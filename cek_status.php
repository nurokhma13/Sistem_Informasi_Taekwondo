<?php
require_once 'config.php';

$result = null;
$error_message = '';
$search_performed = false;

// Proses pencarian status
if ($_POST && isset($_POST['cek_status'])) {
    try {
        $search_key = trim($_POST['search_key']);
        $search_type = $_POST['search_type'];
        
        if (empty($search_key)) {
            $error_message = "Mohon masukkan NIM atau Email untuk pencarian.";
        } else {
            $search_performed = true;
            
            // Query berdasarkan tipe pencarian
            if ($search_type == 'nim') {
                $stmt = $pdo->prepare("SELECT * FROM anggota WHERE nim = ?");
            } else {
                $stmt = $pdo->prepare("SELECT * FROM anggota WHERE email = ?");
            }
            
            $stmt->execute([$search_key]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                $error_message = "Data tidak ditemukan. Pastikan NIM atau Email yang dimasukkan benar.";
            }
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Fungsi untuk menentukan status badge
function getStatusBadge($status = 'pending') {
    switch (strtolower($status)) {
        case 'approved':
        case 'diterima':
            return '<span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i>Diterima</span>';
        case 'rejected':
        case 'ditolak':
            return '<span class="badge bg-danger fs-6"><i class="fas fa-times-circle me-1"></i>Ditolak</span>';
        case 'pending':
        case 'menunggu':
        default:
            return '<span class="badge bg-warning fs-6"><i class="fas fa-clock me-1"></i>Menunggu Konfirmasi</span>';
    }
}


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pendaftaran - UKM Taekwondo UNINDRA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, rgb(149, 5, 5) 20%, rgb(20, 14, 144) 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            margin-bottom: 20px;
        }
        .form-control:focus {
            border-color: rgb(15, 50, 112);
            box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, rgb(114, 30, 30) 0%, rgb(26, 65, 134) 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, rgb(135, 20, 20) 0%, rgb(15, 85, 215) 100%);
        }
        .status-card {
            border-left: 5px solid #28a745;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        .status-pending {
            border-left-color: #ffc107;
        }
        .status-rejected {
            border-left-color: #dc3545;
        }
        .info-row {
            border-bottom: 1px solid #dee2e6;
            padding: 10px 0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .search-section {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="Picture1.png" alt="Logo Website" style="height:30px; width:auto;">
                UKM Taekwondo Universitas Indraprasta PGRI
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Pendaftaran</a>
                <a class="nav-link" href="admin/login.php">Login Admin</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-5 fw-bold mb-3">
                <i class="fas fa-search me-3"></i>Cek Status Pendaftaran
            </h1>
            <p class="lead">Masukkan NIM atau Email untuk mengecek status pendaftaran Anda</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container my-5">
        <!-- Search Section -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="search-section">
                    <form method="POST">
                        <div class="row align-items-end">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Cari Berdasarkan:</label>
                                <select name="search_type" class="form-select" required>
                                    <option value="nim" <?= (isset($_POST['search_type']) && $_POST['search_type'] == 'nim') ? 'selected' : '' ?>>NIM</option>
                                    <option value="email" <?= (isset($_POST['search_type']) && $_POST['search_type'] == 'email') ? 'selected' : '' ?>>Email</option>
                                </select>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label fw-bold">Masukkan NIM atau Email:</label>
                                <input type="text" name="search_key" class="form-control" 
                                       value="<?= isset($_POST['search_key']) ? htmlspecialchars($_POST['search_key']) : '' ?>" 
                                       placeholder="Contoh: 123456789 atau email@example.com" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="submit" name="cek_status" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Cek Status
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <?php if ($error_message): ?>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= $error_message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Results Section -->
        <?php if ($result): ?>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card status-card <?= isset($result['status']) ? 'status-' . strtolower($result['status']) : 'status-pending' ?>">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">
                                    <i class="fas fa-user me-2"></i>Status Pendaftaran
                                </h4>
                                <?= getStatusBadge($result['status'] ?? 'pending') ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Data Pribadi -->
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3"><i class="fas fa-user-circle me-2"></i>Data Pribadi</h5>
                                    
                                    <div class="info-row">
                                        <div class="row">
                                            <div class="col-4"><strong>NIM:</strong></div>
                                            <div class="col-8"><?= htmlspecialchars($result['nim']) ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-row">
                                        <div class="row">
                                            <div class="col-4"><strong>Nama:</strong></div>
                                            <div class="col-8"><?= htmlspecialchars($result['nama_lengkap']) ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-row">
                                        <div class="row">
                                            <div class="col-4"><strong>Email:</strong></div>
                                            <div class="col-8"><?= htmlspecialchars($result['email']) ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-row">
                                        <div class="row">
                                            <div class="col-4"><strong>Telepon:</strong></div>
                                            <div class="col-8"><?= htmlspecialchars($result['no_telepon']) ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-row">
                                        <div class="row">
                                            <div class="col-4"><strong>TTL:</strong></div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-row">
                                        <div class="row">
                                            <div class="col-4"><strong>Gender:</strong></div>
                                            <div class="col-8"><?= htmlspecialchars($result['jenis_kelamin']) ?></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Akademik & Taekwondo -->
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3"><i class="fas fa-graduation-cap me-2"></i>Data Akademik</h5>
                                    
                                    <div class="info-row">
                                        <div class="row">
                                            <div class="col-4"><strong>Fakultas:</strong></div>
                                            <div class="col-8"><?= htmlspecialchars($result['fakultas']) ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-row">
                                        <div class="row">
                                            <div class="col-4"><strong>Prodi:</strong></div>
                                            <div class="col-8"><?= htmlspecialchars($result['program_studi']) ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-row">
                                        <div class="row">
                                            <div class="col-4"><strong>Semester:</strong></div>
                                            <div class="col-8">Semester <?= htmlspecialchars($result['semester']) ?></div>
                                        </div>
                                    </div>
                                    
                                    <h5 class="text-primary mb-3 mt-4"><i class="fas fa-medal me-2"></i>Data Taekwondo</h5>
                                    
                                    <div class="info-row">
                                        <div class="row">
                                            <div class="col-4"><strong>Sabuk:</strong></div>
                                            <div class="col-8">
                                                <span class="badge bg-secondary"><?= htmlspecialchars($result['tingkat_sabuk']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($result['pengalaman_taekwondo'])): ?>
                                    <div class="info-row">
                                        <div class="row">
                                            <div class="col-4"><strong>Pengalaman:</strong></div>
                                            <div class="col-8"><?= nl2br(htmlspecialchars($result['pengalaman_taekwondo'])) ?></div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Motivasi -->
                            <?php if (!empty($result['motivasi'])): ?>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3"><i class="fas fa-heart me-2"></i>Motivasi Bergabung</h5>
                                    <div class="bg-light p-3 rounded">
                                        <?= nl2br(htmlspecialchars($result['motivasi'])) ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Foto -->
                            <?php if (!empty($result['foto']) && file_exists('uploads/' . $result['foto'])): ?>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3"><i class="fas fa-camera me-2"></i>Foto Pendaftar</h5>
                                    <div class="text-center">
                                        <img src="uploads/<?= htmlspecialchars($result['foto']) ?>" 
                                             alt="Foto <?= htmlspecialchars($result['nama_lengkap']) ?>" 
                                             class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Tanggal Pendaftaran -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="text-muted text-center">
                                        <small>
                                            <i class="fas fa-calendar me-1"></i>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Information -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>Informasi Status</h5>
                            <?php
                            $status = $result['status'] ?? 'pending';
                            switch (strtolower($status)) {
                                case 'approved':
                                case 'diterima':
                                    echo '<div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Selamat!</strong> Pendaftaran Anda telah <strong>DITERIMA</strong>. 
                                            Silakan hubungi admin UKM untuk informasi lebih lanjut mengenai jadwal latihan dan kegiatan.
                                          </div>';
                                    break;
                                case 'rejected':
                                case 'ditolak':
                                    echo '<div class="alert alert-danger">
                                            <i class="fas fa-times-circle me-2"></i>
                                            <strong>Maaf,</strong> pendaftaran Anda <strong>DITOLAK</strong>. 
                                            Silakan hubungi admin untuk informasi lebih lanjut atau mencoba mendaftar ulang di periode berikutnya.
                                          </div>';
                                    break;
                                default:
                                    echo '<div class="alert alert-warning">
                                            <i class="fas fa-clock me-2"></i>
                                            Pendaftaran Anda sedang dalam <strong>PROSES REVIEW</strong>. 
                                            Mohon bersabar menunggu konfirmasi dari admin UKM. Anda akan dihubungi melalui email atau telepon.
                                          </div>';
                                    break;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ($search_performed && !$result && !$error_message): ?>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-search text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                            <h4 class="mt-3">Data Tidak Ditemukan</h4>
                            <p class="text-muted">
                                Tidak ada data pendaftaran yang ditemukan dengan NIM atau Email yang Anda masukkan.<br>
                                Pastikan data yang dimasukkan sudah benar atau hubungi admin jika ada masalah.
                            </p>
                            <a href="index.php" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Information Card -->
        <?php if (!$search_performed): ?>
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>Cara Mengecek Status</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-search me-2"></i>Pencarian</h6>
                                <ul>
                                    <li>Pilih apakah ingin mencari berdasarkan <strong>NIM</strong> atau <strong>Email</strong></li>
                                    <li>Masukkan NIM atau Email yang digunakan saat pendaftaran</li>
                                    <li>Klik tombol <strong>"Cek Status"</strong></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-list me-2"></i>Status Pendaftaran</h6>
                                <ul>
                                    <li><?= getStatusBadge('pending') ?> : Sedang dalam proses review</li>
                                    <li><?= getStatusBadge('approved') ?> : Pendaftaran diterima</li>
                                    <li><?= getStatusBadge('rejected') ?> : Pendaftaran ditolak</li>
                                </ul>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Tips:</strong> Jika belum mendaftar, silakan <a href="index.php" class="alert-link">klik di sini untuk mendaftar</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2025 UKM TAEKWONDO UNINDRA. All rights reserved.</p>
            <p><i class="fas fa-university me-2"></i>Universitas Indraprasta PGRI</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>