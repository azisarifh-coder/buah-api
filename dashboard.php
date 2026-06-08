<?php
require 'config.php';
cekLogin();
$user_id = $_SESSION['user_id'];
$nama    = $_SESSION['nama'];
$total   = $conn->query("SELECT COUNT(*) as t FROM riwayat WHERE user_id=$user_id")->fetch_assoc()['t'];
$segar   = $conn->query("SELECT COUNT(*) as t FROM riwayat WHERE user_id=$user_id AND kondisi='fresh'")->fetch_assoc()['t'];
$busuk   = $conn->query("SELECT COUNT(*) as t FROM riwayat WHERE user_id=$user_id AND kondisi='rotten'")->fetch_assoc()['t'];
$pct     = $total > 0 ? round(($segar/$total)*100) : 0;
$first   = strtoupper(substr($nama, 0, 1));
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Instafruit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background: var(--bg); }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: linear-gradient(180deg, #1B5E20 0%, #2E7D32 60%, #388E3C 100%);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0,0,0,0.12);
            transition: all 0.3s ease;
        }
        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }
        .sidebar-brand h5 {
            color: white;
            font-weight: 800;
            font-size: 1rem;
            margin: 0;
        }
        .sidebar-brand p {
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
            margin: 2px 0 0;
        }
        .sidebar-user {
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.08);
            margin: 12px;
            border-radius: 12px;
        }
        .sidebar-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 1rem; color: white;
            border: 2px solid rgba(255,255,255,0.35);
            flex-shrink: 0;
        }
        .sidebar-user-name {
            color: white;
            font-weight: 700;
            font-size: 0.85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-user-role {
            color: rgba(255,255,255,0.55);
            font-size: 0.73rem;
        }
        .sidebar-nav { padding: 8px 12px; flex: 1; }
        .sidebar-label {
            color: rgba(255,255,255,0.4);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 12px 8px 6px;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 12px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.2s;
            margin-bottom: 2px;
        }
        .sidebar-link i { font-size: 1.1rem; width: 20px; text-align: center; }
        .sidebar-link:hover { background: rgba(255,255,255,0.12); color: white; }
        .sidebar-link.active {
            background: rgba(255,255,255,0.18);
            color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .sidebar-link.logout { color: #FFCDD2; }
        .sidebar-link.logout:hover { background: rgba(239,68,68,0.2); color: #FF8A80; }
        .sidebar-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 8px 12px;
        }
        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.35);
            font-size: 0.72rem;
            text-align: center;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: 240px;
            flex: 1;
            padding: 28px;
            min-height: 100vh;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .topbar h4 {
            font-weight: 800;
            color: var(--primary);
            margin: 0;
            font-size: 1.4rem;
        }
        .topbar-date {
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        .greeting-card {
            background: linear-gradient(135deg, #1B5E20, #43A047);
            border-radius: 20px;
            padding: 28px 32px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .greeting-card::after {
            content: '🍎🍌🍊';
            position: absolute;
            right: 28px; top: 50%;
            transform: translateY(-50%);
            font-size: 56px;
            opacity: 0.15;
            letter-spacing: 4px;
        }
        .menu-card {
            border-radius: 16px;
            padding: 28px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s;
            text-decoration: none;
            display: block;
            background: white;
            border: 2px solid transparent;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }
        .menu-card:hover {
            border-color: #81C784;
            transform: translateY(-5px);
            box-shadow: 0 10px 28px rgba(0,0,0,0.1);
        }
        .menu-icon {
            width: 64px; height: 64px;
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px;
            margin: 0 auto 14px;
            transition: all 0.25s;
        }
        .menu-card:hover .menu-icon { transform: scale(1.1) rotate(-5deg); }

        /* ── MOBILE ── */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 16px; left: 16px;
            z-index: 200;
            background: #1B5E20;
            border: none;
            color: white;
            width: 40px; height: 40px;
            border-radius: 10px;
            font-size: 1.2rem;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 16px; padding-top: 64px; }
            .sidebar-toggle { display: flex; align-items: center; justify-content: center; }
            .sidebar-overlay {
                display: none;
                position: fixed; inset: 0;
                background: rgba(0,0,0,0.4);
                z-index: 99;
            }
            .sidebar-overlay.show { display: block; }
        }
    </style>
</head>
<body>

<button class="sidebar-toggle" onclick="toggleSidebar()">
    <i class="bi bi-list"></i>
</button>
<div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <h5>🍎 Instafruit</h5>
        <p>AI Freshness Detection</p>
    </div>

    <div class="sidebar-user">
        <div class="sidebar-avatar"><?= $first ?></div>
        <div>
            <div class="sidebar-user-name"><?= htmlspecialchars($nama) ?></div>
            <div class="sidebar-user-role">Pengguna</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-label">Menu</div>
        <a href="dashboard.php" class="sidebar-link <?= $current === 'dashboard.php' ? 'active' : '' ?>">
            <i class="bi bi-house-fill"></i> Dashboard
        </a>
        <a href="deteksi.php" class="sidebar-link <?= $current === 'deteksi.php' ? 'active' : '' ?>">
            <i class="bi bi-search"></i> Deteksi Buah
        </a>
        <a href="riwayat.php" class="sidebar-link <?= $current === 'riwayat.php' ? 'active' : '' ?>">
            <i class="bi bi-clock-history"></i> Riwayat
        </a>

        <hr class="sidebar-divider">
        <div class="sidebar-label">Lainnya</div>

        <a href="tentang.php" class="sidebar-link <?= $current === 'tentang.php' ? 'active' : '' ?>">
            <i class="bi bi-info-circle"></i> Tentang
        </a>

        <hr class="sidebar-divider">

        <a href="logout.php" class="sidebar-link logout">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </nav>

    <!-- FIX: Tombol lang yang rusak sudah dihapus -->

    <div class="sidebar-footer">
        © 2026 Instafruit
    </div>
</aside>

<main class="main-content page-wrapper">

    <div class="topbar animate-fade-up">
        <h4>Dashboard</h4>
        <div class="topbar-date">
            <i class="bi bi-calendar3 me-1"></i><?= date('d F Y') ?>
        </div>
    </div>

    <div class="greeting-card animate-fade-up">
        <p style="opacity:0.8;font-size:0.9rem;margin-bottom:4px">Selamat datang kembali 👋</p>
        <h3 style="font-weight:800;margin-bottom:4px"><?= htmlspecialchars($nama) ?></h3>
        <p style="opacity:0.75;font-size:0.9rem;margin:0">Ayo deteksi buah hari ini!</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3 animate-fade-up delay-1">
            <div class="stat-card blue">
                <div class="stat-icon-wrap">📊</div>
                <div class="stat-num"><?= $total ?></div>
                <div class="stat-label">Total Deteksi</div>
            </div>
        </div>
        <div class="col-6 col-md-3 animate-fade-up delay-2">
            <div class="stat-card green">
                <div class="stat-icon-wrap">✅</div>
                <div class="stat-num"><?= $segar ?></div>
                <div class="stat-label">Buah Segar</div>
            </div>
        </div>
        <div class="col-6 col-md-3 animate-fade-up delay-3">
            <div class="stat-card red">
                <div class="stat-icon-wrap">🍂</div>
                <div class="stat-num"><?= $busuk ?></div>
                <div class="stat-label">Buah Busuk</div>
            </div>
        </div>
        <div class="col-6 col-md-3 animate-fade-up delay-4">
            <div class="stat-card orange">
                <div class="stat-icon-wrap">🎯</div>
                <div class="stat-num"><?= $pct ?>%</div>
                <div class="stat-label">Tingkat Segar</div>
            </div>
        </div>
    </div>

    <h6 style="font-weight:700;color:var(--primary);margin-bottom:16px">Menu Utama</h6>
    <div class="row g-3">
        <div class="col-6 col-md-4 animate-fade-up delay-1">
            <a href="deteksi.php" class="menu-card">
                <div class="menu-icon" style="background:#E8F5E9">🔍</div>
                <div style="font-weight:700;color:var(--primary);font-size:0.95rem">Deteksi Buah</div>
                <div style="font-size:0.8rem;color:var(--text-muted);margin-top:4px">Upload atau kamera</div>
            </a>
        </div>
        <div class="col-6 col-md-4 animate-fade-up delay-2">
            <a href="riwayat.php" class="menu-card">
                <div class="menu-icon" style="background:#E3F2FD">📋</div>
                <div style="font-weight:700;color:var(--primary);font-size:0.95rem">Riwayat</div>
                <div style="font-size:0.8rem;color:var(--text-muted);margin-top:4px">Lihat histori</div>
            </a>
        </div>
        <div class="col-6 col-md-4 animate-fade-up delay-3">
            <a href="feedback.php" class="menu-card">
                <div class="menu-icon" style="background:#FFF8E1">💬</div>
                <div style="font-weight:700;color:var(--primary);font-size:0.95rem">Umpan Balik</div>
                <div style="font-size:0.8rem;color:var(--text-muted);margin-top:4px">Beri masukan</div>
            </a>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('show');
}
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