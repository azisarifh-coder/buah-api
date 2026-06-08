<?php require 'config.php'; cekLogin(); $nama = $_SESSION['nama']; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang - Instafruit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        body { background: var(--bg); }
        .icon-box { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28px; }
        .badge-tech { background: #e8f5e9; color: #2e7d32; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; display: inline-block; margin: 4px; }
        .info-card { background: white; border-radius: var(--radius); padding: 28px; box-shadow: var(--shadow); border: none; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark">
    <a href="dashboard.php" class="navbar-brand mb-0">🍎 Instafruit</a>
    <div class="d-flex align-items-center gap-2">
        <a href="tutorial.php" class="nav-link-custom"><i class="bi bi-book"></i> Tutorial</a>
        <a href="tentang.php" class="nav-link-custom active"><i class="bi bi-info-circle"></i> Tentang</a>
        <span class="nav-link-custom"><i class="bi bi-person-circle"></i> <?= $nama ?></span>
        <a href="logout.php" class="btn btn-sm ripple"
           style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);color:white;border-radius:8px;padding:6px 12px;font-weight:600">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>
</nav>

<div class="container py-4 page-wrapper">
    <div class="d-flex align-items-center mb-4">
        <a href="dashboard.php" class="btn-outline-custom me-3">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <h4 style="font-weight:800;color:var(--primary);margin:0">Tentang Aplikasi</h4>
    </div>

    <!-- Hero -->
    <div class="info-card text-center mb-4 animate-fade-up" style="background:linear-gradient(135deg,#1B5E20,#43A047)">
        <div style="font-size:64px">🍎🍌🍊</div>
        <h2 style="font-weight:800;color:white;margin-top:12px">Instafruit</h2>
        <p style="color:rgba(255,255,255,0.75);margin-bottom:12px">Aplikasi Kecerdasan Buatan untuk Mendeteksi Kondisi Buah</p>
        <span style="background:white;color:#2e7d32;padding:6px 18px;border-radius:50px;font-weight:700;font-size:0.9rem">Versi 1.0 · 2026</span>
    </div>

    <div class="row g-4">
        <!-- Tentang -->
        <div class="col-12 animate-fade-up">
            <div class="info-card">
                <h5 style="font-weight:700;color:var(--primary);margin-bottom:12px"><i class="bi bi-info-circle me-2"></i>Tentang Aplikasi</h5>
                <p style="color:var(--text-muted);margin-bottom:12px">
                    <strong>Instafruit</strong> adalah aplikasi berbasis kecerdasan buatan (AI) yang dirancang untuk membantu
                    pengguna mendeteksi kondisi buah secara otomatis — apakah buah tersebut dalam keadaan <strong>segar</strong> atau
                    <strong>busuk</strong> — hanya dengan menggunakan foto atau kamera secara real-time.
                </p>
                <p style="color:var(--text-muted);margin:0">
                    Aplikasi ini dikembangkan sebagai solusi inovatif untuk mengurangi pemborosan pangan akibat buah busuk yang
                    tidak terdeteksi, sekaligus membantu pedagang dan konsumen dalam memilih buah yang layak konsumsi dengan cepat dan akurat.
                </p>
            </div>
        </div>

        <!-- Manfaat -->
        <div class="col-md-6 animate-fade-up delay-1">
            <div class="info-card h-100">
                <h5 style="font-weight:700;color:var(--primary);margin-bottom:16px"><i class="bi bi-stars me-2"></i>Manfaat Aplikasi</h5>
                <ul class="list-unstyled">
                    <?php
                    $manfaat = [
                        ['✅','Deteksi Cepat & Akurat','Mendeteksi kondisi buah dalam hitungan detik dengan akurasi hingga 99.95%'],
                        ['🛒','Membantu Pedagang & Konsumen','Memudahkan pemilihan buah yang layak jual dan layak konsumsi'],
                        ['♻️','Kurangi Food Waste','Membantu mengurangi pemborosan pangan akibat buah busuk tidak terdeteksi'],
                        ['📱','Bisa Diakses dari Mana Saja','Tersedia di HP dan PC, tidak perlu install aplikasi tambahan'],
                    ];
                    foreach ($manfaat as $m): ?>
                    <li class="mb-3 d-flex align-items-start">
                        <div class="icon-box me-3" style="background:#e8f5e9;flex-shrink:0"><?= $m[0] ?></div>
                        <div>
                            <strong style="color:var(--text)"><?= $m[1] ?></strong>
                            <p style="color:var(--text-muted);font-size:0.85rem;margin:2px 0 0"><?= $m[2] ?></p>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Fitur -->
        <div class="col-md-6 animate-fade-up delay-2">
            <div class="info-card h-100">
                <h5 style="font-weight:700;color:var(--primary);margin-bottom:16px"><i class="bi bi-grid me-2"></i>Fitur Utama</h5>
                <ul class="list-unstyled">
                    <?php
                    $fitur = [
                        ['📷','Upload Foto','Upload foto buah dari galeri untuk dianalisis AI'],
                        ['📹','Kamera Real-Time','Deteksi langsung menggunakan kamera tanpa perlu ambil foto'],
                        ['📊','Dashboard Statistik','Pantau total deteksi, buah segar, dan buah busuk'],
                        ['📋','Riwayat Deteksi','Semua hasil deteksi tersimpan otomatis per akun'],
                    ];
                    foreach ($fitur as $f): ?>
                    <li class="mb-3 d-flex align-items-start">
                        <div class="icon-box me-3" style="background:#e8f5e9;flex-shrink:0"><?= $f[0] ?></div>
                        <div>
                            <strong style="color:var(--text)"><?= $f[1] ?></strong>
                            <p style="color:var(--text-muted);font-size:0.85rem;margin:2px 0 0"><?= $f[2] ?></p>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Buah yang Didukung -->
        <div class="col-12 animate-fade-up delay-3">
            <div class="info-card">
                <h5 style="font-weight:700;color:var(--primary);margin-bottom:16px"><i class="bi bi-basket me-2"></i>Buah yang Didukung</h5>
                <div class="row g-3 text-center">
                    <?php foreach ([['🍎','Apel'],['🍌','Pisang'],['🍊','Jeruk']] as $b): ?>
                    <div class="col-4">
                        <div style="background:#e8f5e9;border-radius:12px;padding:20px">
                            <div style="font-size:48px"><?= $b[0] ?></div>
                            <h6 style="font-weight:700;margin:8px 0 4px"><?= $b[1] ?></h6>
                            <small style="color:var(--text-muted)">Fresh & Rotten</small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Teknologi -->
        <div class="col-12 animate-fade-up delay-4">
            <div class="info-card">
                <h5 style="font-weight:700;color:var(--primary);margin-bottom:16px"><i class="bi bi-cpu me-2"></i>Teknologi yang Digunakan</h5>
                <div>
                    <?php foreach (['🐍 Python','🔥 PyTorch','⚗️ Flask','🎯 Roboflow','🐘 PHP','🗄️ MySQL','🎨 Bootstrap 5','🤖 MobileNetV2','☁️ Railway'] as $tech): ?>
                    <span class="badge-tech"><?= $tech ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('a[href]').forEach(link => {
    link.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href && !href.startsWith('#') && !href.startsWith('http')) {
            e.preventDefault();
            document.querySelector('.page-wrapper').style.animation = 'fadeInUp 0.3s ease reverse both';
            setTimeout(() => window.location.href = href, 250);
        }
    });
});
</script>
</body>
</html>