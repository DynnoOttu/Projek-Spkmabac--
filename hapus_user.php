<?php
require_once('includes/init.php');
$user_role = get_role();

if (!in_array($user_role, ['admin', 'kasek', 'guru'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($koneksi, "DELETE FROM admin WHERE id_admin = $id");
}

header("Location: user.php");
exit;
?>
