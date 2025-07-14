<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_mabac");

// Cek koneksi
if (!$koneksi) {
	die("Koneksi database gagal: " . mysqli_connect_error());
}
