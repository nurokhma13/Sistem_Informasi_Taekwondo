<?php
require_once '../config.php';

// Hapus semua session
session_destroy();

// Redirect ke halaman login
header('Location: login.php?message=logout');
exit;
?>