<?php
require_once('includes/init.php');
$user_role = get_role();
if (!in_array($user_role, ['admin', 'kasek', 'guru'])) {
  header('Location: login.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

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

    .dropdown-toggle::after {
      display: none !important;
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

  <div id="content" class="content">
    <div class="top-navbar">
      <button class="menu-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
      <div class="text-center flex-grow-1">
        <span class="fs-5 fw-bold text-uppercase" style="letter-spacing: 1px; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
          <i class="bi bi-calculator-fill me-2"></i> DATA PERHITUNGAN
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

    <div class="form-wrapper mx-auto">
      <form action="prosesmabac.php" method="POST">
        <div class="card mb-4">
          <div class="card-header bg-secondary text-white">
            <h5>Data Mahasiswa</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="kode" class="form-label">Kode Alternatif</label>
                  <select class="form-control" id="kode" name="kode" required>
                    <option value="">-- Pilih Kode Alternatif --</option>
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM alternatif");
                    while ($row = mysqli_fetch_assoc($query)) {
                      echo "<option value='" . $row['kode'] . "' data-nama='" . $row['nama'] . "'>" . $row['kode'] . "</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="nama" class="form-label">Nama Lengkap</label>
                  <input type="text" class="form-control" id="nama" name="nama" readonly>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="card mb-4">
          <div class="card-header bg-secondary text-white">
            <h5>Kriteria Beasiswa</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label for="ipk" class="form-label">IPK (Skala 0-4)</label>
              <select class="form-select" id="ipk" name="ipk" required>
                <option value="">Pilih</option>
                <option value="1">(5) ≥ 3.75</option>
                <option value="2">(4) 3.50 - 3.74</option>
                <option value="3">(3) 3.25 - 3.49</option>
                <option value="4">(2) 3.00 - 3.24</option>
                <option value="5">(1) < 3.00</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="dokumen" class="form-label">Kelengkapan Dokumen</label>
              <select class="form-select" id="dokumen" name="dokumen" required>
                <option value="">Pilih</option>
                <option value="1">(5) Lengkap 12 Dokumen</option>
                <option value="2">(4) 9 - 11 Dokumen</option>
                <option value="3">(3) 6 - 8 Dokumen</option>
                <option value="4">(2) 3 - 5 Dokumen</option>
                <option value="5">(1) < 3 Dokumen</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="kk" class="form-label">Keaktifan Kuliah</label>
              <select class="form-select" id="kk" name="kk" required>
                <option value="">Pilih</option>
                <option value="1">(5) Ada Surat Aktif Kuliah + KRS</option>
                <option value="2">(4) Ada Surat Aktif Kuliah Saja</option>
                <option value="3">(3) Surat Tidak Resmi (tanpa TTD/Stempel)</option>
                <option value="4">(2) Surat Aktif Kuliah Lama (Bukan yang Terbaru)</option>
                <option value="5">(1) Tidak Melampirkan Surat Aktif/keterangan Kuliah </option>
              </select>
            </div>

            <div class="mb-3">
              <label for="sbl" class="form-label">Status Beasiswa Lain</label>
              <select class="form-select" id="sbl" name="sbl" required>
                <option value="">Pilih</option>
                <option value="1">(5) Disertai surat resmi pernyataan tidak menerima beasiswa lain</option>
                <option value="2">(4) Tidak menerima beasiswa lain, tanpa surat</option>
                <option value="3">(3) Pernah menerima beasiswa, sudah habis</option>
                <option value="4">(2) Menerima beasiswa lain dengan nilai kecil (< 500rb/bulan)</option>
                <option value="5">(1) Masih aktif menerima beasiswa dari sumber lain</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="penghasilan" class="form-label">Penghasilan Orang Tua (per bulan)</label>
              <select class="form-select" id="penghasilan" name="penghasilan" required>
                <option value="">Pilih</option>
                <option value="1">(5) < Rp 1.000.000</option>
                <option value="2">(4) Rp 1.000.000 - Rp 1.999.000</option>
                <option value="3">(3) Rp 2.000.000 - Rp 2.999.000</option>
                <option value="4">(2) Rp 3.000.000 - Rp 3.999.000</option>
                <option value="5">(1) ≥ Rp 4.000.000</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="prestasi" class="form-label">Jumlah Prestasi</label>
              <select class="form-select" id="prestasi" name="prestasi" required>
                <option value="">Pilih Jumlah Prestasi</option>
                <option value="1">(1) 0</option>
                <option value="2">(2) 1 - 2</option>
                <option value="3">(3) 3 - 5</option>
                <option value="4">(4) 6 - 10</option>
                <option value="5">(5) > 10</option>
              </select>
            </div>

          </div>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-warning btn-lg">Hitung Kelayakan Beasiswa</button>
        </div>
      </form>
    </div>

  </div>

  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('collapsed');
      document.getElementById('content').classList.toggle('collapsed');
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById("kode").addEventListener("change", function() {
      var selectedOption = this.options[this.selectedIndex];
      var nama = selectedOption.getAttribute("data-nama");
      document.getElementById("nama").value = nama || "";
    });
  </script>

</body>

</html>