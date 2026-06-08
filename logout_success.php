<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Instafruit</title>
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

        /* Dekorasi background sama seperti login */
        body::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            top: -200px; right: -150px;
        }
        body::after {
            content: '';
            position: absolute;
            width: 350px; height: 350px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
            bottom: -100px; left: -100px;
        }

        /* Card sama persis dengan login */
        .logout-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 28px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.35);
            animation: fadeInUp 0.5s ease both;
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .wave-emoji {
            font-size: 64px;
            animation: wave 1.5s ease infinite;
            display: inline-block;
            margin-bottom: 8px;
        }
        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            25%       { transform: rotate(20deg); }
            75%       { transform: rotate(-10deg); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logout-title {
            font-weight: 800;
            color: #ffffff;
            font-size: 1.75rem;
            margin: 12px 0 8px;
        }
        .logout-subtitle {
            color: rgba(255,255,255,0.65);
            font-size: 0.9rem;
            margin-bottom: 32px;
            line-height: 1.6;
        }
        .logout-subtitle strong {
            color: rgba(255,255,255,0.9);
        }

        /* Tombol utama — sama dengan login */
        .btn-logout-main {
            background: #52b788;
            border: none;
            border-radius: 50px;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            padding: 14px 24px;
            width: 100%;
            display: block;
            text-align: center;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(82,183,136,0.4);
            transition: all 0.25s;
            text-decoration: none;
            letter-spacing: 0.2px;
        }
        .btn-logout-main:hover {
            background: #40916c;
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(82,183,136,0.5);
            color: #fff;
        }

        /* Divider & link bawah */
        .logout-footer {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid rgba(255,255,255,0.15);
        }
        .logout-footer p {
            color: rgba(255,255,255,0.5);
            font-size: 0.875rem;
            margin: 0;
        }
        .logout-footer a {
            color: #74c69d;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s;
        }
        .logout-footer a:hover { color: #52b788; }
        .logout-secure {
            color: rgba(255,255,255,0.3);
            font-size: 0.8rem;
            margin-top: 10px !important;
        }
        .logout-secure i { margin-right: 4px; }
    </style>
</head>
<body>

<div class="logout-card">
    <div class="wave-emoji">👋</div>
    <h3 class="logout-title">Sampai Jumpa!</h3>
    <p class="logout-subtitle">
        Kamu telah berhasil logout dari <strong>Instafruit</strong>
    </p>

    <a href="login.php" class="btn-logout-main ripple">
        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Lagi
    </a>

    <div style="margin-top:12px">
        <a href="register.php" class="btn-logout-main ripple"
           style="background:rgba(255,255,255,0.12);box-shadow:none;border:1.5px solid rgba(255,255,255,0.25)">
            <i class="bi bi-person-plus me-2"></i>Buat Akun Baru
        </a>
    </div>

    <div class="logout-footer">
        <p class="logout-secure">
            <i class="bi bi-shield-check"></i>Sesi kamu telah berakhir dengan aman
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>