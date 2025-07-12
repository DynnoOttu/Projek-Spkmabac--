<?php
require_once('includes/init.php');
$user_role = get_role();
if (!in_array($user_role, ['admin', 'kasek', 'guru'])) {
  header('Location: login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Dashboard SPK</title>
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
      background-color: rgb(39, 41, 33);
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

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: rgb(5, 108, 212);
      color: #fff;
    }

    .sidebar .nav-link i {
      font-size: 1.1rem;
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

    .form-wrapper {
      max-width: 1000px;
      /* Atur sesuai kebutuhan: 600px, 720px, 768px */
      width: 100%;
      padding: 50px;
    }
  </style>
</head>

<body>

  <?php
  $page = basename($_SERVER['PHP_SELF']);
  ?>

  <!-- Sidebar -->
  <nav id="sidebar" class="sidebar bg-dark text-white position-fixed vh-100">
    <div class="sidebar-header text-center d-flex align-items-center justify-content-center" style="height: 58px; border-bottom: 1px solid #6c757d;">
      <h5 class="mb-0">SPK MABAC</h5>
    </div>

    <ul class="nav flex-column mt-3 px-2">
      <li class="nav-item">
        <a href="dashboard.php" class="nav-link text-white <?= ($page == 'dashboard.php') ? 'active' : '' ?>">
          <i class="bi bi-house-door me-2"></i> Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a href="alternatif.php" class="nav-link text-white <?= ($page == 'alternatif.php') ? 'active' : '' ?>">
          <i class="bi bi-people-fill me-2"></i> Data Alternatif
        </a>
      </li>
      <li class="nav-item">
        <a href="kriteria.php" class="nav-link text-white <?= ($page == 'kriteria.php') ? 'active' : '' ?>">
          <i class="bi bi-box-fill me-2"></i> Data Kriteria
        </a>
      </li>
      <li class="nav-item">
        <a href="perhitungan.php" class="nav-link text-white <?= ($page == 'perhitungan.php') ? 'active' : '' ?>">
          <i class="bi bi-calculator-fill me-2"></i> Data Perhitungan
        </a>
      </li>
      <li class="nav-item">
        <a href="hasil.php" class="nav-link text-white <?= ($page == 'hasil.php') ? 'active' : '' ?>">
          <i class="bi bi-bar-chart-line-fill me-2"></i> Data Hasil Akhir
        </a>
        <hr class="my-2 border-secondary">
      </li>
      <li class="nav-item">
        <a href="user.php" class="nav-link text-white <?= ($page == 'user.php') ? 'active' : '' ?>">
          <i class="bi bi-person-lines-fill me-2"></i> Data User
        </a>
      </li>
    </ul>
  </nav>

  <!-- Content -->
  <div id="content" class="content">
    <div class="top-navbar">
      <button class="menu-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
      <div class="text-center flex-grow-1">
        <span class="fs-5 fw-bold text-uppercase" style="letter-spacing: 1px;">
          <i class="bi bi-people-fill me-2"></i> DATA HASIL AKHIR
        </span>
      </div>
      <div class="dropdown">
        <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" href="#" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
          <span class="me-2 text-uppercase fw-normal"><?php echo $_SESSION['username']; ?></span>
          <i class="bi bi-person-circle fs-4 text-secondary"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser">
          <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Profil</a></li>
          <li><a class="dropdown-item" href="../spkmabac/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
        </ul>
      </div>
    </div>

    <div class="container py-4">
      <div class="card shadow-lg">
        <div class="card-header bg-secondary text-white text-center">
          <h5 class="mb-0">Daftar Peringkat Penerima Beasiswa</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped align-middle text-center">
              <thead class="table-light">
                <tr>
                  <th>Peringkat</th>
                  <th>Kode Alternatif</th>
                  <th>Nama</th>
                  <th>Universitas</th>
                  <th>Skor Akhir</th>
                  <th>Rekomendasi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = "SELECT 
                                    hasil.peringkat,
                                    hasil.skor_akhir,
                                    hasil.rekomendasi,
                                    alternatif.id_alternatif,
                                    alternatif.kode,
                                    alternatif.nama,
                                    alternatif.univ
                                FROM hasil
                                JOIN alternatif ON hasil.id_alternatif = alternatif.id_alternatif
                                ORDER BY hasil.skor_akhir";

                $result = $koneksi->query($query);

                if (!$result) {
                  echo "<tr><td colspan='11'>Query Error: " . $koneksi->error . "</td></tr>";
                } elseif ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                                        <td>" . number_format($row['peringkat'], 0) . "</td>
                                        <td>{$row['kode']}</td>
                                        <td>{$row['nama']}</td>
                                        <td>{$row['univ']}</td>
                                        <td>" . number_format($row['skor_akhir'], 6) . "</td>
                                        <td>
                                            <span class='badge " . ($row['rekomendasi'] == 'Direkomendasikan' ? 'bg-success' : 'bg-danger') . "'>
                                                {$row['rekomendasi']}
                                            </span>
                                        </td>
                                        <td>
                                            <a href='det.php?id={$row['id_alternatif']}' class='btn btn-sm btn-info'>
                                                Detail
                                            </a>
                                        </td>
                                    </tr>";
                  }
                } else {
                  echo "<tr><td colspan='11' class='text-center text-muted'>Belum ada data hasil perhitungan</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card-footer text-center">
          <div class="d-flex flex-wrap justify-content-center gap-2">
            <!-- <a href="../pages/laporan.php" class="btn btn-warning">
              <i class="bi bi-journal-text me-1"></i> Lihat Laporan Lengkap
            </a> -->
            <a href="report/report_hasil.php" class="btn btn-danger">
              <i class="bi bi-file-earmark-pdf me-1"></i> Export ke PDF
            </a>
            <a href="report/export_hasil_excel.php" class="btn btn-success">
              <i class="bi bi-file-earmark-excel me-1"></i> Export ke Excel
            </a>
          </div>
        </div>
      </div>
    </div>


  </div>

  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('collapsed');
      document.getElementById('content').classList.toggle('collapsed');
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>