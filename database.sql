-- Database: ukm_taekwondo
CREATE DATABASE IF NOT EXISTS ukm_taekwondo;
USE ukm_taekwondo;

-- Tabel anggota
CREATE TABLE anggota (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    tempat_lahir VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
    alamat TEXT NOT NULL,
    no_telepon VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    program_studi VARCHAR(50) NOT NULL,
    fakultas VARCHAR(50) NOT NULL,
    semester INT NOT NULL,
    tingkat_sabuk ENUM('Putih', 'Kuning', 'Hijau', 'Biru', 'Coklat', 'Hitam') DEFAULT 'Putih',
    pengalaman_taekwondo TEXT,
    motivasi TEXT NOT NULL,
    foto VARCHAR(255),
    status_pendaftaran ENUM('Pending', 'Diterima', 'Ditolak') DEFAULT 'Pending',
    tanggal_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel admin
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert admin default
INSERT INTO admin (username, password, nama, email) 
VALUES ('admin', MD5('admin123'), 'Administrator', 'admin@taekwondo.unindra.ac.id');

-- Tabel kegiatan (opsional untuk pengembangan selanjutnya)
CREATE TABLE kegiatan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_kegiatan VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    tanggal_kegiatan DATE NOT NULL,
    tempat VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);