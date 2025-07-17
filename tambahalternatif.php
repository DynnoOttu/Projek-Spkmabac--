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
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $univ  = mysqli_real_escape_string($koneksi, $_POST['univ']);
    $jenjang  = mysqli_real_escape_string($koneksi, $_POST['jenjang']);
    $fs  = mysqli_real_escape_string($koneksi, $_POST['fs']);
    $dk  = mysqli_real_escape_string($koneksi, $_POST['dk']);
    $ortu  = mysqli_real_escape_string($koneksi, $_POST['ortu']);

    // Validasi sederhana
    if (!empty($kode) && !empty($nama) && !empty($univ) && !empty($jenjang) && !empty($fs) && !empty($dk) && !empty($ortu)) {
        // Simpan ke database
        $query = "INSERT INTO alternatif (kode, nama, univ, jenjang, fs, dk, ortu) VALUES ('$kode', '$nama', '$univ', '$jenjang', '$fs', '$dk', '$ortu')";
        $result = mysqli_query($koneksi, $query);

        // Cek hasil insert
        if ($result) {
            header('Location: ../alternatif.php?status=success');
            exit;
        } else {
            header('Location: ../alternatif.php?status=error');
            exit;
        }
    } else {
        // Data tidak lengkap
        header('Location: ../alternatif.php?status=invalid');
        exit;
    }
} else {
    // Akses tidak valid
    header('Location: ../alternatif.php');
    exit;
}
