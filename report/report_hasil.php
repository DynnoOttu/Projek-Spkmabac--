<?php
require_once('../includes/konek.php'); // koneksi ke DB
require_once __DIR__ . '/../vendor/autoload.php'; // mPDF autoload

$mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
ob_start(); // mulai buffer output
?>

<h2 style="text-align:center;">LAPORAN HASIL PENILAIAN</h2>
<hr />

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>No</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Universitas</th>
            <th>Jenjang</th>
            <th>Fakultas</th>
            <th>DK</th>
            <th>Ortu</th>
            <th>Skor Akhir</th>
            <th>Peringkat</th>
            <th>Rekomendasi</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $query = mysqli_query($koneksi, "SELECT 
            h.*,
            a.nama,
            a.nim,
            a.univ,
            a.jenjang,
            a.fs,
            a.dk,
            a.ortu
        FROM hasil h
        JOIN alternatif a ON h.id_alternatif = a.id_alternatif
        ORDER BY h.peringkat ASC");

        while ($row = mysqli_fetch_assoc($query)) {
            echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama']}</td>
                <td>{$row['nim']}</td>
                <td>{$row['univ']}</td>
                <td>{$row['jenjang']}</td>
                <td>{$row['fs']}</td>
                <td>{$row['dk']}</td>
                <td>{$row['ortu']}</td>
                <td>{$row['skor_akhir']}</td>
                <td>{$row['peringkat']}</td>
                <td>{$row['rekomendasi']}</td>
                <td>{$row['created_at']}</td>
            </tr>";
            $no++;
        }
        ?>
    </tbody>
</table>

<hr />
<small>Dicetak pada: <?php echo date('d F Y, H:i:s'); ?> </small>

<?php
$html = ob_get_clean(); // ambil output dan tutup buffer
$mpdf->WriteHTML($html);
$mpdf->Output('laporan_hasil.pdf', 'I');
exit;
?>