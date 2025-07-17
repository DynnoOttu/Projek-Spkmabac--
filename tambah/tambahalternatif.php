<?php
// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan path init.php benar
require_once(dirname(__DIR__) . '/includes/init.php');

// Pastikan hanya user yang memiliki hak akses tertentu yang bisa menambahkan data
$user_role = get_role();
if (!in_array($user_role, ['admin', 'kasek', 'guru'])) {
    header('Location: ../login.php');
    exit;
}

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi field yang diperlukan
    $required_fields = ['kode', 'nama', 'univ', 'jenjang', 'fs', 'dk', 'ortu', 'nim'];
    $missing_fields = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (!empty($missing_fields)) {
        header('Location: ../alternatif.php?status=invalid&missing=' . urlencode(implode(',', $missing_fields)));
        exit;
    }

    // Ambil nilai dari form dengan escape
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $univ = mysqli_real_escape_string($koneksi, $_POST['univ']);
    $jenjang = mysqli_real_escape_string($koneksi, $_POST['jenjang']);
    $fs = mysqli_real_escape_string($koneksi, $_POST['fs']);
    $dk = mysqli_real_escape_string($koneksi, $_POST['dk']);
    $ortu = mysqli_real_escape_string($koneksi, $_POST['ortu']);
    $nim = mysqli_real_escape_string($koneksi, $_POST['nim']);

    // Simpan ke database
    $query = "INSERT INTO alternatif (kode, nama, univ, jenjang, fs, dk, ortu, nim) 
              VALUES ('$kode', '$nama', '$univ', '$jenjang', '$fs', '$dk', '$ortu', '$nim')";

    $result = mysqli_query($koneksi, $query);

    // Cek hasil insert
    if ($result) {
        header('Location: ../alternatif.php?status=success');
        exit;
    } else {
        $error_msg = "Error: " . mysqli_error($koneksi);
        error_log($error_msg);
        header('Location: ../alternatif.php?status=error&msg=' . urlencode($error_msg));
        exit;
    }
} else {
    // Akses tidak valid
    header('Location: ../alternatif.php');
    exit;
}
