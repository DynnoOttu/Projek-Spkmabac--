<?php
require_once('../includes/init.php');

// tangkap data dari form
$id_kriteria = $_POST['id_kriteria'];
$kode  = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['kode']));
$nama  = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['nama']));
$bobot = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['bobot']));
$jenis = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['jenis']));

// update data ke database
$result = mysqli_query($koneksi, "UPDATE kriteria SET kode='$kode', nama='$nama', bobot='$bobot', jenis='$jenis' WHERE id_kriteria='$id_kriteria'");

if (!$result) {
    die("Gagal update: " . mysqli_error($koneksi));
}

// kembali ke halaman kriteria
header("Location: ../kriteria.php?m=sukses");
exit();
?>
