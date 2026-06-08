<?php
require 'config.php';
cekLogin();
$user_id = $_SESSION['user_id'];
$nama    = $_SESSION['nama'];
$riwayat = $conn->query("SELECT * FROM riwayat WHERE user_id=$user_id ORDER BY created_at DESC");
$fruitEmoji = ['apple'=>'🍎','banana'=>'🍌','orange'=>'🍊'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat - Instafruit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        body { background: var(--bg); }
        .riwayat-item {
            background: white; border-radius: var(--radius); padding: 16px;
            display: flex; align-items: center; gap: 16px;
            transition: var(--transition); border: 1.5px solid transparent;
            box-shadow: var(--shadow);
        }
        .riwayat-item:hover { border-color: var(--accent-light); transform: translateY(-2px); box-shadow: var(--shadow-hover); }
        .foto-thumb { width:72px; height:72px; border-radius:12px; object-fit:cover; cursor:pointer; transition:var(--transition); flex-shrink:0; }
        .foto-thumb:hover { transform: scale(1.08); }
        .emoji-thumb { width:72px; height:72px; border-radius:12px; background:var(--success-light); display:flex; align-items:center; justify-content:center; font-size:36px; flex-shrink:0; }
        .badge-fresh  { background:#E8F5E9; color:#1B5E20; padding:4px 12px; border-radius:50px; font-size:0.8rem; font-weight:700; }
        .badge-rotten { background:#FFEBEE; color:#B71C1C; padding:4px 12px; border-radius:50px; font-size:0.8rem; font-weight:700; }
        .modal-content { border-radius: var(--radius); border: none; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark">
    <a href="dashboard.php" class="navbar-brand mb-0">🍎 Instafruit</a>
    <div class="d-flex align-items-center gap-2">
        <a href="tentang.php" class="nav-link-custom"><i class="bi bi-info-circle"></i> Tentang</a>
        <span class="nav-link-custom"><i class="bi bi-person-circle"></i> <?= $nama ?></span>
        <a href="tutorial.php" class="nav-link-custom"><i class="bi bi-book"></i> Tutorial</a>
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
        <h4 style="font-weight:800;color:var(--primary);margin:0">Riwayat Deteksi</h4>
    </div>

    <?php if ($riwayat->num_rows == 0): ?>
    <div class="card-modern p-5 text-center animate-fade-up">
        <div style="font-size:64px;margin-bottom:16px">📭</div>
        <h5 style="font-weight:700;color:var(--primary)">Belum ada riwayat</h5>
        <p style="color:var(--text-muted)">Mulai deteksi buah pertamamu!</p>
        <a href="deteksi.php" class="btn-primary-custom ripple" style="display:inline-block;margin-top:8px;width:auto;padding:12px 28px!important">
            <i class="bi bi-search me-2"></i>Deteksi Sekarang
        </a>
    </div>
    <?php else: ?>
    <div class="d-flex flex-column gap-3">
    <?php $no = 0; while ($row = $riwayat->fetch_assoc()): $no++;
        $labelDb = strtolower($row['filename'] ?? '');
        $emoji = '🍎';
        foreach ($fruitEmoji as $key => $em) {
            if (strpos($labelDb, $key) !== false) { $emoji = $em; break; }
        }
        $isFresh = $row['kondisi'] === 'fresh';
        $hasPhoto = !empty($row['foto_base64']);
    ?>
    <div class="riwayat-item animate-fade-up" style="animation-delay:<?= $no * 0.05 ?>s">
        <?php if ($hasPhoto): ?>
            <img src="<?= $row['foto_base64'] ?>"
                 class="foto-thumb"
                 onclick="lihatFoto('<?= htmlspecialchars($row['foto_base64'], ENT_QUOTES) ?>')"
                 alt="Foto">
        <?php else: ?>
            <div class="emoji-thumb"><?= $emoji ?></div>
        <?php endif; ?>
        <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                <span class="<?= $isFresh ? 'badge-fresh' : 'badge-rotten' ?>">
                    <?= $isFresh ? '✅ Segar' : '❌ Busuk' ?>
                </span>
                <span style="background:#F3F4F6;color:var(--text-muted);padding:4px 12px;border-radius:50px;font-size:0.8rem;font-weight:600">
                    <?= htmlspecialchars($row['confidence']) ?>
                </span>
            </div>
            <div style="font-weight:600;font-size:0.9rem;color:var(--text)"><?= htmlspecialchars($row['filename']) ?></div>
            <div style="font-size:0.8rem;color:#9CA3AF;margin-top:2px">
                <i class="bi bi-clock me-1"></i><?= date('d M Y · H:i', strtotime($row['created_at'])) ?>
            </div>
        </div>
        <button onclick="hapusRiwayat(<?= $row['id'] ?>, this)"
            style="background:none;border:none;color:#EF4444;padding:8px;border-radius:8px;transition:all 0.2s;cursor:pointer"
            onmouseover="this.style.background='#FEE2E2'"
            onmouseout="this.style.background='none'">
            <i class="bi bi-trash3" style="font-size:1.1rem"></i>
        </button>
    </div>
    <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="fotoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 style="font-weight:700;margin:0">Foto Deteksi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <img id="modalFoto" src="" style="width:100%;border-radius:12px" alt="Foto">
        </div>
    </div>
</div>

<script>
function lihatFoto(src) {
    document.getElementById('modalFoto').src = src;
    new bootstrap.Modal(document.getElementById('fotoModal')).show();
}
function hapusRiwayat(id, btn) {
    if (!confirm('Hapus riwayat ini?')) return;
    const item = btn.closest('.riwayat-item');
    item.style.opacity = '0.5';
    fetch('hapus_riwayat.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + id
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'ok') {
            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '0';
            item.style.transform = 'translateX(100%)';
            setTimeout(() => item.remove(), 300);
        } else {
            item.style.opacity = '1';
            alert('Gagal: ' + (data.message || ''));
        }
    })
    .catch(() => {
        item.style.opacity = '1';
        alert('Gagal menghapus!');
    });
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>