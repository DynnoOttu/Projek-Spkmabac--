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

    /*.warnaaa pada sidebar pilihan */
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
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

  <?php include './control/nav.php' ?>

  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <!-- Tombol Tambah Data -->
      <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalTambahKriteria">
        <i class="bi bi-plus-lg"></i> Tambah Data
      </button>

    </div>

    <div class="card shadow-sm">
      <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle mb-0">
          <thead class="table-light text-center">
            <tr>
              <th>No</th>
              <th>Kode Kriteria</th>
              <th>Nama Kriteria</th>
              <th>Bobot</th>
              <th>Jenis</th>
              <th>Aksi</th>
            </tr>
          </thead>

          <tbody>
            <?php
            $query = mysqli_query($koneksi, "SELECT * FROM kriteria");
            $no = 1;
            while ($row = mysqli_fetch_assoc($query)) {
              echo "<tr class='text-center'>";
              echo "<td>" . $no++ . "</td>";
              echo "<td>" . htmlspecialchars($row['kode']) . "</td>";
              echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
              echo "<td>" . htmlspecialchars($row['bobot']) . "</td>";
              echo "<td>" . htmlspecialchars($row['jenis']) . "</td>";
              echo "<td>
          <button type='button' class='btn btn-warning btn-sm me-1' data-bs-toggle='modal' data-bs-target='#editModal{$row['id_kriteria']}'>
            <i class='bi bi-pencil-square'></i>
          </button>
          <a href='hapus_kriteria.php?id={$row['id_kriteria']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\")'>
            <i class='bi bi-trash'></i>
          </a>
        </td>";
              echo "</tr>";

              // MODAL diletakkan di sini, setelah </tr>
              echo "
  <div class='modal fade' id='editModal{$row['id_kriteria']}' tabindex='-1' aria-labelledby='editModalLabel{$row['id_kriteria']}' aria-hidden='true'>
    <div class='modal-dialog'>
      <div class='modal-content'>
        <div class='modal-header bg-primary text-white'>
          <h5 class='modal-title' id='editModalLabel{$row['id_kriteria']}'>Edit Kriteria</h5>
          <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <form action='up/editkriteria.php' method='POST'>
          <div class='modal-body'>
            <input type='hidden' name='id_kriteria' value='{$row['id_kriteria']}'>
            <div class='mb-3'>
              <label for='nama{$row['id_kriteria']}' class='form-label'>Kode Kriteria</label>
              <input type='text' class='form-control' id='nama{$row['id_kriteria']}' name='kode' value='{$row['kode']}' required>
            </div>
            <div class='mb-3'>
              <label for='nama{$row['id_kriteria']}' class='form-label'>Nama</label>
              <input type='text' class='form-control' id='nama{$row['id_kriteria']}' name='nama' value='{$row['nama']}' required>
            </div>
            <div class='mb-3'>
              <label for='bobot{$row['id_kriteria']}' class='form-label'>Bobot</label>
              <input type='number' class='form-control' id='bobot{$row['id_kriteria']}' name='bobot' value='{$row['bobot']}' step='0.01' required>
            </div>
            <div class='mb-3'>
              <label for='jenis{$row['id_kriteria']}' class='form-label'>Jenis</label>
              <select class='form-select' id='jenis{$row['id_kriteria']}' name='jenis' required>
  <option value='Benefit' " . ($row['jenis'] == 'Benefit' ? 'selected' : '') . ">Benefit</option>
  <option value='Cost' " . ($row['jenis'] == 'Cost' ? 'selected' : '') . ">Cost</option>
</select>
            </div>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
            <button type='submit' class='btn btn-primary'><i class='bi bi-save2'></i> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  ";
            }
            ?>
          </tbody>

        </table>
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

<!-- Modal Tambah Data Kriteria -->
<div class="modal fade" id="modalTambahKriteria" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="tambah/tambahkriteria.php" method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalTambahLabel"><i class="bi bi-plus-circle me-2"></i>Tambah Data Kriteria</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="kode" class="form-label">Kode Kriteria</label>
              <input type="text" class="form-control" id="kode" name="kode" required>
            </div>
            <div class="col-md-6">
              <label for="nama" class="form-label">Nama Kriteria</label>
              <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="bobot" class="form-label">Bobot Kriteria</label>
              <input type="number" step="0.01" class="form-control" id="bobot" name="bobot" required>
            </div>
            <div class="col-md-6">
              <label for="jenis" class="form-label">Jenis Kriteria</label>
              <select class="form-select" id="jenis" name="jenis" required>
                <option value="">-- Pilih Jenis Kriteria --</option>
                <option value="Benefit">Benefit</option>
                <option value="Cost">Cost</option>
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="reset" class="btn btn-secondary"><i class="bi bi-arrow-repeat"></i> Reset</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-save2"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Data Kriteria -->
<div class="modal fade" id="modaleditKriteria" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="proses_tambah_kriteria.php" method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalTambahLabel"><i class="bi bi-plus-circle me-2"></i>Tambah Data Kriteria</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="kode" class="form-label">Kode Kriteria</label>
              <input type="text" class="form-control" id="kode" name="kode" required>
            </div>
            <div class="col-md-6">
              <label for="nama" class="form-label">Nama Kriteria</label>
              <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="bobot" class="form-label">Bobot Kriteria</label>
              <input type="number" step="0.01" class="form-control" id="bobot" name="bobot" required>
            </div>
            <div class="col-md-6">
              <label for="jenis" class="form-label">Jenis Kriteria</label>
              <select class="form-select" id="jenis" name="jenis" required>
                <option value="">-- Pilih Jenis Kriteria --</option>
                <option value="Benefit">Benefit</option>
                <option value="Cost">Cost</option>
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="reset" class="btn btn-secondary"><i class="bi bi-arrow-repeat"></i> Reset</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-save2"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

</html>