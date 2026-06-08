<?php error_reporting(0);
ini_set('display_errors', 0);
require 'config.php'; cekLogin(); $nama = $_SESSION['nama']; $user_id = $_SESSION['user_id'];

$success = false; $error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul    = trim($_POST['judul'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $pesan    = trim($_POST['pesan'] ?? '');
    $rating   = intval($_POST['rating'] ?? 0);
    if (empty($judul) || empty($pesan) || $rating == 0) {
        $error = "Mohon isi semua field!";
    } else {
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, judul, kategori, pesan, rating) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("isssi", $user_id, $judul, $kategori, $pesan, $rating);
            $success = $stmt->execute();
            if (!$success) $error = "Gagal mengirim feedback: " . $stmt->error;
        } else {
            $error = "Tabel feedback belum tersedia. Silakan buat tabel terlebih dahulu.";
        }
    }
}

// FIX: gunakan prepared statement & tangani error tabel belum ada
$myFeedback = null;
$feedbackResult = $conn->query("SELECT * FROM feedback WHERE user_id=$user_id ORDER BY created_at DESC");
if ($feedbackResult !== false) {
    $myFeedback = $feedbackResult;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umpan Balik - Instafruit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        body { background: var(--bg); }
        .star-rating { display: flex; gap: 8px; flex-direction: row-reverse; justify-content: flex-end; }
        .star-rating input { display: none; }
        .star-rating label { font-size: 32px; cursor: pointer; color: #D1D5DB; transition: var(--transition); }
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input:checked ~ label { color: #F59E0B; }
        .feedback-item { background: white; border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); transition: var(--transition); }
        .feedback-item:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover); }
        .star-display { color: #F59E0B; font-size: 1rem; }
        .kategori-badge { padding: 4px 12px; border-radius: 50px; font-size: 0.78rem; font-weight: 600; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark">
    <a href="dashboard.php" class="navbar-brand mb-0">🍎 Instafruit</a>
    <div class="d-flex align-items-center gap-2">
        <a href="tutorial.php" class="nav-link-custom"><i class="bi bi-book"></i> Tutorial</a>
        <a href="tentang.php" class="nav-link-custom"><i class="bi bi-info-circle"></i> Tentang</a>
        <span class="nav-link-custom"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($nama) ?></span>
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
        <h4 style="font-weight:800;color:var(--primary);margin:0">Umpan Balik</h4>
    </div>

    <div class="card-modern p-4 mb-4 animate-fade-up" style="background:linear-gradient(135deg,#1B5E20,#43A047)">
        <div class="row align-items-center">
            <div class="col-8">
                <h5 style="color:white;font-weight:800;margin-bottom:6px">Bantu Kami Berkembang!</h5>
                <p style="color:rgba(255,255,255,0.8);font-size:0.875rem;margin:0">
                    Masukan kamu sangat berarti untuk meningkatkan kualitas aplikasi ini.
                </p>
            </div>
            <div class="col-4 text-end" style="font-size:52px">💬</div>
        </div>
    </div>

    <?php if ($myFeedback === null): ?>
    <div style="background:#FFF8E1;border:1.5px solid #FFE082;border-radius:12px;padding:16px;margin-bottom:20px;color:#F57F17;font-size:0.9rem">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Tabel feedback belum tersedia di database. Silakan buat tabel dengan SQL berikut:<br>
        <code style="font-size:0.8rem;display:block;margin-top:8px;background:#FFF3E0;padding:8px;border-radius:6px">
            CREATE TABLE feedback (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, judul VARCHAR(255), kategori VARCHAR(100), pesan TEXT, rating INT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
        </code>
    </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card-modern p-4 animate-fade-up">
                <h6 style="font-weight:700;color:var(--primary);margin-bottom:20px">
                    <i class="bi bi-pencil-square me-2"></i>Kirim Masukan
                </h6>

                <?php if ($success): ?>
                <div style="background:#E8F5E9;border:1.5px solid #A5D6A7;border-radius:12px;padding:16px;margin-bottom:20px;display:flex;gap:10px;align-items:center">
                    <span style="font-size:24px">✅</span>
                    <div>
                        <div style="font-weight:700;color:#1B5E20">Terima kasih!</div>
                        <div style="font-size:0.85rem;color:#43A047">Masukan kamu berhasil dikirim.</div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($error): ?>
                <div style="background:#FFEBEE;border:1.5px solid #FFCDD2;border-radius:12px;padding:16px;margin-bottom:20px;color:#B71C1C;font-size:0.9rem">
                    <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <form method="POST">
                    <div style="margin-bottom:20px">
                        <label style="font-weight:600;color:var(--text);font-size:0.9rem;display:block;margin-bottom:8px">
                            Rating Aplikasi <span style="color:#EF4444">*</span>
                        </label>
                        <div class="star-rating">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>">
                            <label for="star<?= $i ?>">★</label>
                            <?php endfor; ?>
                        </div>
                        <small style="color:var(--text-muted);font-size:0.8rem">Klik bintang untuk memberi nilai</small>
                    </div>

                    <div class="input-group-modern">
                        <i class="bi bi-tag input-icon"></i>
                        <select name="kategori" class="input-modern" required>
                            <option value="" disabled selected>Pilih kategori masukan</option>
                            <option value="Saran">💡 Saran &amp; Ide</option>
                            <option value="Bug">🐛 Laporan Bug</option>
                            <option value="Fitur">✨ Request Fitur</option>
                            <option value="Akurasi">🎯 Akurasi Deteksi</option>
                            <option value="Tampilan">🎨 Tampilan/UI</option>
                            <option value="Lainnya">📝 Lainnya</option>
                        </select>
                    </div>

                    <div class="input-group-modern">
                        <i class="bi bi-card-heading input-icon"></i>
                        <input type="text" name="judul" class="input-modern" placeholder="Judul masukan kamu" required>
                    </div>

                    <div style="margin-bottom:18px">
                        <textarea name="pesan" class="input-modern" placeholder="Ceritakan masukan kamu secara detail..." required></textarea>
                    </div>

                    <button type="submit" class="btn-primary-custom ripple w-100">
                        <i class="bi bi-send me-2"></i>Kirim Masukan
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-modern p-4 animate-fade-up delay-2">
                <h6 style="font-weight:700;color:var(--primary);margin-bottom:20px">
                    <i class="bi bi-clock-history me-2"></i>Masukan Sebelumnya
                </h6>
                <?php if ($myFeedback === null || $myFeedback->num_rows == 0): ?>
                <div class="text-center py-4">
                    <div style="font-size:48px;margin-bottom:12px">📭</div>
                    <p style="color:var(--text-muted);font-size:0.9rem">Belum ada masukan yang dikirim</p>
                </div>
                <?php else: ?>
                <div class="d-flex flex-column gap-3" style="max-height:500px;overflow-y:auto">
                <?php while ($fb = $myFeedback->fetch_assoc()):
                    $kategoriColor = [
                        'Saran'    => '#E8F5E9,#1B5E20',
                        'Bug'      => '#FFEBEE,#B71C1C',
                        'Fitur'    => '#E3F2FD,#1565C0',
                        'Akurasi'  => '#FFF8E1,#F57F17',
                        'Tampilan' => '#F3E5F5,#6A1B9A',
                        'Lainnya'  => '#F3F4F6,#6B7280',
                    ];
                    $colors = explode(',', $kategoriColor[$fb['kategori']] ?? '#F3F4F6,#6B7280');
                ?>
                <div class="feedback-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="kategori-badge" style="background:<?= $colors[0] ?>;color:<?= $colors[1] ?>">
                            <?= htmlspecialchars($fb['kategori']) ?>
                        </span>
                        <div class="star-display">
                            <?= str_repeat('★', $fb['rating']) ?><?= str_repeat('☆', 5 - $fb['rating']) ?>
                        </div>
                    </div>
                    <div style="font-weight:700;color:var(--text);font-size:0.9rem;margin-bottom:4px"><?= htmlspecialchars($fb['judul']) ?></div>
                    <div style="color:var(--text-muted);font-size:0.85rem;line-height:1.5"><?= htmlspecialchars($fb['pesan']) ?></div>
                    <div style="color:#9CA3AF;font-size:0.75rem;margin-top:8px">
                        <i class="bi bi-clock me-1"></i><?= date('d M Y · H:i', strtotime($fb['created_at'])) ?>
                    </div>
                </div>
                <?php endwhile; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>