<?php
require_once('../includes/init.php');

// tangkap data dari form
$id_admin = $_POST['id_admin'];
$nama  = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['nama']));
$username  = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['username']));
$password = htmlspecialchars(mysqli_real_escape_string($koneksi, $_POST['password']));
 
// update data ke database
$result = mysqli_query($koneksi, "UPDATE admin SET nama='$nama', username='$username', password='$password' WHERE id_admin='$id_admin'");

if (!$result) {
    die("Gagal update: " . mysqli_error($koneksi));
}

// kembali ke halaman kriteria
header("Location: ../user.php?m=sukses");
exit();
?>
