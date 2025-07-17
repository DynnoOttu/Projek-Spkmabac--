<?php
require_once('../includes/init.php');

// tangkap data dari form
$id_alternatif = $_POST['id_alternatif'];
$kode  = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['kode']));
$nama  = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['nama']));
$univ = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['univ']));
$jenjang = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['jenjang']));
$fs = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['fs']));
$dk = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['dk']));
$ortu = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['ortu']));

// update data ke database
$result = mysqli_query($koneksi, "UPDATE alternatif SET kode='$kode', nama='$nama', univ='$univ', jenjang='$jenjang', fs='$fs', dk='$dk', ortu='$ortu' WHERE id_alternatif='$id_alternatif'");

if (!$result) {
    die("Gagal update: " . mysqli_error($koneksi));
}

// kembali ke halaman kriteria
header("Location: ../alternatif.php?m=sukses");
exit();
?>
