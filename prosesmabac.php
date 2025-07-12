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
    1 => $ipk,
    2 => $dokumen,
    3 => $kk,
    4 => $sbl,
    5 => $penghasilan,
    6 => $prestasi
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
        $alternatif = [];
        $sql = "SELECT m.id_alternatif, m.nama, p.id_kriteria, p.nilai 
                FROM alternatif m 
                JOIN perhitungan p ON m.id_alternatif = p.id_alternatif";
        if ($id_filter !== null) {
            $sql .= " WHERE m.id_alternatif = " . intval($id_filter);
        }
        $sql .= " ORDER BY m.id_alternatif, p.id_kriteria";

        $result = $koneksi->query($sql);
        while ($row = $result->fetch_assoc()) {
            $id_kriteria = (int)$row['id_kriteria'];
            $id_alt = $row['id_alternatif'];
            if (!isset($alternatif[$id_alt]['nama'])) {
                $alternatif[$id_alt]['nama'] = $row['nama'];
            }
            if ($id_kriteria >= 1 && $id_kriteria <= 6) {
                $alternatif[$id_alt]['kriteria'][$id_kriteria] = $row['nilai'];
            }
        }

        $bobot = [];
        $result = $koneksi->query("SELECT id_kriteria, bobot, jenis FROM kriteria ORDER BY id_kriteria");
        while ($row = $result->fetch_assoc()) {
            $bobot[$row['id_kriteria']] = [
                'bobot' => $row['bobot'],
                'jenis' => $row['jenis']
            ];
        }

        $X = [];
        $max_min = [];
        foreach ($alternatif as $id => $data) {
            foreach ($bobot as $kid => $b) {
                $nilai = isset($data['kriteria'][$kid]) ? $data['kriteria'][$kid] : 0;
                $X[$id][$kid] = $nilai;
                if (!isset($max_min[$kid])) {
                    $max_min[$kid] = ['max' => $nilai, 'min' => $nilai];
                } else {
                    $max_min[$kid]['max'] = max($max_min[$kid]['max'], $nilai);
                    $max_min[$kid]['min'] = min($max_min[$kid]['min'], $nilai);
                }
            }
        }

        $N = [];
        foreach ($X as $id => $kriteria_values) {
            foreach ($kriteria_values as $kid => $nilai) {
                $range = $max_min[$kid]['max'] - $max_min[$kid]['min'];
                if ($range == 0) {
                    $N[$id][$kid] = 1;
                } else {
                    $N[$id][$kid] = ($bobot[$kid]['jenis'] === 'benefit')
                        ? ($nilai - $max_min[$kid]['min']) / $range
                        : ($max_min[$kid]['max'] - $nilai) / $range;
                }
            }
        }

        $V = [];
        foreach ($N as $id => $kriteria_values) {
            foreach ($kriteria_values as $kid => $val) {
                $V[$id][$kid] = $bobot[$kid]['bobot'] * ($val + 1);
            }
        }

        $G = [];
        foreach (array_keys($bobot) as $kid) {
            $product = 1;
            foreach ($V as $id => $vals) {
                $product *= $vals[$kid];
            }
            $G[$kid] = pow($product, 1 / count($V));
        }

        $S = [];
        foreach ($V as $id => $vals) {
            $sum = 0;
            foreach ($vals as $kid => $val) {
                $sum += $val - $G[$kid];
            }
            $S[$id] = $sum;
        }

        arsort($S);
        $ranking = 1;
        foreach ($S as $id => $skor) {
            $rekomendasi = ($ranking <= 10) ? 'Direkomendasikan' : 'Tidak Direkomendasikan';
            $stmt = $koneksi->prepare("INSERT INTO hasil (id_alternatif, skor_akhir, peringkat, rekomendasi) 
                VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE skor_akhir = VALUES(skor_akhir), peringkat = VALUES(peringkat), rekomendasi = VALUES(rekomendasi)");
            $stmt->bind_param("idis", $id, $skor, $ranking, $rekomendasi);
            $stmt->execute();
            $stmt->close();
            $ranking++;
        }

        return true;
    } catch (Exception $e) {
        error_log("MABAC Error: " . $e->getMessage());
        return false;
    }
}
