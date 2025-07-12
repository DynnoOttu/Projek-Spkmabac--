<?php
// Mulai session
session_start();

// Include file konfigurasi dan fungsi

require_once 'includes/konek.php';

// Cek apakah parameter id ada
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: hasil.php");
    exit();
}

$id_mahasiswa = (int)$_GET['id'];

// Ambil data mahasiswa beserta kriteria dan hasil
$data = getMahasiswaWithCriteria($koneksi, $id_alternatif);

// Jika data tidak ditemukan, redirect ke halaman hasil
if (empty($data['alternatif'])) {
    header("Location: hasil.php");
    exit();
}

// Set halaman aktif
$current_page = 'hasil';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mahasiswa - DSS Beasiswa MABAC</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css">
</head>

<body>
    <!-- Include Header -->
    <?php include '../includes/header.php'; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Detail Mahasiswa</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">NIM</th>
                            <td><?= htmlspecialchars($data['mahasiswa']['nim']); ?></td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td><?= htmlspecialchars($data['mahasiswa']['nama']); ?></td>
                        </tr>
                        <tr>
                            <th>Fakultas</th>
                            <td><?= htmlspecialchars($data['mahasiswa']['fakultas']); ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Pendaftaran</th>
                            <td><?= date('d F Y H:i', strtotime($data['mahasiswa']['created_at'])); ?></td>
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

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Detail Penilaian Kriteria</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
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
                                <td><?= htmlspecialchars($kriteria['nama_kriteria']); ?></td>
                                <td><?= $kriteria['jenis'] == 'benefit' ? 'Benefit' : 'Cost'; ?></td>
                                <td><?= getBobotKriteria($conn, $kriteria['id_kriteria']); ?></td>
                                <td><?= $kriteria['nilai']; ?></td>
                                <td><?= convertToLabel($kriteria['id_kriteria'], $kriteria['nilai']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Visualisasi Nilai Kriteria</h4>
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
    </div>

    <div class="text-center mt-4">
        <a href="../pages/hasil.php" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="../export.php?type=pdf&id=<?= $id_mahasiswa; ?>" class="btn btn-danger me-2">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
        <a href="../export.php?type=excel&id=<?= $id_mahasiswa; ?>" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>
    </div>
    </div>
    </main>

    <!-- Include Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom JS -->
    <script src="../assets/js/script.js"></script>

    <!-- Initialize Charts -->
    <script>
        // Radar Chart
        const radarCtx = document.getElementById('radarChart').getContext('2d');
        new Chart(radarCtx, {
            type: 'radar',
            data: {
                labels: <?= json_encode(array_column($data['kriteria'], 'nama_kriteria')); ?>,
                datasets: [{
                    label: 'Nilai Kriteria',
                    data: <?= json_encode(array_column($data['kriteria'], 'nilai')); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        angleLines: {
                            display: true
                        },
                        suggestedMin: 0,
                        suggestedMax: 5
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Radar Chart Nilai Kriteria'
                    }
                }
            }
        });

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($data['kriteria'], 'nama_kriteria')); ?>,
                datasets: [{
                    label: 'Nilai Kriteria',
                    data: <?= json_encode(array_column($data['kriteria'], 'nilai')); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Bar Chart Nilai Kriteria'
                    }
                }
            }
        });
    </script>
</body>

</html>