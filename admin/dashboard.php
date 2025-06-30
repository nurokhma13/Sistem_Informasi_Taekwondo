<?php
require_once '../config.php';

// Cek login admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Proses update status
if ($_POST && isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE anggota SET status_pendaftaran = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        
        $_SESSION['success_message'] = "Status pendaftaran berhasil diupdate!";
        header('Location: dashboard.php');
        exit;
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Proses hapus data
if (isset($_GET['delete']) && $_GET['delete']) {
    $id = $_GET['delete'];
    
    try {
        // Hapus foto jika ada
        $stmt = $pdo->prepare("SELECT foto FROM anggota WHERE id = ?");
        $stmt->execute([$id]);
        $anggota = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($anggota && $anggota['foto'] && file_exists('../uploads/' . $anggota['foto'])) {
            unlink('../uploads/' . $anggota['foto']);
        }
        
        // Hapus data
        $stmt = $pdo->prepare("DELETE FROM anggota WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['success_message'] = "Data anggota berhasil dihapus!";
        header('Location: dashboard.php');
        exit;
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Ambil data statistik
$stats = [];
$stats['total'] = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
$stats['pending'] = $pdo->query("SELECT COUNT(*) FROM anggota WHERE status_pendaftaran = 'Pending'")->fetchColumn();
$stats['diterima'] = $pdo->query("SELECT COUNT(*) FROM anggota WHERE status_pendaftaran = 'Diterima'")->fetchColumn();
$stats['ditolak'] = $pdo->query("SELECT COUNT(*) FROM anggota WHERE status_pendaftaran = 'Ditolak'")->fetchColumn();

// Ambil data anggota dengan pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$where_clause = "WHERE 1=1";
$params = [];

if ($search) {
    $where_clause .= " AND (nama_lengkap LIKE ? OR nim LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status_filter) {
    $where_clause .= " AND status_pendaftaran = ?";
    $params[] = $status_filter;
}

// Total data untuk pagination
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM anggota $where_clause");
$count_stmt->execute($params);
$total_data = $count_stmt->fetchColumn();
$total_pages = ceil($total_data / $limit);

// Ambil data anggota
$stmt = $pdo->prepare("SELECT * FROM anggota $where_clause ORDER BY tanggal_daftar DESC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$anggota_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - UKM Taekwondo UNINDRA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg,rgb(96, 9, 9) 0%,rgb(10, 38, 87) 100%);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center text-white mb-4">
                        <h5>Admin Panel</h5>
                        <img src="image.png" style="height:40px; width:auto;"></img><small>UKM TAEKWONDO UNINDRA</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="laporan.php">
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
                    <h1 class="h2"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success_message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= $error_message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

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

                <!-- Filter and Search -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Cari</label>
                                <input type="text" name="search" class="form-control" placeholder="Nama, NIM, atau Email" value="<?= htmlspecialchars($search) ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Diterima" <?= $status_filter == 'Diterima' ? 'selected' : '' ?>>Diterima</option>
                                    <option value="Ditolak" <?= $status_filter == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Cari
                                    </button>
                                    <a href="dashboard.php" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Data Pendaftar (<?= $total_data ?> total)
                        </h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Fakultas</th>
                                    <th>Status</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($anggota_list)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Tidak ada data pendaftar</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = $offset + 1; ?>
                                    <?php foreach ($anggota_list as $anggota): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($anggota['nim']) ?></td>
                                            <td><?= htmlspecialchars($anggota['nama_lengkap']) ?></td>
                                            <td><?= htmlspecialchars($anggota['email']) ?></td>
                                            <td><?= htmlspecialchars($anggota['fakultas']) ?></td>
                                            <td>
                                                <span class="badge badge-<?= strtolower($anggota['status_pendaftaran']) ?>">
                                                    <?= $anggota['status_pendaftaran'] ?>
                                                </span>
                                            </td>
                                            <td><?= date('d/m/Y H:i', strtotime($anggota['tanggal_daftar'])) ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?= $anggota['id'] ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#statusModal<?= $anggota['id'] ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <a href="?delete=<?= $anggota['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="card-footer">
                            <nav>
                                <ul class="pagination justify-content-center mb-0">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>">Previous</a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>">Next</a>
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

    <!-- Modal Detail & Status untuk setiap anggota -->
    <?php foreach ($anggota_list as $anggota): ?>
        <!-- Detail Modal -->
        <div class="modal fade" id="detailModal<?= $anggota['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Detail Pendaftar</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr><td><strong>NIM:</strong></td><td><?= htmlspecialchars($anggota['nim']) ?></td></tr>
                                    <tr><td><strong>Nama:</strong></td><td><?= htmlspecialchars($anggota['nama_lengkap']) ?></td></tr>
                                    <tr><td><strong>Tempat, Tanggal Lahir:</strong></td><td><?= htmlspecialchars($anggota['tempat_lahir']) ?>, <?= formatTanggalIndonesia($anggota['tanggal_lahir']) ?></td></tr>
                                    <tr><td><strong>Jenis Kelamin:</strong></td><td><?= $anggota['jenis_kelamin'] ?></td></tr>
                                    <tr><td><strong>Alamat:</strong></td><td><?= nl2br(htmlspecialchars($anggota['alamat'])) ?></td></tr>
                                    <tr><td><strong>No. Telepon:</strong></td><td><?= htmlspecialchars($anggota['no_telepon']) ?></td></tr>
                                    <tr><td><strong>Email:</strong></td><td><?= htmlspecialchars($anggota['email']) ?></td></tr>
                                    <tr><td><strong>Program Studi:</strong></td><td><?= htmlspecialchars($anggota['program_studi']) ?></td></tr>
                                    <tr><td><strong>Fakultas:</strong></td><td><?= htmlspecialchars($anggota['fakultas']) ?></td></tr>
                                    <tr><td><strong>Semester:</strong></td><td><?= $anggota['semester'] ?></td></tr>
                                    <tr><td><strong>Tingkat Sabuk:</strong></td><td><?= $anggota['tingkat_sabuk'] ?></td></tr>
                                    <tr><td><strong>Pengalaman:</strong></td><td><?= $anggota['pengalaman_taekwondo'] ? nl2br(htmlspecialchars($anggota['pengalaman_taekwondo'])) : '-' ?></td></tr>
                                    <tr><td><strong>Motivasi:</strong></td><td><?= nl2br(htmlspecialchars($anggota['motivasi'])) ?></td></tr>
                                    <tr><td><strong>Status:</strong></td><td><span class="badge badge-<?= strtolower($anggota['status_pendaftaran']) ?>"><?= $anggota['status_pendaftaran'] ?></span></td></tr>
                                </table>
                            </div>
                            <div class="col-md-4 text-center">
                                <?php if ($anggota['foto']): ?>
                                    <img src="../uploads/<?= htmlspecialchars($anggota['foto']) ?>" class="img-fluid rounded" alt="Foto" style="max-height: 200px;">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-user fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Modal -->
        <div class="modal fade" id="statusModal<?= $anggota['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">Update Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $anggota['id'] ?>">
                            <p><strong><?= htmlspecialchars($anggota['nama_lengkap']) ?></strong><br>
                            NIM: <?= htmlspecialchars($anggota['nim']) ?></p>
                            
                            <div class="mb-3">
                                <label class="form-label">Status Pendaftaran</label>
                                <select name="status" class="form-select" required>
                                    <option value="Pending" <?= $anggota['status_pendaftaran'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Diterima" <?= $anggota['status_pendaftaran'] == 'Diterima' ? 'selected' : '' ?>>Diterima</option>
                                    <option value="Ditolak" <?= $anggota['status_pendaftaran'] == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>