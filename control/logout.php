<?php 
//Script ini diletakan pada sebuah halaman yang diberi nama logout.php
// mengaktifkan session
session_start();

// menghapus semua session
$_SESSION['status']='';
$_SESSION['username']='';

unset($_SESSION['status']);
unset($_SESSION['username']);

session_unset();
session_destroy();

// mengalihkan halaman sambil mengirim pesan logout
header("location:../index.php?pesan=logout");
?>

//Link untuk akses logout
<a href="logout.php">Logout</a>