<?php
include '../koneksi.php';

if (isset($_POST['kode'])) {
    $kode = $_POST['kode'];
    $query = "SELECT nama FROM mahasiswa WHERE kode = '$kode'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo $data['nama'];
    } else {
        echo '';
    }
}
?>
