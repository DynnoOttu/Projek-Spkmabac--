<?php
//Script ini diletakan pada halaman cekLogin.php
// mengaktifkan session php
session_start();

// menghubungkan dengan koneksi
include '../asset/konektor.php';

//Fungsi untuk mencegah inputan karakter yang tidak sesuai
include '../asset/cekinput.php';

// menangkap data yang dikirim dari form
$username = input($_POST['username']);
$password = input($_POST['password']);

// menyeleksi data admin dengan username dan telepon yang sesuai
$data = mysqli_query($konektor, "SELECT * FROM admin WHERE username='$username' AND password='$password'");

// menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($data);

if ($cek > 0) {
  $_SESSION['username'] = $username;
  $_SESSION['status'] = "login";
  header("location:../dashboard.php");
} else {
  header("location:../index.php?pesan=gagal");
}