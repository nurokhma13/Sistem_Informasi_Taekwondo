<?php
require_once 'config.php';
$success_message = '';
$error_message = '';

// Proses pendaftaran
if ($_POST && isset($_POST['daftar'])) {
    try {
        // Validasi input
        $nim = trim($_POST['nim']);
        $nama = trim($_POST['nama_lengkap']);
        $tempat_lahir = trim($_POST['tempat_lahir']);
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $alamat = trim($_POST['alamat']);
        $no_telepon = trim($_POST['no_telepon']);
        $email = trim($_POST['email']);
        $program_studi = trim($_POST['program_studi']);
        $fakultas = $_POST['fakultas'];
        $semester = $_POST['semester'];
        $tingkat_sabuk = $_POST['tingkat_sabuk'];
        $pengalaman = trim($_POST['pengalaman_taekwondo']);
        $motivasi = trim($_POST['motivasi']);
        
        // Cek apakah NIM atau email sudah ada
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM anggota WHERE nim = ? OR email = ?");
        $check_stmt->execute([$nim, $email]);
        
        if ($check_stmt->fetchColumn() > 0) {
            $error_message = "NIM atau Email sudah terdaftar!";
        } else {
            // Upload foto
            $foto_name = '';
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                $foto_name = uploadFoto($_FILES['foto']);
                if (!$foto_name) {
                    $error_message = "Gagal upload foto. Pastikan file berformat JPG/PNG dan ukuran maksimal 2MB.";
                }
            }
            
            if (empty($error_message)) {
                // Insert data
                $stmt = $pdo->prepare("INSERT INTO anggota (nim, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_telepon, email, program_studi, fakultas, semester, tingkat_sabuk, pengalaman_taekwondo, motivasi, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                $stmt->execute([
                    $nim, $nama, $tempat_lahir, $tanggal_lahir, $jenis_kelamin,
                    $alamat, $no_telepon, $email, $program_studi, $fakultas,
                    $semester, $tingkat_sabuk, $pengalaman, $motivasi, $foto_name
                ]);
                
                $success_message = "Pendaftaran berhasil! Silakan tunggu konfirmasi dari admin.";
            }
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran UKM Taekwondo UNINDRA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg,rgb(149, 5, 5) 20%,rgb(20, 14, 144) 100%);
            color: white;
            padding: 2px 0;
            text-align: center;
        }
        .card {
            box-shadow: 0 4px 6px rgba(255, 255, 255, 0.1);
            border: none;
        }
        .form-control:focus {
            border-color:rgb(15, 50, 112);
            box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg,rgb(114, 30, 30) 0%,rgb(26, 65, 134) 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg,rgb(135, 20, 20) 0%,rgb(7, 47, 122) 100%);
        }
        
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="Picture1.png" alt="Logo Website" style="height:30px; width:auto;">
                <i class=""></i>UKM Taekwondo Universitas Indraprasta PGRI
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="admin/login.php">Login Admin</a>
                <a class="nav-link" href="cek_status.php">Cek Status Pendaftaran</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">
                 <img src="Picture1.png" alt="Logo Website" style="height:100px; width:auto;">
                <i class="lead"></i>UKM TAEKWONDO UNINDRA
            </h1>
            <p class="lead">Bergabunglah dengan kami dan kembangkan kemampuan Taekwondo Anda!</p>
            <p>"Membentuk Karakter, Membangun Prestasi, Mengharumkan UNINDRA"</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container my-5">
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= $success_message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= $error_message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="border rounded p-3">
                    <div class="card-header bg-danger btn-primary text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-user-plus me-2"></i>Formulir Pendaftaran Anggota
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIM <span class="text-danger">*</span></label>
                                    <input type="text" name="nim" class="form-control" required maxlength="20">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_lengkap" class="form-control" required maxlength="100">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" name="tempat_lahir" class="form-control" required maxlength="50">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_lahir" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                    <input type="tel" name="no_telepon" class="form-control" required maxlength="15">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea name="alamat" class="form-control" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required maxlength="100">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                                    <input type="text" name="program_studi" class="form-control" required maxlength="50">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fakultas <span class="text-danger">*</span></label>
                                    <select name="fakultas" class="form-select" required>
                                        <option value="">Pilih Fakultas</option>
                                        <option value="FIPPS">FIPPS (Fakultas Ilmu Pendidikan Pengetahuan Sosial)</option>
                                        <option value="FBS">FBS (Fakultas Bahasa dan Sastra)</option>
                                        <option value="FTIK">FTIK (Fakultas Teknik dan Ilmu Komputer)</option>
                                        <option value="FMIPA">FMIPA (Fakultas Matematika dan Ilmu Pengetahuan Alam)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Semester <span class="text-danger">*</span></label>
                                    <select name="semester" class="form-select" required>
                                        <option value="">Pilih Semester</option>
                                        <?php for($i = 1; $i <= 8; $i++): ?>
                                            <option value="<?= $i ?>">Semester <?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tingkat Sabuk Saat Ini</label>
                                    <select name="tingkat_sabuk" class="form-select">
                                        <option value="Putih">Putih (Pemula)</option>
                                        <option value="Kuning">Kuning</option>
                                        <option value="Hijau">Hijau</option>
                                        <option value="Biru">Biru</option>
                                        <option value="Coklat">Coklat</option>
                                        <option value="Hitam">Hitam</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pengalaman Taekwondo</label>
                                <textarea name="pengalaman_taekwondo" class="form-control" rows="3" placeholder="Ceritakan pengalaman Anda dalam Taekwondo (jika ada)"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Motivasi Bergabung <span class="text-danger">*</span></label>
                                <textarea name="motivasi" class="form-control" rows="3" required placeholder="Ceritakan motivasi Anda bergabung dengan UKM Taekwondo UNINDRA"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto Diri</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                <div class="form-text">Format: JPG, PNG. Maksimal 2MB</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="daftar" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Daftar Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-auto w-100">
    <div class="container text-center">
      <p>&copy; 2025 UKM TAEKWONDO UNINDRA. All rights reserved.</p>
      <p><i class="fas fa-university me-2"></i>Universitas Indraprasta PGRI</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
