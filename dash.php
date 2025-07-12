<?php

require_once('includes/init.php');

$user_role = get_role();
if ($user_role == 'admin' || $user_role == 'kasek' || $user_role == 'guru') {

    $page = "Dashboard";

    require_once('template/header.php');

?>


    <div class="mb-4">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-home"></i> Dashboard</h1>
        </div>

        <?php
        if ($user_role == 'admin') {
        ?>

            <!-- Content Row -->
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                SELAMAT DATANG <span class="text-uppercase"><b><?php echo $_SESSION['username']; ?>!</b></span> DI SISTEM PENDUKUNG KEPUTUSAN PENILAIAN KINERJA GURU SEKOLAH DASAR KATHOLIK NUAWAIN II.
            </div>
            <div class="row">
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
  <div class="d-flex justify-content-between align-items-center h5 mb-0 fw-bold text-gray-800">
    <a href="list-kriteria.php" class="text-secondary text-decoration-none">Data Alternatif</a>
    <i class="bi bi-people-fill fs-4 text-muted"></i>
  </div>
</div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-secondary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
  <div class="d-flex justify-content-between align-items-center h5 mb-0 fw-bold text-gray-800">
    <a href="list-kriteria.php" class="text-secondary text-decoration-none">Data Kriteria</a>
    <i class="bi bi-box fs-4 text-muted"></i>
  </div>
</div>
                                <div class="col-auto">
                                    <i class="fas fa-cubes fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
  <div class="d-flex justify-content-between align-items-center h5 mb-0 fw-bold text-gray-800">
    <a href="list-kriteria.php" class="text-secondary text-decoration-none">Data Perhitungan</a>
    <i class="bi bi-calculator-fill fs-4 text-muted"></i>
  </div>
</div>
                                <div class="col-auto">
                                    <i class="fas fa-calculator fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
  <div class="d-flex justify-content-between align-items-center h5 mb-0 fw-bold text-gray-800">
    <a href="list-kriteria.php" class="text-secondary text-decoration-none">Data Hasil Akhir</a>
    <i class="bi bi-bar-chart-fill fs-4 text-muted"></i>
  </div>
</div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
  <div class="d-flex justify-content-between align-items-center h5 mb-0 fw-bold text-gray-800">
    <a href="list-kriteria.php" class="text-secondary text-decoration-none">Data User</a>
    <i class="bi bi-person-lines-fill fs-4 text-muted"></i>
  </div>
</div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
  <div class="d-flex justify-content-between align-items-center h5 mb-0 fw-bold text-gray-800">
    <a href="list-kriteria.php" class="text-secondary text-decoration-none">Data Profil</a>
    <i class="bi bi-person-circle fs-4 text-muted"></i>
  </div>
</div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        } elseif ($user_role == 'kasek') {
        ?>
            <!-- Content Row -->
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                SELAMAT DATANG <span class="text-uppercase"><b><?php echo $_SESSION['username']; ?>!</b></span> DI SISTEM PENDUKUNG KEPUTUSAN PENILAIAN KINERJA GURU SDK NUAWAIN II.
            </div>
            <div class="row">
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="list-penilaian.php" class="text-secondary text-decoration-none">Data Penilaian</a></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="hasil.php" class="text-secondary text-decoration-none">Data Hasil Akhir</a></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="list-profile.php" class="text-secondary text-decoration-none">Data Profile</a></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        <?php
        } elseif ($user_role == 'guru') {
        ?>
            <!-- Content Row -->
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                SELAMAT DATANG <span class="text-uppercase"><b><?php echo $_SESSION['username']; ?>!</b></span> DI SISTEM PENDUKUNG KEPUTUSAN PENILAIAN KINERJA GURU SDK NUAWAIN II.
            </div>
            <div class="row">

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="hasil.php" class="text-secondary text-decoration-none">Data Hasil Akhir</a></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-area fa-2x text-info-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="list-profile.php" class="text-secondary text-decoration-none">Data Profile</a></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php
        }
        ?>
    </div>
    </div>
<?php
    require_once('template/footer.php');
} else {
    header('Location: login.php');
}
?>
</body>
</div>