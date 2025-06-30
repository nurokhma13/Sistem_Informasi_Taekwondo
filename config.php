<?php
// config.php - Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ukm_taekwondo";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8");
} catch(PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Fungsi untuk upload foto
function uploadFoto($file) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = pathinfo($file["name"], PATHINFO_EXTENSION);
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Cek apakah file adalah gambar
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        return false;
    }
    
    // Cek ukuran file (maksimal 2MB)
    if ($file["size"] > 2000000) {
        return false;
    }
    
    // Format file yang diizinkan
    if(!in_array(strtolower($file_extension), ["jpg", "jpeg", "png", "gif"])) {
        return false;
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $new_filename;
    } else {
        return false;
    }
}

// Fungsi untuk format tanggal Indonesia
function formatTanggalIndonesia($tanggal) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

// Session start
session_start();
?>