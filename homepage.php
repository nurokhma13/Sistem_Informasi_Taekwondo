<?php
// Konfigurasi dasar
$page_title = "UKM Taekwondo UNINDRA - Beranda";
$current_page = "home";
$stats = [
    'anggota_aktif' => 80,
    'prestasi' => 20,
    'tahun_berdiri' => 8,
    'pelatih' => 12
];
// Data jadwal latihan
$jadwal = [
    ['hari' => 'Selasa', 'waktu' => '16:00 - 18:00', 'tingkat' => 'Pemula & Menengah'],
    ['hari' => 'Kamis', 'waktu' => '16:00 - 18:00', 'tingkat' => 'Lanjutan & Kompetisi'],
    ['hari' => 'Sabtu', 'waktu' => '08:00 - 10:00', 'tingkat' => 'Semua Tingkat']
];

// Informasi kontak
$kontak = [
    'alamat' => [
        'nama' => 'Universitas Indraprasta PGRI',
        'jalan' => 'Jl. Nangka No. 58 C, Tanjung Barat',
        'kota' => 'Jagakarsa, Jakarta Selatan 12530'
    ],
    'telepon' => [
        'utama' => '(021) 78881717',
        'wa' => '0812-3456-7890',
        'jam' => 'Senin - Jumat, 08:00 - 17:00'
    ],
    'digital' => [
        'email' => 'ukm.taekwondo@unindra.ac.id',
        'instagram' => '@ukmtaekwondounindra',
        'facebook' => 'UKM Taekwondo UNINDRA'
    ]
];

// Function untuk menampilkan format tanggal
function formatTanggal($tanggal) {
    return date('d F Y', strtotime($tanggal));
}

// Function untuk limit text
function limitText($text, $limit = 100) {
    return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="Unit Kegiatan Mahasiswa Taekwondo Universitas Indraprasta PGRI. Bergabunglah dengan komunitas taekwondo terbaik untuk mengembangkan kemampuan dan meraih prestasi.">
    <meta name="keywords" content="UKM Taekwondo, UNINDRA, Universitas Indraprasta PGRI, taekwondo Jakarta, beladiri, mahasiswa">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-red: #950505;
            --primary-blue: #140e90;
            --secondary-red: #721e1e;
            --secondary-blue: #1a4186;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-red) 20%, var(--primary-blue) 100%);
            color: white;
            padding: 100px 0;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.1)" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .card {
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-red) 0%, var(--secondary-blue) 100%);
            border: none;
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-blue) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .section-title {
            position: relative;
            padding-bottom: 20px;
            margin-bottom: 50px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-red), var(--primary-blue));
            border-radius: 2px;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--primary-red), var(--primary-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 30px;
        }

        .stats-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 80px 0;
        }

        .stat-item {
            text-align: center;
            padding: 30px 15px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: var(--primary-red);
            display: block;
        }

        .gallery-img {
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .gallery-img:hover {
            transform: scale(1.05);
        }

        .testimonial-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            position: relative;
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -10px;
            left: 20px;
            font-size: 60px;
            color: var(--primary-red);
            opacity: 0.3;
        }

        .news-card {
            border-left: 4px solid var(--primary-red);
            transition: all 0.3s ease;
        }

        .news-card:hover {
            border-left-color: var(--primary-blue);
        }

        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-red), var(--primary-blue));
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .navbar-brand img {
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: rotate(360deg);
        }

        .program-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            height: 100%;
            transition: all 0.3s ease;
        }

        .program-card:hover {
            background: linear-gradient(135deg, var(--primary-red), var(--primary-blue));
            color: white;
        }

        .program-card:hover .feature-icon {
            background: rgba(255,255,255,0.2);
        }
    </style>
</head>
<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
</script>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <img src="Picture1.png" alt="Logo UKM Taekwondo" style="height:40px; width:auto; margin-right: 10px;">
                UKM Taekwondo UNINDRA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'home') ? 'active' : ''; ?>" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#programs">Program</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gallery">Galeri</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                </ul>
                <div class="navbar-nav">
                    <a class="nav-link" href="daftar.php">
                        <i class="fas fa-user-plus me-1"></i>Daftar
                    </a>
                    <a class="nav-link" href="cek_status.php">
                        <i class="fas fa-search me-1"></i>Cek Status
                    </a>
                    <a class="nav-link" href="admin/login.php">
                        <i class="fas fa-sign-in-alt me-1"></i>Login Admin
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="display-3 fw-bold mb-4">
                        UKM TAEKWONDO<br>
                        <span class="text-warning">UNINDRA</span>
                    </h1>
                    <p class="lead mb-4">
                        Bergabunglah dengan Unit Kegiatan Mahasiswa Taekwondo Universitas Indraprasta PGRI. 
                        Kembangkan kemampuan, bangun karakter, dan raih prestasi bersama kami!
                    </p>
                    <p class="h5 mb-4 text-warning fst-italic">
                        "Membentuk Karakter, Membangun Prestasi, Mengharumkan UNINDRA"
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="daftar.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-rocket me-2"></i>Daftar Sekarang
                        </a>
                        <a href="#about" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-info-circle me-2"></i>Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left">
                    <img src="Picture1.png" alt="Logo UKM Taekwondo" class="img-fluid" style="max-height: 1000px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-item">
                        <span class="stat-number" data-count="<?php echo $stats['anggota_aktif']; ?>">0</span>
                        <p class="text-muted">Anggota Aktif</p>
                    </div>
                </div>
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                        <span class="stat-number" data-count="<?php echo $stats['prestasi']; ?>">0</span>
                        <p class="text-muted">Prestasi Diraih</p>
                    </div>
                </div>
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-item">
                        <span class="stat-number" data-count="<?php echo $stats['tahun_berdiri']; ?>">0</span>
                        <p class="text-muted">Tahun Berdiri</p>
                    </div>
                </div>
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-item">
                        <span class="stat-number" data-count="<?php echo $stats['pelatih']; ?>">0</span>
                        <p class="text-muted">Pelatih Bersertifikat</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-title" data-aos="fade-up">Tentang UKM Taekwondo UNINDRA</h2>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="pe-lg-4">
                        <h3 class="mb-4">Sejarah dan Visi Kami</h3>
                        <p class="text-muted mb-4">
                            UKM Taekwondo Universitas Indraprasta PGRI didirikan pada tahun 2007 dengan tujuan 
                            mengembangkan bakat mahasiswa dalam bidang olahraga beladiri Taekwondo. Kami berkomitmen 
                            untuk membentuk karakter yang kuat, disiplin, dan sportivitas tinggi.
                        </p>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="feature-icon me-3" style="width: 50px; height: 50px; font-size: 20px;">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Prestasi</h6>
                                        <small class="text-muted">Meraih berbagai penghargaan</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="feature-icon me-3" style="width: 50px; height: 50px; font-size: 20px;">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Komunitas</h6>
                                        <small class="text-muted">Keluarga besar yang solid</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="row g-3">
                        <div class="col-6">
                            <img src="assets/WhatsApp Image 2025-06-17 at 15.28.34_a9968182.jpg" alt="Training" class="gallery-img w-100">
                        </div>
                        <div class="col-6">
                            <img src="assets/WhatsApp Image 2025-06-17 at 15.40.35_88b8aab7.jpg" alt="Competition" class="gallery-img w-100">
                        </div>
                        <div class="col-12">
                            <img src="assets/WhatsApp Image 2025-06-17 at 15.38.17_b3605f08.jpg" alt="Team" class="gallery-img w-100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="programs" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-title" data-aos="fade-up">Program Latihan</h2>
                    <p class="text-muted mb-5" data-aos="fade-up" data-aos-delay="100">
                        Berbagai program latihan yang disesuaikan dengan tingkat kemampuan dan kebutuhan anggota
                    </p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card program-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <h4 class="text-center mb-3">Program Pemula</h4>
                        <p class="text-center text-muted mb-4">
                            Untuk anggota baru yang belum memiliki pengalaman dalam Taekwondo. 
                            Fokus pada teknik dasar dan pembentukan karakter.
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Teknik dasar Taekwondo</li>
                            <li><i class="fas fa-check text-success me-2"></i>Filosofi dan etika</li>
                            <li><i class="fas fa-check text-success me-2"></i>Kondisi fisik dasar</li>
                            <li><i class="fas fa-check text-success me-2"></i>Poomsae tingkat pemula</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card program-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <h4 class="text-center mb-3">Program Menengah</h4>
                        <p class="text-center text-muted mb-4">
                            Untuk anggota yang sudah menguasai teknik dasar dan ingin mengembangkan 
                            kemampuan ke level selanjutnya.
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Teknik kombinasi</li>
                            <li><i class="fas fa-check text-success me-2"></i>Sparring dasar</li>
                            <li><i class="fas fa-check text-success me-2"></i>Poomsae menengah</li>
                            <li><i class="fas fa-check text-success me-2"></i>Persiapan ujian sabuk</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="card program-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h4 class="text-center mb-3">Program Lanjutan</h4>
                        <p class="text-center text-muted mb-4">
                            Untuk anggota berpengalaman yang ingin berkompetisi dan mencapai 
                            prestasi tingkat regional maupun nasional.
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Teknik kompetisi</li>
                            <li><i class="fas fa-check text-success me-2"></i>Sparring lanjutan</li>
                            <li><i class="fas fa-check text-success me-2"></i>Poomsae kompetisi</li>
                            <li><i class="fas fa-check text-success me-2"></i>Mental training</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
<section id="gallery" class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col text-center">
                <h2 class="section-title" data-aos="zoom-in">Galeri Kegiatan</h2>
                <p class="text-muted" data-aos="fade-up" data-aos-delay="100">
                    Dokumentasi kegiatan dan pencapaian UKM Taekwondo UNINDRA
                </p>
            </div>
        </div>

        <div class="row g-4">
            <?php
            $gallery_items = [
                ['src' => 'assets/img/kejuaraan.jpg', 'alt' => ''],
                ['src' => 'assets/img/diklat.jpg', 'alt' => ''],
                ['src' => 'assets/img/latgab.jpg', 'alt' => ''],
                ['src' => 'assets/img/latihan.jpg', 'alt' => ''],
                ['src' => 'assets/img/wardah.jpg', 'alt' => ''],
                ['src' => 'assets/img/silver.jpg', 'alt' => ''],
                ['src' => 'assets/img/sparing.jpg', 'alt' => ''],
                ['src' => 'assets/img/latihan1.jpg', 'alt' => ''],
                ['src' => 'assets/img/divia.jpg', 'alt' => '']
            ]; 

            foreach ($gallery_items as $index => $item) {
                $delay = ($index + 1) * 150;
                echo '
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="' . $delay . '">
                    <div class="gallery-card position-relative overflow-hidden rounded shadow-sm">
                        <img src="' . $item['src'] . '" alt="' . $item['alt'] . '" class="img-fluid w-100 gallery-img">
                        <div class="overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center text-white">
                            <h5 class="m-0">' . $item['alt'] . '</h5>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
</section>



            </div>
        </div>
    </section>
    <!-- Schedule Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-title" data-aos="fade-up">Jadwal Latihan</h2>
                    <p class="text-muted mb-5" data-aos="fade-up" data-aos-delay="100">
                        Jadwal latihan rutin UKM Taekwondo UNINDRA
                    </p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th><i class="fas fa-calendar-day me-2"></i>Hari</th>
                                            <th><i class="fas fa-clock me-2"></i>Waktu</th>
                                            <th><i class="fas fa-layer-group me-2"></i>Tingkat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($jadwal as $item): ?>
                                        <tr>
                                            <td class="fw-bold"><?php echo htmlspecialchars($item['hari']); ?></td>
                                            <td><?php echo htmlspecialchars($item['waktu']); ?></td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo htmlspecialchars($item['tingkat']); ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Lokasi:</strong> Parkiran Rektor Kampus B UNINDRA<br>
                                <strong>Perlengkapan:</strong> Seragam taekwondo (dobok), pelindung, dan peralatan latihan disediakan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-title" data-aos="fade-up">Hubungi Kami</h2>
                    <p class="text-muted mb-5" data-aos="fade-up" data-aos-delay="100">
                        Ada pertanyaan? Jangan ragu untuk menghubungi kami
                    </p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h5>Alamat</h5>
                            <p class="text-muted">
                                <?php echo htmlspecialchars($kontak['alamat']['nama']); ?><br>
                                <?php echo htmlspecialchars($kontak['alamat']['jalan']); ?><br>
                                <?php echo htmlspecialchars($kontak['alamat']['kota']); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h5>Telepon</h5>
                            <p class="text-muted">
                                <strong>Utama:</strong> <?php echo htmlspecialchars($kontak['telepon']['utama']); ?><br>
                                <strong>WhatsApp:</strong> <?php echo htmlspecialchars($kontak['telepon']['wa']); ?><br>
                                <small><?php echo htmlspecialchars($kontak['telepon']['jam']); ?></small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h5>Digital</h5>
                            <p class="text-muted">
                                <strong>Email:</strong><br>
                                <a href="mailto:<?php echo $kontak['digital']['email']; ?>" class="text-decoration-none">
                                    <?php echo htmlspecialchars($kontak['digital']['email']); ?>
                                </a><br>
                                <strong>Instagram:</strong> <?php echo htmlspecialchars($kontak['digital']['instagram']); ?><br>
                                <strong>Facebook:</strong> <?php echo htmlspecialchars($kontak['digital']['facebook']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up" data-aos-delay="400">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-center mb-4">Kirim Pesan</h4>
                            <form action="send_message.php" method="POST">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nama" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" id="nama" name="nama" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="telepon" class="form-label">Nomor Telepon</label>
                                            <input type="tel" class="form-control" id="telepon" name="telepon">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="subjek" class="form-label">Subjek</label>
                                            <select class="form-select" id="subjek" name="subjek" required>
                                                <option value="">Pilih Subjek</option>
                                                <option value="Pendaftaran">Informasi Pendaftaran</option>
                                                <option value="Jadwal">Jadwal Latihan</option>
                                                <option value="Program">Program Latihan</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="pesan" class="form-label">Pesan</label>
                                    <textarea class="form-control" id="pesan" name="pesan" rows="5" required></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="Picture1.png" alt="Logo UKM Taekwondo" style="height:50px; width:auto; margin-right: 15px;">
                        <h5 class="mb-0">UKM Taekwondo UNINDRA</h5>
                    </div>
                    <p class="text-muted">
                        Unit Kegiatan Mahasiswa Taekwondo Universitas Indraprasta PGRI. 
                        Membentuk karakter, membangun prestasi, mengharumkan UNINDRA.
                    </p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6>Menu Utama</h6>
                    <ul class="list-unstyled">
                        <li><a href="#home" class="text-muted text-decoration-none">Beranda</a></li>
                        <li><a href="#about" class="text-muted text-decoration-none">Tentang</a></li>
                        <li><a href="#programs" class="text-muted text-decoration-none">Program</a></li>
                        <li><a href="#gallery" class="text-muted text-decoration-none">Galeri</a></li>
                        <li><a href="#contact" class="text-muted text-decoration-none">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6>Layanan</h6>
                    <ul class="list-unstyled">
                        <li><a href="daftar.php" class="text-muted text-decoration-none">Pendaftaran</a></li>
                        <li><a href="cek_status.php" class="text-muted text-decoration-none">Cek Status</a></li>
                        <li><a href="admin/login.php" class="text-muted text-decoration-none">Login Admin</a></li>
                        <li><a href="jadwal.php" class="text-muted text-decoration-none">Jadwal Lengkap</a></li>
                        <li><a href="prestasi.php" class="text-muted text-decoration-none">Prestasi</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6>Kontak Info</h6>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <?php echo htmlspecialchars($kontak['alamat']['jalan']); ?>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2"></i>
                            <?php echo htmlspecialchars($kontak['telepon']['utama']); ?>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            <?php echo htmlspecialchars($kontak['digital']['email']); ?>
                        </li>
                        <li>
                            <i class="fas fa-clock me-2"></i>
                            <?php echo htmlspecialchars($kontak['telepon']['jam']); ?>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        &copy; <?php echo date('Y'); ?> UKM Taekwondo UNINDRA. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">
                        Developed with <i class="fas fa-heart text-danger"></i> for UNINDRA
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/<?php echo str_replace(['+', '-', ' '], '', $kontak['telepon']['wa']); ?>?text=Halo,%20saya%20ingin%20bertanya%20tentang%20UKM%20Taekwondo%20UNINDRA" 
       class="floating-btn" target="_blank" title="Chat WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true
        });

        // Counter Animation
        function animateCounter() {
            const counters = document.querySelectorAll('[data-count]');
            
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count'));
                const increment = target / 100;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = Math.floor(current);
                }, 20);
            });
        }

        // Trigger counter animation when stats section is visible
        const statsSection = document.querySelector('.stats-section');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter();
                    observer.unobserve(entry.target);
                }
            });
        });

        if (statsSection) {
            observer.observe(statsSection);
        }

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const offsetTop = target.offsetTop - 70; // Account for fixed navbar
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Navbar background change on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.backgroundColor = 'rgba(33, 37, 41, 0.95)';
                navbar.style.backdropFilter = 'blur(10px)';
            } else {
                navbar.style.backgroundColor = '';
                navbar.style.backdropFilter = '';
            }
        });

        // Form validation and submission
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic form validation
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });
            
            if (isValid) {
                // Show success message (in real implementation, submit to server)
                alert('Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
                this.reset();
                
                // Remove validation classes
                const inputs = this.querySelectorAll('.form-control, .form-select');
                inputs.forEach(input => {
                    input.classList.remove('is-valid', 'is-invalid');
                });
            } else {
                alert('Mohon lengkapi semua field yang wajib diisi.');
            }
        });

        // Dynamic year update
        document.addEventListener('DOMContentLoaded', function() {
            const currentYear = new Date().getFullYear();
            const yearElements = document.querySelectorAll('[data-year]');
            yearElements.forEach(element => {
                element.textContent = currentYear;
            });
        });

        // Image lazy loading fallback
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('error', function() {
                    this.src = 'https://via.placeholder.com/400x300/cccccc/666666?text=Image+Not+Found';
                });
            });
        });

        // Back to top functionality
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > 500) {
                if (!document.querySelector('.back-to-top')) {
                    const backToTop = document.createElement('button');
                    backToTop.className = 'back-to-top';
                    backToTop.innerHTML = '<i class="fas fa-arrow-up"></i>';
                    backToTop.style.cssText = `
                        position: fixed;
                        bottom: 100px;
                        right: 30px;
                        z-index: 999;
                        width: 50px;
                        height: 50px;
                        border-radius: 50%;
                        background: linear-gradient(135deg, var(--primary-red), var(--primary-blue));
                        color: white;
                        border: none;
                        cursor: pointer;
                        font-size: 18px;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                        transition: all 0.3s ease;
                    `;
                    
                    backToTop.addEventListener('click', function() {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    });
                    
                    document.body.appendChild(backToTop);
                }
            } else {
                const backToTop = document.querySelector('.back-to-top');
                if (backToTop) {
                    backToTop.remove();
                }
            }
        });
    </script>

    <!-- Custom Styles for responsiveness -->
    <style>
        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }
            
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .feature-icon {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
            
            .floating-btn {
                width: 50px;
                height: 50px;
                font-size: 20px;
                bottom: 20px;
                right: 20px;
            }
        }
        
        @media (max-width: 576px) {
            .hero-section h1 {
                font-size: 2rem;
            }
            
            .btn {
                padding: 10px 20px;
                font-size: 14px;
            }
            
            .card {
                margin-bottom: 20px;
            }
        }
    </style>
</body>
</html>