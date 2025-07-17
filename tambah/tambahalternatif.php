<?php
// Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

try {
    // Log that script started
    error_log("tambahalternatif.php started execution");

    require_once('../includes/init.php');
    error_log("init.php loaded successfully");

    // Debug user role
    $user_role = get_role();
    error_log("User role: " . $user_role);

    if (!in_array($user_role, ['admin', 'kasek', 'guru'])) {
        error_log("Access denied for role: " . $user_role);
        header('Location: login.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log("POST request received");

        // Verify database connection
        if (!$koneksi) {
            throw new Exception("Database connection failed: " . mysqli_connect_error());
        }

        // Get form data with validation
        $required = ['kode', 'nama', 'bobot', 'jenis'];
        $missing = array_diff($required, array_keys($_POST));

        if (!empty($missing)) {
            throw new Exception("Missing fields: " . implode(', ', $missing));
        }

        // Sanitize inputs
        $kode = mysqli_real_escape_string($koneksi, $_POST['kode']);
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $bobot = mysqli_real_escape_string($koneksi, $_POST['bobot']);
        $jenis = mysqli_real_escape_string($koneksi, $_POST['jenis']);

        // Build and execute query
        $query = "INSERT INTO kriteria (kode, nama, bobot, jenis) VALUES ('$kode', '$nama', '$bobot', '$jenis')";
        error_log("Executing query: " . $query);

        $result = mysqli_query($koneksi, $query);

        if (!$result) {
            throw new Exception("Query failed: " . mysqli_error($koneksi));
        }

        error_log("Insert successful");
        header('Location: ../kriteria.php?status=success');
        exit;
    } else {
        error_log("Invalid access method: " . $_SERVER['REQUEST_METHOD']);
        header('Location: kriteria.php');
        exit;
    }
} catch (Exception $e) {
    error_log("ERROR: " . $e->getMessage());
    // Display error message for debugging (remove in production)
    die("Error: " . $e->getMessage());
}
