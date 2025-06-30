<?php
require_once '../config.php';

$error_message = '';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

// Proses login
if ($_POST && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = md5(trim($_POST['password']));
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
        $stmt->execute([$username, $password]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['nama'];
            $_SESSION['admin_username'] = $admin['username'];
            
            header('Location: dashboard.php');
            exit;
        } else {
            $error_message = 'Username atau password salah!';
        }
    } catch (PDOException $e) {
        $error_message = 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - UKM Taekwondo UNINDRA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg,rgb(98, 10, 10) 0%,rgb(6, 13, 136) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg,rgb(14, 53, 123) 0%,rgb(159, 16, 16) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .btn-login {
            background: linear-gradient(135deg,rgb(0, 0, 0) 0%,rgb(129, 15, 15) 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-card">
                    <div class="login-header">
                       <h3><i class=""></i>Admin Login</h3>
                        <p class="mb-0">UKM Taekwondo UNINDRA</p>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i><?= $error_message ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" name="login" class="btn btn-primary btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="../index.php" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda
                            </a>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>