<?php
require_once('../includes/init.php');

// Debug 1: Cek session dan role user
var_dump('User Role:', get_role());
var_dump('Session Data:', $_SESSION);

// Pastikan hanya user yang memiliki hak akses tertentu yang bisa menambahkan data
$user_role = get_role();
if (!in_array($user_role, ['admin', 'kasek', 'guru'])) {
    var_dump('Akses ditolak. Role user:', $user_role);
    header('Location: login.php');
    exit;
}

// Debug 2: Cek method request
var_dump('Request Method:', $_SERVER['REQUEST_METHOD']);
var_dump('POST Data:', $_POST);

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug 3: Cek koneksi database
    var_dump('Koneksi Database:', $koneksi);

    if (!$koneksi) {
        die('Koneksi database gagal: ' . mysqli_connect_error());
    }

    // Ambil nilai dari form
    $kode = isset($_POST['kode']) ? mysqli_real_escape_string($koneksi, $_POST['kode']) : '';
    $nama = isset($_POST['nama']) ? mysqli_real_escape_string($koneksi, $_POST['nama']) : '';
    $bobot = isset($_POST['bobot']) ? mysqli_real_escape_string($koneksi, $_POST['bobot']) : '';
    $jenis = isset($_POST['jenis']) ? mysqli_real_escape_string($koneksi, $_POST['jenis']) : '';

    // Debug 4: Cek data yang akan diinsert
    var_dump('Data sebelum insert:', $kode, $nama, $bobot, $jenis);

    // Validasi sederhana
    if (!empty($kode) && !empty($nama) && !empty($bobot) && !empty($jenis)) {
        // Simpan ke database
        $query = "INSERT INTO kriteria (kode, nama, bobot, jenis) VALUES ('$kode', '$nama','$bobot','$jenis')";

        // Debug 5: Tampilkan query SQL
        var_dump('Query SQL:', $query);

        $result = mysqli_query($koneksi, $query);

        // Debug 6: Cek hasil query
        if ($result) {
            var_dump('Insert berhasil');
            header('Location: ../kriteria.php?status=success');
            exit;
        } else {
            // Debug 7: Tampilkan error MySQL
            var_dump('Error MySQL:', mysqli_error($koneksi));
            header('Location: ../kriteria.php?status=error');
            exit;
        }
    } else {
        // Debug 8: Data tidak lengkap
        var_dump('Data tidak lengkap:', empty($kode), empty($nama), empty($bobot), empty($jenis));
        header('Location: ../kriteria.php?status=invalid');
        exit;
    }
} else {
    // Debug 9: Akses tidak valid
    var_dump('Akses langsung ke script tanpa POST');
    header('Location: kriteria.php');
    exit;
}
