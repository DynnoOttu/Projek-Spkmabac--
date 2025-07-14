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
          <i class="bi bi-people-fill me-2"></i> DATA USER
        </span>
      </div>
      <div class="dropdown">
        <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" href="#" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
          <span class="me-2 text-uppercase fw-normal"><?php echo $_SESSION['username']; ?></span>
          <i class="bi bi-person-circle fs-4 text-secondary"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser">
          <!-- <li><a class="dropdown-item" href="./profile.php"><i class="bi bi-person me-2"></i>Profil</a></li> -->
          <li><a class="dropdown-item" href="./login.php"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
        </ul>
      </div>
    </div>

    <div class="container-fluid p-4">
      <div class="d-flex align-items-center gap-2 mb-3">
        <form class="d-flex" method="GET" action="">
          <div class="input-group">
            <input type="search" name="keyword" class="form-control shadow-sm" placeholder="Cari nama alternatif..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
            <button class="btn btn-outline-light bg-secondary" type="submit"><i class="bi bi-search"></i></button>
          </div>
        </form>
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
          <i class="bi bi-plus-circle me-1"></i>Tambah Data
        </button>
      </div>

      <div class="card shadow-sm rounded-4">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead class="table-light text-center">
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Username</th>
                  <th>Password</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = mysqli_query($koneksi, "SELECT * FROM admin");
                $no = 1;
                while ($row = mysqli_fetch_assoc($query)) {
                  echo "<tr class='text-center'>";
                  echo "<td>" . $no++ . "</td>";
                  echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['password']) . "</td>";
                  echo "<td>
                <button type='button' class='btn btn-warning btn-sm me-1' data-bs-toggle='modal' data-bs-target='#editModal{$row['id_admin']}'>
            <i class='bi bi-pencil-square'></i>
          </button>
                        <a href='hapus_alternatif.php?id={$row['id_admin']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\")'>
                          <i class='bi bi-trash'></i>
                        </a>
                      </td>";
                  echo "</tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Tambah User -->
    <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md">
        <form action="tambah_user.php" method="post" class="modal-content rounded-3">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalTambahUserLabel">
              <i class="bi bi-person-plus me-2"></i>Tambah Data User
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="nama" class="form-label">Nama Lengkap</label>
              <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="reset" class="btn btn-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
          </div>
        </form>
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