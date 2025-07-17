<?php
require_once('../includes/init.php');

// Pastikan hanya user yang memiliki hak akses tertentu yang bisa menambahkan data
$user_role = get_role();
if (!in_array($user_role, ['admin', 'kasek', 'guru'])) {
    header('Location: login.php');
    exit;
}

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil nilai dari form
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $bobot  = mysqli_real_escape_string($koneksi, $_POST['bobot']);
    $jenis  = mysqli_real_escape_string($koneksi, $_POST['jenis']);

    // Validasi sederhana
    if (!empty($kode) && !empty($nama) && !empty($bobot) && !empty($jenis)) {
        // Simpan ke database
        $query = "INSERT INTO kriteria (kode, nama, bobot, jenis) VALUES ('$kode', '$nama','$bobot','$jenis')";
        $result = mysqli_query($koneksi, $query);

        // Cek hasil insert
        if ($result) {
            header('Location: ../kriteria.php?status=success');
            exit;
        } else {
            header('Location: ../kriteria.php?status=error');
            exit;
        }
    } else {
        // Data tidak lengkap
        header('Location: ../kriteria.php?status=invalid');
        exit;
    }
} else {
    // Akses tidak valid
    header('Location: kriteria.php');
    exit;
}
