<?php
session_start();
require_once('includes/konek.php');
require_once('includes/functions.php');

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: hasil.php");
  exit();
}

$id_alternatif = (int)$_GET['id'];

// Ambil data dari database
$data = getMahasiswaWithCriteria($koneksi, $id_alternatif);

// Jika data tidak ditemukan
if (empty($data['alternatif'])) {
  header("Location: hasil.php");
  exit();
}


?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Detail Mahasiswa - SPK MABAC</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
      background-color: #272921;
      color: white;
      height: 100vh;
      position: fixed;
      flex-shrink: 0;
      transition: all 0.3s;
    }

    .sidebar .nav-link {
      padding: 12px 20px;
      border-radius: 6px;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: #056cd4;
      color: #fff;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      color: white;
      text-decoration: none;
      width: 100%;
      padding: 12px 16px;
      border-radius: 8px;
    }

    .sidebar a:hover {
      background-color: #495057;
    }

    .sidebar a i {
      margin-right: 10px;
      font-size: 1.2rem;
    }

    .sidebar.collapsed {
      margin-left: -250px;
    }

    .content.collapsed {
      margin-left: 0;
    }

    .content {
      margin-left: 250px;
      flex-grow: 1;
      background: linear-gradient(135deg, #00b4db, #0083b0);
      min-height: 100vh;
      padding-bottom: 30px;
      transition: all 0.3s;
    }

    .top-navbar {
      background-color: white;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .menu-toggle {
      background: none;
      border: none;
      font-size: 1.5rem;
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
  </style>
</head>

<body>

  <!-- Sidebar -->
  <nav class="sidebar">
    <div class="sidebar-header text-center py-3 border-bottom">
      <h5>SPK MABAC</h5>
    </div>
    <ul class="nav flex-column mt-3 px-2">
      <li class="nav-item"><a href="dashboard.php" class="nav-link active"><i class="bi bi-house-door me-2"></i>Dashboard</a></li>
      <li class="nav-item"><a href="alternatif.php" class="nav-link"><i class="bi bi-people-fill me-2"></i>Data Alternatif</a></li>
      <li class="nav-item"><a href="kriteria.php" class="nav-link"><i class="bi bi-box-fill me-2"></i>Data Kriteria</a></li>
      <li class="nav-item"><a href="perhitungan.php" class="nav-link"><i class="bi bi-calculator-fill me-2"></i>Data Perhitungan</a></li>
      <li class="nav-item"><a href="hasil.php" class="nav-link"><i class="bi bi-bar-chart-line-fill me-2"></i>Data Hasil Akhir</a></li>
      <li>
        <hr class="my-2 border-secondary">
      </li>
      <li class="nav-item"><a href="user.php" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i>Data User</a></li>
    </ul>
  </nav>

  <!-- Main Content -->
  <div class="content">
    <div class="top-navbar">
      <button class="menu-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>

      <div class="dropdown">
        <a class="text-dark dropdown-toggle text-decoration-none" href="#" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
          <span class="me-2 text-uppercase fw-normal"><?= $_SESSION['username']; ?></span>
          <i class="bi bi-person-circle fs-4 text-secondary"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
          <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Profil</a></li>
          <li><a class="dropdown-item" href="../spkmabac/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
        </ul>
      </div>
    </div>

    <div class="container py-4">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
          <h5 class="mb-0">Detail Mahasiswa</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <table class="table table-bordered">
                <tr class="table-primary">
                  <th width="35%">Kode Alternatif</th>
                  <td><?= htmlspecialchars($data['alternatif']['kode']); ?></td>
                </tr>
                <tr>
                  <th>Nama Lengkap</th>
                  <td><?= htmlspecialchars($data['alternatif']['nama']); ?></td>
                </tr>
                <tr class="table-primary">
                  <th>Universitas</th>
                  <td><?= htmlspecialchars($data['alternatif']['univ']); ?></td>
                </tr>
                <tr>
                  <th>Tanggal Pendaftaran</th>
                  <td><?= date('d F Y H:i', strtotime($data['alternatif']['created_at'])); ?></td>
                </tr>
              </table>
            </div>
            <div class="col-md-4">
              <div class="card bg-light">
                <div class="card-body text-center">
                  <h4 class="card-title">Hasil Akhir</h4>
                  <div class="display-4 fw-bold text-primary mb-2">
                    <?= number_format($data['hasil']['skor_akhir'], 4); ?>
                  </div>
                  <span class="badge <?= $data['hasil']['rekomendasi'] == 'Direkomendasikan' ? 'bg-success' : 'bg-danger'; ?> fs-6">
                    <?= $data['hasil']['rekomendasi']; ?>
                  </span>
                  <div class="mt-3">
                    <small class="text-muted">Peringkat: <?= $data['hasil']['peringkat']; ?></small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container py-4">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
          <h5 class="mb-0">Detail Penilaian Kriteri</h5>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead class="table-primary">
                <tr>
                  <th>Kriteria</th>
                  <th>Jenis</th>
                  <th>Bobot</th>
                  <th>Nilai</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($data['kriteria'] as $kriteria): ?>
                  <tr>
                    <td><?= htmlspecialchars($kriteria['nama']); ?></td>
                    <td><?= $kriteria['jenis'] == 'benefit' ? 'Benefit' : 'Cost'; ?></td>
                    <td><?= getBobotKriteria($koneksi, $kriteria['id_kriteria']); ?></td>
                    <td><?= $kriteria['nilai']; ?></td>
                    <td><?= convertToLabel($kriteria['id_kriteria'], $kriteria['nilai']); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>

    <div class="container py-4">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
          <h5 class="mb-0">Detail Penilaian Kriteri</h5>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <canvas id="radarChart"></canvas>
            </div>
            <div class="col-md-6">
              <canvas id="barChart"></canvas>
            </div>
          </div>
        </div>

        <div class="text-center mt-4 mb-3">
          <a href="hasil.php" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
          <a href="../export.php?type=pdf&id=<?= $id_alternatif; ?>" class="btn btn-danger me-2">
            <i class="fas fa-file-pdf"></i> Export PDF
          </a>
          <a href="../export.php?type=excel&id=<?= $id_alternatif; ?>" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
          </a>
        </div>

      </div>
    </div>

  </div>

  <script>
    function toggleSidebar() {
      document.querySelector('.sidebar').classList.toggle('collapsed');
      document.querySelector('.content').classList.toggle('collapsed');
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>