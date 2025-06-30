<?php
require_once 'config.php';

$anggota = null;
$error_message = '';
$success_message = '';

// Proses pencarian
if ($_POST && isset($_POST['cek_status'])) {
    $nim = trim($_POST['nim']);
    $email = trim($_POST['email']);
    
    if (empty($nim) || empty($email)) {
        $error_message = "NIM dan Email harus diisi!";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT a.*, p.nama_program, p.jadwal_latihan, p.lokasi_latihan 
                                  FROM anggota a 
                                  LEFT JOIN program_latihan p ON a.program_id = p.id 
                                  WHERE a.nim = ? AND a.email = ?");
            $stmt->execute([$nim, $email]);
            $anggota = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$anggota) {
                $error_message = "Data tidak ditemukan! Pastikan NIM dan Email yang Anda masukkan benar.";
            } else {
                // Log aktivitas pencarian
                $log_stmt = $pdo->prepare("INSERT INTO log_aktivitas (nim, email, aktivitas, waktu) VALUES (?, ?, 'cek_status', NOW())");
                $log_stmt->execute([$nim, $email]);
            }
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Fungsi untuk mendapatkan badge status
function getStatusBadge($status) {
    switch(strtolower($status)) {
        case 'pending':
            return ['class' => 'status-pending', 'icon' => 'fas fa-clock', 'text' => 'Menunggu Verifikasi'];
        case 'diterima':
            return ['class' => 'status-diterima', 'icon' => 'fas fa-check-circle', 'text' => 'Diterima'];
        case 'ditolak':
            return ['class' => 'status-ditolak', 'icon' => 'fas fa-times-circle', 'text' => 'Ditolak'];
        default:
            return ['class' => 'status-pending', 'icon' => 'fas fa-question-circle', 'text' => 'Status Tidak Diketahui'];
    }
}

// Fungsi untuk mendapatkan progress bar
function getProgressPercentage($status) {
    switch(strtolower($status)) {
        case 'pending': return 33;
        case 'diterima': return 100;
        case 'ditolak': return 100;
        default: return 0;
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            --success-gradient: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --danger-gradient: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hero-section {
            background: var(--primary-gradient);
            color: white;
            padding: 80px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="1" fill="white" opacity="0.1"/><circle cx="10" cy="90" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }

        .hero-section .container {
            position: relative;
            z-index: 1;
        }

        .card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: none;
            border-radius: 15px;
            overflow: hidden;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: none;
            font-weight: 600;
        }

        .status-pending {
            background: var(--warning-gradient);
            color: white;
        }

        .status-diterima {
            background: var(--success-gradient);
            color: white;
        }

        .status-ditolak {
            background: var(--danger-gradient);
            color: white;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 60, 114, 0.4);
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #2a5298;
            box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.25);
        }

        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(0, 0, 0, 0.8) !important;
        }

        .status-timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 14px;
        }

        .timeline-icon.active {
            background: var(--success-gradient);
        }

        .timeline-icon.pending {
            background: var(--warning-gradient);
        }

        .timeline-icon.inactive {
            background: #dee2e6;
            color: #6c757d;
        }

        .info-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #2a5298;
        }

        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .floating-btn .btn {
            border-radius: 50%;
            width: 60px;
            height: 60px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }
            
            .hero-section h1 {
                font-size: 2rem;
            }
        }

        .progress-custom {
            height: 8px;
            border-radius: 10px;
            background: #e9ecef;
            overflow: hidden;
        }

        .progress-bar-custom {
            height: 100%;
            background: var(--primary-gradient);
            transition: width 0.6s ease;
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        @media print {
            .navbar, .floating-btn, footer, .no-print {
                display: none !important;
            }
            
            body {
                background: white !important;
            }
            
            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1100;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner-border-lg {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="spinner-border spinner-border-lg text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container"></div>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-fist-raised me-2"></i>UKM Taekwondo UNINDRA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-home me-1"></i>Beranda
                    </a>
                    <a class="nav-link" href="pendaftaran.php">
                        <i class="fas fa-user-plus me-1"></i>Daftar
                    </a>
                    <a class="nav-link active" href="cek_status.php">
                        <i class="fas fa-search me-1"></i>Cek Status
                        <?php if($anggota && strtolower($anggota['status_pendaftaran']) == 'diterima'): ?>
                            <span class="notification-badge">!</span>
                        <?php endif; ?>
                    </a>
                    <a class="nav-link" href="admin/login.php">
                        <i class="fas fa-sign-in-alt me-1"></i>Login Admin
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="animate__animated animate__fadeInDown">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-search me-3"></i>Cek Status Pendaftaran
                </h1>
                <p class="lead mb-4">Periksa status pendaftaran Anda sebagai anggota UKM Taekwondo UNINDRA</p>
                <div class="d-flex justify-content-center">
                    <div class="glass-effect px-4 py-2 rounded-pill">
                        <i class="fas fa-users me-2"></i>
                        <span>Sistem Terintegrasi & Real-time</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container my-5" style="margin-top: 120px !important;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Oops!</strong> <?= $error_message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($success_message): ?>
                    <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= $success_message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Form Pencarian -->
                <div class="card mb-4 animate__animated animate__fadeInUp">
                    <div class="card-header text-white" style="background: var(--primary-gradient);">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-search me-2"></i>Masukkan Data Anda
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="statusForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-id-card me-1"></i>NIM 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="nim" class="form-control" required 
                                           value="<?= isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : '' ?>"
                                           placeholder="Contoh: 123456789"
                                           pattern="[0-9]{8,15}"
                                           title="NIM harus berupa angka 8-15 digit">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-envelope me-1"></i>Email 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" class="form-control" required 
                                           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                                           placeholder="contoh@email.com">
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="cek_status" class="btn btn-primary btn-lg">
                                    <i class="fas fa-search me-2"></i>Cek Status Pendaftaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Hasil Status -->
                <?php if ($anggota): ?>
                    <?php $statusInfo = getStatusBadge($anggota['status_pendaftaran']); ?>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card animate__animated animate__fadeInLeft">
                                <div class="card-header <?= $statusInfo['class'] ?>">
                                    <h5 class="card-title mb-0">
                                        <i class="<?= $statusInfo['icon'] ?> me-2"></i>
                                        Status: <?= $statusInfo['text'] ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <!-- Progress Bar -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold">Progress Pendaftaran</span>
                                            <span class="badge bg-primary"><?= getProgressPercentage($anggota['status_pendaftaran']) ?>%</span>
                                        </div>
                                        <div class="progress-custom">
                                            <div class="progress-bar-custom" style="width: <?= getProgressPercentage($anggota['status_pendaftaran']) ?>%"></div>
                                        </div>
                                    </div>

                                    <!-- Data Anggota -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td width="120"><i class="fas fa-id-card text-primary me-2"></i><strong>NIM</strong></td>
                                                    <td>: <?= htmlspecialchars($anggota['nim']) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fas fa-user text-primary me-2"></i><strong>Nama</strong></td>
                                                    <td>: <?= htmlspecialchars($anggota['nama_lengkap']) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fas fa-envelope text-primary me-2"></i><strong>Email</strong></td>
                                                    <td>: <?= htmlspecialchars($anggota['email']) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fas fa-phone text-primary me-2"></i><strong>Telepon</strong></td>
                                                    <td>: <?= htmlspecialchars($anggota['no_telepon'] ?? '-') ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td width="120"><i class="fas fa-graduation-cap text-primary me-2"></i><strong>Jurusan</strong></td>
                                                    <td>: <?= htmlspecialchars($anggota['jurusan'] ?? '-') ?></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fas fa-calendar text-primary me-2"></i><strong>Angkatan</strong></td>
                                                    <td>: <?= htmlspecialchars($anggota['angkatan'] ?? '-') ?></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fas fa-clock text-primary me-2"></i><strong>Tgl Daftar</strong></td>
                                                    <td>: <?= date('d/m/Y H:i', strtotime($anggota['tanggal_daftar'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fas fa-medal text-primary me-2"></i><strong>Pengalaman</strong></td>
                                                    <td>: <?= htmlspecialchars($anggota['pengalaman_taekwondo'] ?? 'Pemula') ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Program Latihan -->
                                    <?php if (!empty($anggota['nama_program'])): ?>
                                        <div class="info-box">
                                            <h6 class="fw-bold mb-3">
                                                <i class="fas fa-dumbbell text-primary me-2"></i>
                                                Program Latihan
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <strong>Program:</strong><br>
                                                    <?= htmlspecialchars($anggota['nama_program']) ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Jadwal:</strong><br>
                                                    <?= htmlspecialchars($anggota['jadwal_latihan']) ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Lokasi:</strong><br>
                                                    <?= htmlspecialchars($anggota['lokasi_latihan']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Keterangan Status -->
                                    <?php if (!empty($anggota['keterangan'])): ?>
                                        <div class="alert alert-info">
                                            <h6 class="fw-bold mb-2">
                                                <i class="fas fa-info-circle me-2"></i>Keterangan
                                            </h6>
                                            <?= nl2br(htmlspecialchars($anggota['keterangan'])) ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2 flex-wrap no-print">
                                        <button class="btn btn-outline-primary" onclick="window.print()">
                                            <i class="fas fa-print me-1"></i>Cetak
                                        </button>
                                        <button class="btn btn-outline-success" onclick="shareStatus()">
                                            <i class="fas fa-share me-1"></i>Bagikan
                                        </button>
                                        <button class="btn btn-outline-info" onclick="exportToPDF()">
                                            <i class="fas fa-file-pdf me-1"></i>Export PDF
                                        </button>
                                        <?php if (strtolower($anggota['status_pendaftaran']) == 'diterima'): ?>
                                            <a href="kartu_anggota.php?nim=<?= $anggota['nim'] ?>&email=<?= $anggota['email'] ?>" class="btn btn-success">
                                                <i class="fas fa-id-badge me-1"></i>Unduh Kartu Anggota
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Timeline Status -->
                            <div class="card animate__animated animate__fadeInRight">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-timeline me-2"></i>Timeline Pendaftaran
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="status-timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-icon active">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <div>
                                                <strong>Pendaftaran Diterima</strong><br>
                                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($anggota['tanggal_daftar'])) ?></small>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-item">
                                            <div class="timeline-icon <?= strtolower($anggota['status_pendaftaran']) != 'pending' ? 'active' : 'pending' ?>">
                                                <i class="fas fa-search"></i>
                                            </div>
                                            <div>
                                                <strong>Verifikasi Data</strong><br>
                                                <small class="text-muted">
                                                    <?= strtolower($anggota['status_pendaftaran']) != 'pending' ? 'Selesai' : 'Dalam Proses' ?>
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-item">
                                            <div class="timeline-icon <?= strtolower($anggota['status_pendaftaran']) == 'diterima' ? 'active' : 'inactive' ?>">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div>
                                                <strong>Hasil Seleksi</strong><br>
                                                <small class="text-muted">
                                                    <?= strtolower($anggota['status_pendaftaran']) == 'diterima' ? 'Diterima' : 
                                                        (strtolower($anggota['status_pendaftaran']) == 'ditolak' ? 'Ditolak' : 'Menunggu') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tips Card -->
                            <div class="card mt-3 animate__animated animate__fadeInRight animate__delay-1s">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-lightbulb me-2"></i>Tips
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Simpan screenshot status ini
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Periksa email secara berkala
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Hubungi admin jika ada pertanyaan
                                        </li>
                                        <li>
                                            <i class="fas fa-check text-success me-2"></i>
                                            Siapkan berkas tambahan jika diperlukan
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Contact Info Card -->
                            <div class="card mt-3 animate__animated animate__fadeInRight animate__delay-2s">
                                <div class="card-header bg-info text-white">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-phone me-2"></i>Kontak Admin
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fab fa-whatsapp text-success me-2"></i>
                                        <a href="https://wa.me/628123456789" class="text-decoration-none">
                                            +62 812-3456-789
                                        </a>
                                    </div>
                                    <div class="mb-2">
                                        <i class="fas fa-envelope text-primary me-2"></i>
                                        <a href="mailto:admin@ukmtaekwondo.com" class="text-decoration-none">
                                            admin@ukmtaekwondo.com
                                        </a>
                                    </div>
                                    <div>
                                        <i class="fab fa-instagram text-danger me-2"></i>
                                        <a href="https://instagram.com/ukmtaekwondo" class="text-decoration-none">
                                            @ukmtaekwondo
                                        </a>
                                    </div>
                                                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center text-white" style="background: var(--primary-gradient); padding: 20px 0;">
        <div class="container">
            <p class="mb-0">© 2023 UKM Taekwondo UNINDRA. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi untuk membagikan status
        function shareStatus() {
            const statusText = document.querySelector('.card-title').innerText;
            const shareData = {
                title: 'Status Pendaftaran',
                text: statusText,
                url: window.location.href
            };
            navigator.share(shareData).catch(console.error);
        }

        // Fungsi untuk mengekspor ke PDF (placeholder)
        function exportToPDF() {
            alert('Fitur ini belum tersedia.');
        }

        // Menampilkan loading overlay saat form disubmit
        document.getElementById('statusForm').addEventListener('submit', function() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        });
    </script>
</body>
</html>
