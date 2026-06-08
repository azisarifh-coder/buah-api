<?php
require 'config.php';
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama']    = $user['nama'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Instafruit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1B5E20 0%, #2E7D32 40%, #43A047 100%);
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            top: -200px; right: -200px;
        }
        body::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            bottom: -150px; left: -150px;
        }
        .login-card {
            background: white;
            border-radius: 24px;
            padding: 48px 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.2);
            animation: fadeInUp 0.5s ease both;
            position: relative;
            z-index: 1;
        }
        .fruit-banner {
            font-size: 42px;
            margin-bottom: 8px;
            animation: fadeInUp 0.4s ease both;
        }
        .error-box {
            background: #FFEBEE;
            border: 1px solid #FFCDD2;
            border-radius: 10px;
            padding: 12px 16px;
            color: #C62828;
            font-size: 0.9rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>
<div class="login-card">
    <div class="text-center mb-4">
        <div class="fruit-banner">🍎🍌🍊</div>
        <h2 style="color:#1B5E20; font-weight:800; font-size:1.6rem">Instafruit</h2>
        <p style="color:#6B7280; font-size:0.9rem">Masuk ke akun kamu untuk melanjutkan</p>
    </div>

    <?php if ($error): ?>
    <div class="error-box">
        <i class="bi bi-exclamation-circle-fill"></i> <?= $error ?>
    </div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group-modern">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" name="email" class="input-modern" placeholder="Email kamu" required>
        </div>
        <div class="input-group-modern">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" name="password" class="input-modern" placeholder="Password kamu" required>
        </div>
        <button type="submit" class="btn-primary-custom ripple w-100 mt-2">
            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
        </button>
    </form>

    <p class="text-center mt-4" style="color:#6B7280; font-size:0.9rem">
        Belum punya akun?
        <a href="register.php" style="color:#2E7D32; font-weight:700; text-decoration:none">Daftar sekarang →</a>
    </p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>