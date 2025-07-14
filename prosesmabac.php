<?php
include 'includes/konek.php';
include 'includes/functions.php';

if (empty($_POST)) {
    header("Location: perhitungan.php?error=empty_data");
    exit();
}

$kode = trim($_POST['kode']);
$nama = trim($_POST['nama']);
$ipk = (int)$_POST['ipk'];
$dokumen = (int)$_POST['dokumen'];
$kk = (int)$_POST['kk'];
$sbl = (int)$_POST['sbl'];
$penghasilan = (int)$_POST['penghasilan'];
$prestasi = (int)$_POST['prestasi'];

$min_values = [
    'ipk' => 1,
    'dokumen' => 1,
    'kk' => 1,
    'sbl' => 1,
    'penghasilan' => 1,
    'prestasi' => 1
];

foreach ($min_values as $field => $min) {
    if (!is_numeric($$field) || $$field < $min) {
        header("Location: perhitungan.php?error=invalid_$field");
        exit();
    }
}

$id_alternatif = getAlternatifId($koneksi, $kode);
if (!$id_alternatif) {
    $sql = "INSERT INTO alternatif (kode, nama) VALUES (?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ss", $kode, $nama);
    if (!$stmt->execute()) {
        error_log("DB Error (Insert Alternatif): " . $stmt->error);
        header("Location: perhitungan.php?error=db_error");
        exit();
    }
    $id_alternatif = $stmt->insert_id;
    $stmt->close();
}

saveNilaiKriteria($koneksi, $id_alternatif, [
    3 => $ipk,
    6 => $dokumen,
    7 => $kk,
    8 => $sbl,
    9 => $prestasi
]);

if (!prosesMABAC($koneksi, $id_alternatif)) {
    header("Location: perhitungan.php?error=process_failed");
    exit();
}

header("Location: hasil.php");
exit();

function getAlternatifId($koneksi, $kode)
{
    $sql = "SELECT id_alternatif FROM alternatif WHERE kode = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $kode);
    $stmt->execute();
    $stmt->bind_result($id_alternatif);
    if ($stmt->fetch()) {
        $stmt->close();
        return $id_alternatif;
    }
    $stmt->close();
    return false;
}

function saveNilaiKriteria($koneksi, $id_alternatif, $kriteria_values)
{
    foreach ($kriteria_values as $id_kriteria => $nilai) {
        $check = $koneksi->prepare("SELECT id_perhitungan FROM perhitungan WHERE id_alternatif = ? AND id_kriteria = ?");
        $check->bind_param("ii", $id_alternatif, $id_kriteria);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $sql = "UPDATE perhitungan SET nilai = ? WHERE id_alternatif = ? AND id_kriteria = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("dii", $nilai, $id_alternatif, $id_kriteria);
        } else {
            $sql = "INSERT INTO perhitungan (id_alternatif, id_kriteria, nilai) VALUES (?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("iid", $id_alternatif, $id_kriteria, $nilai);
        }
        $stmt->execute();
        $stmt->close();
        $check->close();
    }
}

function prosesMABAC($koneksi, $id_filter = null)
{
    try {
        // Ambil data alternatif dan perhitungan
        $alternatif = [];
        $sql = "SELECT m.id_alternatif, m.nama, p.id_kriteria, p.nilai 
                FROM alternatif m 
                JOIN perhitungan p ON m.id_alternatif = p.id_alternatif
                ORDER BY m.id_alternatif, p.id_kriteria";
        $result = $koneksi->query($sql);

        while ($row = $result->fetch_assoc()) {
            $id = (int)$row['id_alternatif'];
            $kriteria = (int)$row['id_kriteria'];
            $alternatif[$id]['nama'] = $row['nama'];
            $alternatif[$id]['kriteria'][$kriteria] = (float)$row['nilai'];
        }

        // Ambil bobot dan jenis
        $bobot = [];
        $res = $koneksi->query("SELECT id_kriteria, bobot, jenis FROM kriteria ORDER BY id_kriteria");
        while ($row = $res->fetch_assoc()) {
            $bobot[$row['id_kriteria']] = [
                'bobot' => (float)$row['bobot'],
                'jenis' => strtolower($row['jenis'])
            ];
        }

        // Matriks X dan max/min
        $X = [];
        $max_min = [];
        foreach ($alternatif as $id => $data) {
            foreach ($bobot as $k => $_) {
                $nilai = $data['kriteria'][$k] ?? 0;
                $X[$id][$k] = $nilai;
                if (!isset($max_min[$k])) {
                    $max_min[$k] = ['max' => $nilai, 'min' => $nilai];
                } else {
                    $max_min[$k]['max'] = max($max_min[$k]['max'], $nilai);
                    $max_min[$k]['min'] = min($max_min[$k]['min'], $nilai);
                }
            }
        }

        // Matriks N
        $N = [];
        foreach ($X as $id => $nilai_kriteria) {
            foreach ($nilai_kriteria as $k => $v) {
                $range = $max_min[$k]['max'] - $max_min[$k]['min'];
                $N[$id][$k] = ($range == 0) ? 1 : (
                    $bobot[$k]['jenis'] === 'benefit'
                    ? ($v - $max_min[$k]['min']) / $range
                    : ($max_min[$k]['max'] - $v) / $range
                );
            }
        }

        // Matriks V
        $V = [];
        foreach ($N as $id => $nilai_kriteria) {
            foreach ($nilai_kriteria as $k => $v) {
                $V[$id][$k] = $bobot[$k]['bobot'] * ($v + 1);
            }
        }

        // Titik batas G
        $G = [];
        foreach ($bobot as $k => $_) {
            $product = 1;
            foreach ($V as $id => $vals) {
                $product *= $vals[$k];
            }
            $G[$k] = pow($product, 1 / count($V));
        }

        // Hitung skor akhir (S)
        $S = [];
        foreach ($V as $id => $vals) {
            $sum = 0;
            foreach ($vals as $k => $val) {
                $sum += $val - $G[$k];
            }
            $S[$id] = $sum;
        }

        // Urutkan skor terbesar
        arsort($S);

        // Peringkat unik selalu naik
        $peringkat = 1;
        foreach ($S as $id => $score) {
            if ($id_filter !== null && $id != $id_filter) {
                continue;
            }

            $rekomendasi = ($peringkat <= 10) ? 'Direkomendasikan' : 'Tidak Direkomendasikan';

            $stmt = $koneksi->prepare("INSERT INTO hasil (id_alternatif, skor_akhir, peringkat, rekomendasi) 
                VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                skor_akhir = VALUES(skor_akhir), 
                peringkat = VALUES(peringkat), 
                rekomendasi = VALUES(rekomendasi)");
            $stmt->bind_param("idis", $id, $score, $peringkat, $rekomendasi);
            $stmt->execute();
            $stmt->close();

            $peringkat++; // SELALU naik meski skor sama
        }

        return true;
    } catch (Exception $e) {
        error_log("MABAC Error: " . $e->getMessage());
        return false;
    }
}
