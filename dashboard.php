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



<div id="content" class="content">
  <div class="top-navbar">
    <button class="menu-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>

    <div class="text-center flex-grow-1">
    <span class="fs-5 fw-bold text-uppercase" style="letter-spacing: 1px; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
       <i class="bi bi-house-door me-1"></i>DASHBOARD
    </span>
</div>

    
    <div class="dropdown">
  <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" href="#" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
    <span class="me-2 text-uppercase fw-normal"><?php echo $_SESSION['username']; ?></span>
    <i class="bi bi-person-circle fs-4 text-secondary"></i>
  </a>
  <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser">
    <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Profil</a></li>
    <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
  </ul>
</div>

  </div>

  <div class="container py-4">
    <div class="alert alert-welcome text-center fw-bold">
      SELAMAT DATANG DI SISTEM PENDUKUNG KEPUTUSAN PENENTUAN PENERIMA BEASISWA <strong><?php echo strtoupper($_SESSION['username']); ?>!</strong>
    </div>

    <div class="row g-4 mt-3">
      <div class="col-md-4">
        <a href="alternatif.php" class="text-decoration-none text-dark">
          <div class="card-menu border-start border-4 border-danger">
            <span>Data Alternatif</span>
            <i class="bi bi-people-fill fs-2 text-muted"></i>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="kriteria.php" class="text-decoration-none text-dark">
          <div class="card-menu border-start border-4 border-secondary">
            <span>Data Kriteria</span>
            <i class="bi bi-box-fill fs-2 text-muted"></i>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="perhitungan.php" class="text-decoration-none text-dark">
          <div class="card-menu border-start border-4 border-primary">
            <span>Data Perhitungan</span>
            <i class="bi bi-calculator-fill fs-2 text-muted"></i>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="hasil.php" class="text-decoration-none text-dark">
          <div class="card-menu border-start border-4 border-warning">
            <span>Data Hasil Akhir</span>
            <i class="bi bi-bar-chart-fill fs-2 text-muted"></i>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="user.php" class="text-decoration-none text-dark">
          <div class="card-menu border-start border-4 border-success">
            <span>Data User</span>
            <i class="bi bi-person-lines-fill fs-2 text-muted"></i>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="profile.php" class="text-decoration-none text-dark">
          <div class="card-menu border-start border-4 border-danger">
            <span> Profil</span>
            <i class="bi bi-person-circle fs-2 text-muted"></i>
          </div>
        </a>
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
