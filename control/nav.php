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
       <i class="bi bi-box-fill me-2"></i> DATA KRITERIA
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