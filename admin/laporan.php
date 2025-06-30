<?php
require_once '../config.php';

// Cek login admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Fungsi untuk format tanggal Indonesia
function formatTanggal($tanggal) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

// Proses export ke Excel
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    // Setup filter untuk export
    $where_clause = "WHERE 1=1";
    $params = [];
    
    $tanggal_dari = isset($_GET['tanggal_dari']) ? $_GET['tanggal_dari'] : '';
    $tanggal_sampai = isset($_GET['tanggal_sampai']) ? $_GET['tanggal_sampai'] : '';
    $status_filter = isset($_GET['status']) ? $_GET['status'] : '';
    $fakultas_filter = isset($_GET['fakultas']) ? $_GET['fakultas'] : '';
    $semester_filter = isset($_GET['semester']) ? $_GET['semester'] : '';
    
    if ($tanggal_dari) {
        $where_clause .= " AND DATE(tanggal_daftar) >= ?";
        $params[] = $tanggal_dari;
    }
    
    if ($tanggal_sampai) {
        $where_clause .= " AND DATE(tanggal_daftar) <= ?";
        $params[] = $tanggal_sampai;
    }
    
    if ($status_filter) {
        $where_clause .= " AND status_pendaftaran = ?";
        $params[] = $status_filter;
    }
    
    if ($fakultas_filter) {
        $where_clause .= " AND fakultas = ?";
        $params[] = $fakultas_filter;
    }
    
    if ($semester_filter) {
        $where_clause .= " AND semester = ?";
        $params[] = $semester_filter;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM anggota $where_clause ORDER BY tanggal_daftar DESC");
    $stmt->execute($params);
    $data_export = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Set header untuk download Excel
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="Laporan_Pendaftaran_UKM_Taekwondo_' . date('Y-m-d') . '.xls"');
    
    echo '<table border="1">';
    echo '<tr>';
    echo '<th>No</th>';
    echo '<th>NIM</th>';
    echo '<th>Nama Lengkap</th>';
    echo '<th>Tempat Lahir</th>';
    echo '<th>Tanggal Lahir</th>';
    echo '<th>Jenis Kelamin</th>';
    echo '<th>Alamat</th>';
    echo '<th>No Telepon</th>';
    echo '<th>Email</th>';
    echo '<th>Program Studi</th>';
    echo '<th>Fakultas</th>';
    echo '<th>Semester</th>';
    echo '<th>Tingkat Sabuk</th>';
    echo '<th>Pengalaman Taekwondo</th>';
    echo '<th>Motivasi</th>';
    echo '<th>Status Pendaftaran</th>';
    echo '<th>Tanggal Daftar</th>';
    echo '</tr>';
    
    $no = 1;
    foreach ($data_export as $row) {
        echo '<tr>';
        echo '<td>' . $no++ . '</td>';
        echo '<td>' . htmlspecialchars($row['nim']) . '</td>';
        echo '<td>' . htmlspecialchars($row['nama_lengkap']) . '</td>';
        echo '<td>' . htmlspecialchars($row['tempat_lahir']) . '</td>';
        echo '<td>' . $row['tanggal_lahir'] . '</td>';
        echo '<td>' . $row['jenis_kelamin'] . '</td>';
        echo '<td>' . htmlspecialchars($row['alamat']) . '</td>';
        echo '<td>' . htmlspecialchars($row['no_telepon']) . '</td>';
        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
        echo '<td>' . htmlspecialchars($row['program_studi']) . '</td>';
        echo '<td>' . htmlspecialchars($row['fakultas']) . '</td>';
        echo '<td>' . $row['semester'] . '</td>';
        echo '<td>' . $row['tingkat_sabuk'] . '</td>';
        echo '<td>' . htmlspecialchars($row['pengalaman_taekwondo']) . '</td>';
        echo '<td>' . htmlspecialchars($row['motivasi']) . '</td>';
        echo '<td>' . $row['status_pendaftaran'] . '</td>';
        echo '<td>' . $row['tanggal_daftar'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    exit;
}

// Ambil data untuk filter
$fakultas_list = $pdo->query("SELECT DISTINCT fakultas FROM anggota WHERE fakultas IS NOT NULL ORDER BY fakultas")->fetchAll(PDO::FETCH_COLUMN);
$semester_list = $pdo->query("SELECT DISTINCT semester FROM anggota WHERE semester IS NOT NULL ORDER BY semester")->fetchAll(PDO::FETCH_COLUMN);

// Setup filter
$tanggal_dari = isset($_GET['tanggal_dari']) ? $_GET['tanggal_dari'] : '';
$tanggal_sampai = isset($_GET['tanggal_sampai']) ? $_GET['tanggal_sampai'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$fakultas_filter = isset($_GET['fakultas']) ? $_GET['fakultas'] : '';
$semester_filter = isset($_GET['semester']) ? $_GET['semester'] : '';

$where_clause = "WHERE 1=1";
$params = [];

if ($tanggal_dari) {
    $where_clause .= " AND DATE(tanggal_daftar) >= ?";
    $params[] = $tanggal_dari;
}

if ($tanggal_sampai) {
    $where_clause .= " AND DATE(tanggal_daftar) <= ?";
    $params[] = $tanggal_sampai;
}

if ($status_filter) {
    $where_clause .= " AND status_pendaftaran = ?";
    $params[] = $status_filter;
}

if ($fakultas_filter) {
    $where_clause .= " AND fakultas = ?";
    $params[] = $fakultas_filter;
}

if ($semester_filter) {
    $where_clause .= " AND semester = ?";
    $params[] = $semester_filter;
}

// Ambil data statistik berdasarkan filter
$stats = [];
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status_pendaftaran = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status_pendaftaran = 'Diterima' THEN 1 ELSE 0 END) as diterima,
    SUM(CASE WHEN status_pendaftaran = 'Ditolak' THEN 1 ELSE 0 END) as ditolak
FROM anggota $where_clause";

$stmt = $pdo->prepare($stats_query);
$stmt->execute($params);
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Statistik per fakultas
$fakultas_stats_query = "SELECT fakultas, COUNT(*) as total FROM anggota $where_clause AND fakultas IS NOT NULL GROUP BY fakultas ORDER BY total DESC";
$stmt = $pdo->prepare($fakultas_stats_query);
$stmt->execute($params);
$fakultas_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statistik per bulan
$monthly_stats_query = "SELECT 
    DATE_FORMAT(tanggal_daftar, '%Y-%m') as bulan,
    COUNT(*) as total,
    SUM(CASE WHEN status_pendaftaran = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status_pendaftaran = 'Diterima' THEN 1 ELSE 0 END) as diterima,
    SUM(CASE WHEN status_pendaftaran = 'Ditolak' THEN 1 ELSE 0 END) as ditolak
FROM anggota $where_clause 
GROUP BY DATE_FORMAT(tanggal_daftar, '%Y-%m') 
ORDER BY bulan DESC 
LIMIT 12";

$stmt = $pdo->prepare($monthly_stats_query);
$stmt->execute($params);
$monthly_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil data untuk tabel
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM anggota $where_clause");
$count_stmt->execute($params);
$total_data = $count_stmt->fetchColumn();
$total_pages = ceil($total_data / $limit);

$stmt = $pdo->prepare("SELECT * FROM anggota $where_clause ORDER BY tanggal_daftar DESC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$anggota_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendaftaran - UKM Taekwondo UNINDRA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg,rgb(96, 9, 9) 0%,rgb(10, 38, 87) 100%);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%,rgb(56, 20, 92) 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .stat-card.pending {
            background: linear-gradient(135deg,rgb(239, 159, 69) 0%, #f5576c 100%);
        }
        .stat-card.approved {
            background: linear-gradient(135deg,rgb(14, 78, 134) 0%,rgb(110, 183, 235) 100%);
        }
        .stat-card.rejected {
            background: linear-gradient(135deg,rgb(225, 35, 35) 0%,rgb(218, 110, 110) 100%);
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin: 20px 0;
        }
        .badge-pending {
            background-color: #ffc107;
        }
        .badge-diterima {
            background-color: #198754;
        }
        .badge-ditolak {
            background-color: #dc3545;
        }
        .print-hide {
            display: block;
        }
        @media print {
            .sidebar, .print-hide {
                display: none !important;
            }
            .col-md-9 {
                width: 100% !important;
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse print-hide">
                <div class="position-sticky pt-3">
                    <div class="text-center text-white mb-4">
                        <h5>Admin Panel</h5>
                        <img src="image.png" style="height:40px; width:auto;"><br><small>UKM TAEKWONDO UNINDRA</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="laporan.php">
                                <i class="fas fa-file-alt me-2"></i>Laporan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../index.php" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>Lihat Website
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a class="nav-link text-white" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                    
                    <div class="mt-4 text-white-50 text-center">
                        <small>
                            Login sebagai:<br>
                            <strong><?= $_SESSION['admin_name'] ?></strong>
                        </small>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-file-alt me-2"></i>Laporan Pendaftaran</h1>
                    <div class="btn-toolbar mb-2 mb-md-0 print-hide">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-success" onclick="exportToExcel()">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                            <button type="button" class="btn btn-sm btn-info" onclick="window.print()">
                                <i class="fas fa-print"></i> Cetak
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="card mb-4 print-hide">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-filter me-2"></i>Filter Laporan
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Dari</label>
                                <input type="date" name="tanggal_dari" class="form-control" value="<?= $tanggal_dari ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Sampai</label>
                                <input type="date" name="tanggal_sampai" class="form-control" value="<?= $tanggal_sampai ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua</option>
                                    <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Diterima" <?= $status_filter == 'Diterima' ? 'selected' : '' ?>>Diterima</option>
                                    <option value="Ditolak" <?= $status_filter == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Fakultas</label>
                                <select name="fakultas" class="form-select">
                                    <option value="">Semua</option>
                                    <?php foreach ($fakultas_list as $fakultas): ?>
                                        <option value="<?= $fakultas ?>" <?= $fakultas_filter == $fakultas ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($fakultas) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Semester</label>
                                <select name="semester" class="form-select">
                                    <option value="">Semua</option>
                                    <?php foreach ($semester_list as $semester): ?>
                                        <option value="<?= $semester ?>" <?= $semester_filter == $semester ? 'selected' : '' ?>>
                                            Semester <?= $semester ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Filter
                                </button>
                                <a href="laporan.php" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?= $stats['total'] ?></h4>
                                    <p class="mb-0">Total Pendaftar</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card pending">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?= $stats['pending'] ?></h4>
                                    <p class="mb-0">Menunggu</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card approved">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?= $stats['diterima'] ?></h4>
                                    <p class="mb-0">Diterima</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card rejected">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?= $stats['ditolak'] ?></h4>
                                    <p class="mb-0">Ditolak</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-times fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Statistik per Fakultas</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="fakultasChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Trend Pendaftaran (12 Bulan Terakhir)</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="monthlyChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Data Pendaftar (<?= $total_data ?> data)
                        </h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Fakultas</th>
                                    <th>Semester</th>
                                    <th>Status</th>
                                    <th>Tanggal Daftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($anggota_list)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Tidak ada data</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = $offset + 1; ?>
                                    <?php foreach ($anggota_list as $anggota): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($anggota['nim']) ?></td>
                                            <td><?= htmlspecialchars($anggota['nama_lengkap']) ?></td>
                                            <td><?= htmlspecialchars($anggota['fakultas']) ?></td>
                                            <td><?= $anggota['semester'] ?></td>
                                            <td>
                                                <span class="badge badge-<?= strtolower($anggota['status_pendaftaran']) ?>">
                                                    <?= $anggota['status_pendaftaran'] ?>
                                                </span>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($anggota['tanggal_daftar'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="card-footer print-hide">
                            <nav>
                                <ul class="pagination justify-content-center mb-0">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $page - 1 ?>&tanggal_dari=<?= urlencode($tanggal_dari) ?>&tanggal_sampai=<?= urlencode($tanggal_sampai) ?>&status=<?= urlencode($status_filter) ?>&fakultas=<?= urlencode($fakultas_filter) ?>&semester=<?= urlencode($semester_filter) ?>">Previous</a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>&tanggal_dari=<?= urlencode($tanggal_dari) ?>&tanggal_sampai=<?= urlencode($tanggal_sampai) ?>&status=<?= urlencode($status_filter) ?>&fakultas=<?= urlencode($fakultas_filter) ?>&semester=<?= urlencode($semester_filter) ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $page + 1 ?>&tanggal_dari=<?= urlencode($tanggal_dari) ?>&tanggal_sampai=<?= urlencode($tanggal_sampai) ?>&status=<?= urlencode($status_filter) ?>&fakultas=<?= urlencode($fakultas_filter) ?>&semester=<?= urlencode($semester_filter) ?>">Next</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Chart untuk statistik fakultas
        const fakultasCtx = document.getElementById('fakultasChart').getContext('2d');
        const fakultasChart = new Chart(fakultasCtx, {
            type: 'doughnut',
            data: {
                labels: [<?php foreach ($fakultas_stats as $fs) echo "'" . addslashes($fs['fakultas']) . "',"; ?>],
                datasets: [{
                    data: [<?php foreach ($fakultas_stats as $fs) echo $fs['total'] . ','; ?>],
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#FF9F40', '#4BC0C0',
                        '#9966FF', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Chart untuk trend bulanan
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: [<?php foreach (array_reverse($monthly_stats) as $ms) echo "'" . date('M Y', strtotime($ms['bulan'] . '-01')) . "',"; ?>],
                datasets: [{
                    label: 'Total Pendaftar',
                    data: [<?php foreach (array_reverse($monthly_stats) as $ms) echo $ms['total'] . ','; ?>],
                    borderColor: '#36A2EB',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Diterima',
                    data: [<?php foreach (array_reverse($monthly_stats) as $ms) echo $ms['diterima'] . ','; ?>],
                    borderColor: '#4BC0C0',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        function exportToExcel() {
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'excel');
            window.location.href = '?' + params.toString();
        }
    </script>
</body>
</html>