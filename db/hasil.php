<?php
include '../spkmabac/includes/konek.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard SPK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      overflow-x: hidden;
      font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
      width: 250px;
      background-color:rgb(39, 41, 33);
      color: white;
      height: 100vh;
      position: fixed;
      flex-shrink: 0;
      transition: all 0.3s;
    }

    .sidebar .nav-link {
  padding: 12px 20px;
  border-radius: 6px;
  transition: background 0.2s, color 0.2s;
}
/*.warnaaa pada sidebar pilihan */
.sidebar .nav-link:hover,
.sidebar .nav-link.active {
  background-color:rgb(5, 108, 212);
  color: #fff;
}

.sidebar .nav-link i {
  font-size: 1.1rem;
}

.sidebar .nav-item:last-child {
  margin-top: auto;
}

    .sidebar.collapsed {
      transform: translateX(-100%);
    }

    .sidebar a {
  display: flex;
  align-items: center;
  padding: 12px 16px;
  color: white;
  text-decoration: none;
  width: 100%;
  border-radius: 8px;
  transition: background 0.3s;
}

.sidebar a:hover {
  background-color: #495057;
  text-decoration: none;
}

.sidebar a i {
  margin-right: 10px;
  font-size: 1.2rem;
}

/*.warnaaa pada backround */
    .content {
      margin-left: 250px;
      flex-grow: 1;
      transition: margin-left 0.3s ease;
      background: linear-gradient(135deg, #00b4db, #0083b0);
      min-height: 100vh;
      padding-bottom: 30px;
    }

    .content.collapsed {
      margin-left: 0;
    }

    .top-navbar {
      background-color: white;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 4px rgba(172, 10, 10, 0.1);
    }

    .menu-toggle {
      background: none;
      border: none;
      font-size: 1.5rem;
    }

    .card-menu {
      background: #fff;
      border-radius: 15px;
      padding: 20px;
      text-align: left;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: 0.3s;
      font-weight: 600;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .card-menu:hover {
      transform: translateY(-5px);
    }

    .alert-welcome {
      background-color: #d4edda;
      color: #155724;
      border-radius: 10px;
      padding: 15px;
    }

    @media (max-width: 768px) {
      .sidebar {
        position: absolute;
        z-index: 1000;
      }

      .content {
        margin-left: 0;
      }
    }

    .dropdown-toggle::after {
  display: none !important;
}

  </style>
</head>
<body>

<<!-- Sidebar -->
<nav id="sidebar" class="sidebar bg-dark text-white position-fixed vh-100">
  <div class="sidebar-header text-center d-flex align-items-center justify-content-center" style="height: 58px; border-bottom: 1px solid #6c757d;">
  <h5 class="mb-0">SPK MABAC</h5>
</div>

  <ul class="nav flex-column mt-3 px-2">
    <li class="nav-item">
      <a href="dashboard.php" class="nav-link text-white active">
        <i class="bi bi-house-door me-2"></i> Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a href="alternatif.php" class="nav-link text-white">
        <i class="bi bi-people-fill me-2"></i> Data Alternatif
      </a>
    </li>
    <li class="nav-item">
      <a href="kriteria.php" class="nav-link text-white">
        <i class="bi bi-box-fill me-2"></i> Data Kriteria
      </a>
    </li>
    <li class="nav-item">
      <a href="perhitungan.php" class="nav-link text-white">
        <i class="bi bi-calculator-fill me-2"></i> Data Perhitungan
      </a>
    </li>
    <li class="nav-item">
      <a href="hasil.php" class="nav-link text-white">
        <i class="bi bi-bar-chart-line-fill me-2"></i> Data Hasil Akhir
      </a>
      <hr class="my-2 border-secondary">
    </li>
    <li class="nav-item">
      <a href="user.php" class="nav-link text-white">
        <i class="bi bi-person-lines-fill me-2"></i> Data User
      </a>
    </li>
    <li class="nav-item">
      <a href="profile.php" class="nav-link text-white">
        <i class="bi bi-person-circle me-2"></i> Profil
      </a>
    </li>
  </ul>
</nav>

  

<!-- Main Content -->
<div id="content" class="content">
  
  <!-- Top Navbar -->
  <div class="top-navbar">
    <button class="menu-toggle" onclick="toggleSidebar()">
      <i class="bi bi-list"></i>
    </button>
    <div class="d-flex align-items-center">
      <span class="me-2">ADMIN</span>
      <i class="bi bi-person-circle fs-4"></i>
    </div>
  </div>

  <!-- Page Content -->
  <div class="p-4">
    <h3><i class="bi bi-box"></i> Data Hasil Akhir</h3>
    
    <div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">     


    </div>

    <?php
include '../spkmabac/control/cekinput.php';
include '../spkmabac/control/konektor.php';

if (!$konektor) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
    // Kriteria dan Bobot
$kriteria = [
    'ipk'           => ['bobot' => 0.4, 'type' => 'benefit'],
    'penghasilan'   => ['bobot' => 0.3, 'type' => 'cost'],
    'ekstrakurik'   => ['bobot' => 0.2, 'type' => 'benefit'],
    'prestasi'      => ['bobot' => 0.1, 'type' => 'benefit']
];

// Tambah Data Siswa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($konektor, $_POST['nama']);
    $ipk = floatval($_POST['ipk']);
    $penghasilan = floatval($_POST['penghasilan']);
    $ekstrakurik = intval($_POST['ekstrakurik']);
    $prestasi = intval($_POST['prestasi']);

    $stmt = $konektor->prepare("INSERT INTO siswa (nama, ipk, penghasilan, ekstrakurik, prestasi) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sddii", $nama, $ipk, $penghasilan, $ekstrakurik, $prestasi);
    $stmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Ambil Data
$result = mysqli_query($konektor, "SELECT * FROM siswa");
$dataSiswa = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Proses MABAC jika ada data
if (!empty($dataSiswa)) {
    // Normalisasi
    foreach ($kriteria as $key => $attr) {
        $values = array_column($dataSiswa, $key);
        $min = min($values);
        $max = max($values);

        foreach ($dataSiswa as $i => $row) {
            if ($attr['type'] == 'benefit') {
                $dataSiswa[$i]['n_' . $key] = ($max == $min) ? 0.5 : ($row[$key] - $min) / ($max - $min);
            } else {
                $dataSiswa[$i]['n_' . $key] = ($max == $min) ? 0.5 : ($max - $row[$key]) / ($max - $min);
            }
        }
    }

    // Matriks Tertimbang
    foreach ($dataSiswa as $i => $row) {
        foreach ($kriteria as $key => $attr) {
            $dataSiswa[$i]['v_' . $key] = ($row['n_' . $key] * $attr['bobot']) + $attr['bobot'];
        }
    }

    // Hitung BAA
    $baa = [];
    foreach ($kriteria as $key => $attr) {
        $values = array_column($dataSiswa, 'v_' . $key);
        $baa[$key] = (array_product($values)) ** (1 / count($values));
    }

    // Skor Akhir
    foreach ($dataSiswa as $i => $row) {
        $total = 0;
        foreach ($kriteria as $key => $attr) {
            $total += ($row['v_' . $key] - $baa[$key]);
        }
        $dataSiswa[$i]['total'] = $total;
    }

    // Ranking
    usort($dataSiswa, function ($a, $b) {
        return $b['total'] <=> $a['total'];
    });
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DSS Beasiswa MABAC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-hover tbody tr:hover {
            background: #f8f9fa;
        }
        .card-header {
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-light">
           
        </div>
        
        <br>

       

            <!-- Hasil Ranking -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>IPK</th>
                        <th>Penghasilan</th>
                        <th>Ekskul</th>
                        <th>Prestasi</th>
                        <th>Skor</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1;
                    foreach ($dataSiswa as $siswa): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($siswa['nama']) ?></td>
                            <td><?= number_format($siswa['ipk'], 2) ?></td>
                            <td>Rp<?= number_format($siswa['penghasilan'], 0, ',', '.') ?></td>
                            <td><?= $siswa['ekstrakurik'] ?></td>
                            <td><?= $siswa['prestasi'] ?></td>
                            <td class="fw-bold"><?= number_format($siswa['total'], 4) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
