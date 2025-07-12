<?php

/**
 * Kumpulan fungsi utilitas untuk sistem DSS Beasiswa
 */

/**
 * Mendapatkan statistik cepat untuk dashboard
 * @param mysqli $koneksi Koneksi database
 * @return array Array berisi statistik
 */
function getQuickStats($koneksi)
{
    $stats = array();

    // Total alternatif
    $sql = "SELECT COUNT(*) as total FROM alternatif";
    $result = $koneksi->query($sql);
    $stats['total_alternatif'] = $result->fetch_assoc()['total'];

    // Direkomendasikan
    $sql = "SELECT COUNT(*) as total FROM hasil WHERE rekomendasi = 'Direkomendasikan'";
    $result = $koneksi->query($sql);
    $stats['direkomendasikan'] = $result->fetch_assoc()['total'];

    // Tidak direkomendasikan
    $sql = "SELECT COUNT(*) as total FROM hasil WHERE rekomendasi = 'Tidak Direkomendasikan'";
    $result = $koneksi->query($sql);
    $stats['tidak_direkomendasikan'] = $result->fetch_assoc()['total'];

    // Fakultas dengan pendaftar terbanyak
    $sql = "SELECT fs, COUNT(*) as jumlah 
        FROM alternatif 
        GROUP BY fs 
        ORDER BY jumlah DESC 
        LIMIT 1";
    $result = $koneksi->query($sql);
    $row = $result->fetch_assoc(); // TAMBAHKAN baris ini
    if ($row) {
        $stats['fs_terbanyak'] = $row['fs'];
        $stats['fs_terbanyak_jumlah'] = $row['jumlah'];
    } else {
        $stats['fs_terbanyak'] = '-';
        $stats['fs_terbanyak_jumlah'] = 0;
    }

    // Distribusi fakultas
    $sql = "SELECT fs, COUNT(*) as jumlah 
            FROM alternatif 
            GROUP BY fs 
            ORDER BY jumlah DESC";
    $result = $koneksi->query($sql);
    $stats['fs_distribusi'] = array();
    while ($row = $result->fetch_assoc()) {
        $stats['fs_distribusi'][] = $row;
    }

    return $stats;
}

/**
 * Mendapatkan hasil terbaru
 * @param mysqli $koneksi Koneksi database
 * @param int $limit Jumlah data yang diambil
 * @return array Array berisi hasil terbaru
 */
function getRecentResults($koneksi, $limit = 5)
{
    $sql = "SELECT m.nama, m.univ, m.jenjeng, m.fs, m.dk, m.ortu, h.skor_akhir, h.peringkat, h.rekomendasi 
            FROM alternatif m 
            JOIN hasil h ON m.id_alternatif = h.id_alternatif
            ORDER BY h.created_at DESC 
            LIMIT ?";

    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $recent_results = array();
    while ($row = $result->fetch_assoc()) {
        $recent_results[] = $row;
    }

    $stmt->close();
    return $recent_results;
}

/**
 * Membersihkan input dari potensi serangan XSS dan SQL Injection
 * @param string $data Input yang akan dibersihkan
 * @param mysqli $koneksi Koneksi database (untuk real_escape_string)
 * @return string Data yang sudah dibersihkan
 */
function cleanInput($data, $koneksi)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $koneksi->real_escape_string($data);
    return $data;
}

/**
 * Mengubah nilai numerik menjadi label untuk kriteria tertentu
 * @param int $id_kriteria ID kriteria
 * @param float $nilai Nilai yang akan dikonversi
 * @return string Label yang sesuai
 */
function convertToLabel($id_kriteria, $nilai)
{
    switch ($id_kriteria) {
        case 2: // Penghasilan Orang Tua
            $labels = [
                5 => '< Rp 1.000.000',
                4 => 'Rp 1.000.000 - Rp 1.999.000',
                3 => 'Rp 2.000.000 - Rp 2.999.000',
                2 => 'Rp 3.000.000 - Rp 3.999.000',
                1 => '≥ Rp 4.000.000'
            ];
            return $labels[(int)$nilai] ?? 'Tidak diketahui';

        case 4: // Prestasi
            $labels = [
                1 => '0 prestasi',
                2 => '1-2 prestasi',
                3 => '3-5 prestasi',
                4 => '6-10 prestasi',
                5 => '>10 prestasi'
            ];
            return $labels[(int)$nilai] ?? 'Tidak diketahui';

        case 5: // Dampak Sosial
            $labels = [
                1 => 'Sangat Rendah',
                2 => 'Rendah',
                3 => 'Sedang',
                4 => 'Tinggi',
                5 => 'Sangat Tinggi'
            ];
            return $labels[(int)$nilai] ?? 'Tidak diketahui';

        case 6: // Daerah Tertinggal
            return $nilai == 1 ? 'Ya' : 'Tidak';

        default:
            return $nilai;
    }
}

/**
 * Menghasilkan warna acak untuk chart
 * @param int $count Jumlah warna yang dibutuhkan
 * @return array Array berisi warna dalam format rgba
 */
function generateChartColors($count)
{
    $colors = [];
    for ($i = 0; $i < $count; $i++) {
        $r = rand(50, 200);
        $g = rand(50, 200);
        $b = rand(50, 200);
        $colors[] = "rgba($r, $g, $b, 0.7)";
    }
    return $colors;
}

/**
 * Mengecek apakah proses MABAC sudah pernah dijalankan
 * @param mysqli $koneksi Koneksi database
 * @return bool True jika sudah pernah dijalankan, false jika belum
 */
function isMabacProcessed($koneksi)
{
    $sql = "SELECT COUNT(*) as total FROM hasil";
    $result = $koneksi->query($sql);
    return $result->fetch_assoc()['total'] > 0;
}

/**
 * Mendapatkan detail alternatif beserta nilai kriteria
 * @param mysqli $koneksi Koneksi database
 * @param int $id_alternatif ID alternatif
 * @return array Array berisi data alternatif dan nilai kriteria
 */
function getMahasiswaWithCriteria($koneksi, $id_alternatif)
{
    $data = array();

    // Data dasar alternatif
    $sql = "SELECT * FROM alternatif WHERE id_alternatif = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_alternatif);
    $stmt->execute();
    $result = $stmt->get_result();
    $data['alternatif'] = $result->fetch_assoc();
    $stmt->close();

    // Data hasil
    $sql = "SELECT * FROM hasil WHERE id_alternatif = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_alternatif);
    $stmt->execute();
    $result = $stmt->get_result();
    $data['hasil'] = $result->fetch_assoc();
    $stmt->close();

    // Data Perhitungan kriteria
    $sql = "SELECT p.id_kriteria, k.nama, k.jenis, p.nilai 
            FROM perhitungan p
            JOIN kriteria k ON p.id_kriteria = k.id_kriteria
            WHERE p.id_alternatif = ?
            ORDER BY p.id_kriteria";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_alternatif);
    $stmt->execute();
    $result = $stmt->get_result();

    $data['kriteria'] = array();
    while ($row = $result->fetch_assoc()) {
        $data['kriteria'][] = $row;
    }
    $stmt->close();

    return $data;
}

/**
 * Mengkonversi skor menjadi kategori rekomendasi
 * @param float $skor Skor akhir
 * @param float $threshold Batas minimal untuk direkomendasikan
 * @return string Kategori rekomendasi
 */
function getRecommendationCategory($skor, $threshold = 0.5)
{
    if ($skor >= $threshold) {
        return 'Direkomendasikan';
    } else {
        return 'Tidak Direkomendasikan';
    }
}

/**
 * Mendapatkan bobot kriteria berdasarkan ID
 * @param mysqli $koneksi Koneksi database
 * @param int $id_kriteria ID kriteria
 * @return string Bobot kriteria dalam persen
 */
function getBobotKriteria($koneksi, $id_kriteria)
{
    $sql = "SELECT bobot FROM kriteria WHERE id_kriteria = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_kriteria);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return ($row['bobot'] * 100) . '%';
}

/**
 * Mengekspor data alternatif tunggal
 * @param mysqli $koneksi Koneksi database
 * @param int $id_alternatif ID alternatif
 * @param string $type Jenis ekspor (pdf/excel)
 */
function exportSingleMahasiswa($koneksi, $id_alternatif, $type = 'pdf')
{
    $data = getMahasiswaWithCriteria($koneksi, $id_alternatif);

    if ($type == 'excel') {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="detail_alternatif_' . $data['alternatif']['kode'] . '.xls"');
        header('Cache-Control: max-age=0');

        echo '<table border="1">
                <tr>
                    <th colspan="2">Detail Alternatif</th>
                </tr>
                <tr>
                    <th>Kode Alternatif</th>
                    <td>' . $data['alternatif']['kode'] . '</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>' . $data['alternatif']['nama'] . '</td>
                </tr>
                <tr>
                    <th>Universitas</th>
                    <td>' . $data['alternatif']['univ'] . '</td>
                </tr>
                <tr>
                    <th>Skor Akhir</th>
                    <td>' . $data['hasil']['skor_akhir'] . '</td>
                </tr>
                <tr>
                    <th>Peringkat</th>
                    <td>' . $data['hasil']['peringkat'] . '</td>
                </tr>
                <tr>
                    <th>Rekomendasi</th>
                    <td>' . $data['hasil']['rekomendasi'] . '</td>
                </tr>
                <tr>
                    <th colspan="2">Detail Kriteria</th>
                </tr>
                <tr>
                    <th>Kriteria</th>
                    <th>Nilai</th>
                </tr>';

        foreach ($data['kriteria'] as $kriteria) {
            echo '<tr>
                    <td>' . $kriteria['nama'] . '</td>
                    <td>' . $kriteria['nilai'] . '</td>
                  </tr>';
        }

        echo '</table>';
    } elseif ($type == 'pdf') {
        require_once('tcpdf/tcpdf.php');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Detail alternatif - ' . $data['alternatif']['kode']);
        $pdf->SetHeaderData('', 0, 'Detail alternatif', 'DSS Beasiswa - Metode MABAC');
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->AddPage();

        $html = '<h1>Detail alternatif</h1>
                 <table border="1" cellpadding="5">
                    <tr>
                        <th width="30%">NIM</th>
                        <td>' . $data['alternatif']['nim'] . '</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>' . $data['alternatif']['nama'] . '</td>
                    </tr>
                    <tr>
                        <th>Fakultas</th>
                        <td>' . $data['alternatif']['fs'] . '</td>
                    </tr>
                    <tr>
                        <th>Skor Akhir</th>
                        <td>' . $data['hasil']['skor_akhir'] . '</td>
                    </tr>
                    <tr>
                        <th>Peringkat</th>
                        <td>' . $data['hasil']['peringkat'] . '</td>
                    </tr>
                    <tr>
                        <th>Rekomendasi</th>
                        <td>' . $data['hasil']['rekomendasi'] . '</td>
                    </tr>
                 </table>
                 
                 <h2 style="margin-top: 20px;">Detail Penilaian Kriteria</h2>
                 <table border="1" cellpadding="5">
                    <tr>
                        <th width="30%">Kriteria</th>
                        <th width="20%">Jenis</th>
                        <th width="15%">Bobot</th>
                        <th width="15%">Nilai</th>
                        <th width="20%">Keterangan</th>
                    </tr>';

        foreach ($data['kriteria'] as $kriteria) {
            $html .= '<tr>
                        <td>' . $kriteria['nama_kriteria'] . '</td>
                        <td>' . ($kriteria['jenis'] == 'benefit' ? 'Benefit' : 'Cost') . '</td>
                        <td>' . (getBobotKriteria($koneksi, $kriteria['id_kriteria'])) . '</td>
                        <td>' . $kriteria['nilai'] . '</td>
                        <td>' . convertToLabel($kriteria['id_kriteria'], $kriteria['nilai']) . '</td>
                      </tr>';
        }

        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('detail_alternatif_' . $data['alternatif']['nim'] . '.pdf', 'D');
    }
}
