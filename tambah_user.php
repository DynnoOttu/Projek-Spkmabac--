<?php
require_once('includes/init.php');

// Cek hak akses user
$user_role = get_role();
if (!in_array($user_role, ['admin'])) {
    header('Location: login.php');
    exit;
}

// Proses form jika disubmit dengan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password_plain = $_POST['password']; // disimpan sebelum di-hash

    // Validasi input tidak boleh kosong
    if (!empty($nama) && !empty($username) && !empty($password_plain)) {

        // Cek apakah username sudah digunakan
        $cek = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            header('Location: user.php?status=exists'); // Username sudah ada
            exit;
        }

        // Enkripsi password
        $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

        // Simpan ke tabel admin
        $query = "INSERT INTO admin (nama, username, password) VALUES ('$nama', '$username', '$password_hashed')";
        $result = mysqli_query($koneksi, $query);

        if ($result) {
            header('Location: user.php?status=success');
            exit;
        } else {
            header('Location: user.php?status=error');
            exit;
        }
    } else {
        header('Location: user.php?status=invalid');
        exit;
    }
} else {
    header('Location: user.php');
    exit;
}
?>
