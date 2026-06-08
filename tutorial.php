<?php require 'config.php'; cekLogin(); $nama = $_SESSION['nama']; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorial - Instafruit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        body { background: var(--bg); }
        .step-card {
            background: white;
            border-radius: var(--radius);
            padding: 28px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            border-left: 5px solid var(--accent);
        }
        .step-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
        .step-number {
            width: 48px; height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-mid), var(--accent));
            color: white;
            font-weight: 800;
            font-size: 1.2rem;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .tip-card {
            background: linear-gradient(135deg, #E8F5E9, #F1F8E9);
            border-radius: var(--radius);
            padding: 20px 24px;
            border: 1.5px solid var(--accent-light);
        }
        .faq-item {
            background: white;
            border-radius: var(--radius-sm);
            padding: 20px;
            box-shadow: var(--shadow);
            cursor: pointer;
            transition: var(--transition);
        }
        .faq-item:hover { box-shadow: var(--shadow-hover); }
        .faq-answer {
            display: none;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #F3F4F6;
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.7;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark">
    <a href="dashboard.php" class="navbar-brand mb-0">🍎 Instafruit</a>
    <div class="d-flex align-items-center gap-2">
        <a href="tutorial.php" class="nav-link-custom active"><i class="bi bi-book"></i> Tutorial</a>
        <a href="tentang.php" class="nav-link-custom"><i class="bi bi-info-circle"></i> Tentang</a>
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
        <h4 style="font-weight:800;color:var(--primary);margin:0">Tutorial & Panduan</h4>
    </div>

    <!-- Hero -->
    <div class="card-modern p-4 mb-4 animate-fade-up" style="background:linear-gradient(135deg,#1B5E20,#43A047)">
        <div class="row align-items-center">
            <div class="col-8">
                <h4 style="color:white;font-weight:800;margin-bottom:8px">Cara Menggunakan Aplikasi</h4>
                <p style="color:rgba(255,255,255,0.8);font-size:0.9rem;margin:0">
                    Ikuti panduan berikut untuk mulai mendeteksi kondisi buah dengan mudah dan akurat.
                </p>
            </div>
            <div class="col-4 text-end" style="font-size:56px">📖</div>
        </div>
    </div>

    <!-- Langkah-langkah -->
    <h6 style="font-weight:700;color:var(--primary);margin-bottom:16px">
        <i class="bi bi-list-ol me-2"></i>Langkah-langkah Penggunaan
    </h6>

    <div class="d-flex flex-column gap-3 mb-4">
        <div class="step-card animate-fade-up delay-1">
            <div class="d-flex align-items-start gap-3">
                <div class="step-number">1</div>
                <div>
                    <h6 style="font-weight:700;color:var(--primary);margin-bottom:6px">Buat Akun / Login</h6>
                    <p style="color:var(--text-muted);font-size:0.9rem;margin:0;line-height:1.6">
                        Jika belum punya akun, klik <strong>"Daftar sekarang"</strong> di halaman login.
                        Isi nama lengkap, email, dan password. Jika sudah punya akun, langsung masukkan
                        email dan password lalu klik <strong>"Masuk"</strong>.
                    </p>
                </div>
            </div>
        </div>

        <div class="step-card animate-fade-up delay-2">
            <div class="d-flex align-items-start gap-3">
                <div class="step-number">2</div>
                <div>
                    <h6 style="font-weight:700;color:var(--primary);margin-bottom:6px">Lihat Dashboard</h6>
                    <p style="color:var(--text-muted);font-size:0.9rem;margin:0;line-height:1.6">
                        Setelah login, kamu akan masuk ke halaman <strong>Dashboard</strong> yang menampilkan
                        statistik deteksimu — total deteksi, jumlah buah segar, buah busuk, dan persentase
                        kesegaran. Dari sini kamu bisa navigasi ke semua fitur.
                    </p>
                </div>
            </div>
        </div>

        <div class="step-card animate-fade-up delay-3" style="border-left-color:#43A047">
            <div class="d-flex align-items-start gap-3">
                <div class="step-number">3</div>
                <div>
                    <h6 style="font-weight:700;color:var(--primary);margin-bottom:6px">Deteksi via Upload Foto</h6>
                    <p style="color:var(--text-muted);font-size:0.9rem;margin:0;line-height:1.6">
                        Klik menu <strong>"Deteksi Buah"</strong> → pilih tab <strong>"Upload Foto"</strong>.
                        Klik area upload atau drag & drop foto buah kamu. Pastikan foto jelas, pencahayaan
                        cukup, dan buah mengisi sebagian besar frame. Klik <strong>"Analisis Sekarang"</strong>
                        dan tunggu hasilnya.
                    </p>
                    <div style="background:#E8F5E9;border-radius:8px;padding:10px 14px;margin-top:12px;font-size:0.85rem;color:#1B5E20">
                        <i class="bi bi-lightbulb me-1"></i>
                        <strong>Tips:</strong> Buah yang didukung: 🍎 Apel · 🍌 Pisang · 🍊 Jeruk
                    </div>
                </div>
            </div>
        </div>

        <div class="step-card animate-fade-up delay-4" style="border-left-color:#66BB6A">
            <div class="d-flex align-items-start gap-3">
                <div class="step-number">4</div>
                <div>
                    <h6 style="font-weight:700;color:var(--primary);margin-bottom:6px">Deteksi via Kamera Real-Time</h6>
                    <p style="color:var(--text-muted);font-size:0.9rem;margin:0;line-height:1.6">
                        Pilih tab <strong>"Kamera Real-Time"</strong> → klik <strong>"Mulai Deteksi Real-Time"</strong>.
                        Izinkan akses kamera browser. Arahkan kamera ke buah — aplikasi akan otomatis
                        menganalisis setiap 3 detik. Hasil muncul langsung di atas kamera.
                    </p>
                </div>
            </div>
        </div>

        <div class="step-card animate-fade-up delay-5" style="border-left-color:#A5D6A7">
            <div class="d-flex align-items-start gap-3">
                <div class="step-number">5</div>
                <div>
                    <h6 style="font-weight:700;color:var(--primary);margin-bottom:6px">Lihat & Kelola Riwayat</h6>
                    <p style="color:var(--text-muted);font-size:0.9rem;margin:0;line-height:1.6">
                        Semua hasil deteksi tersimpan otomatis di <strong>Riwayat</strong>. Kamu bisa
                        melihat histori lengkap termasuk jenis buah, kondisi (segar/busuk), tingkat
                        keyakinan AI, dan waktu deteksi. Klik ikon 🗑️ untuk menghapus riwayat tertentu.
                    </p>
                </div>
            </div>
        </div>

        <div class="step-card animate-fade-up" style="border-left-color:#C8E6C9">
            <div class="d-flex align-items-start gap-3">
                <div class="step-number">6</div>
                <div>
                    <h6 style="font-weight:700;color:var(--primary);margin-bottom:6px">Logout</h6>
                    <p style="color:var(--text-muted);font-size:0.9rem;margin:0;line-height:1.6">
                        Setelah selesai, klik tombol <strong>Logout</strong> di navbar untuk keluar dengan aman.
                        Sesi kamu akan berakhir dan data tetap tersimpan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tips -->
    <h6 style="font-weight:700;color:var(--primary);margin-bottom:16px">
        <i class="bi bi-lightbulb me-2"></i>Tips Agar Hasil Akurat
    </h6>
    <div class="row g-3 mb-4">
        <?php
        $tips = [
            ['icon'=>'☀️', 'title'=>'Pencahayaan Cukup',    'desc'=>'Pastikan foto diambil di tempat yang terang. Hindari foto yang terlalu gelap atau silau.'],
            ['icon'=>'🎯', 'title'=>'Fokus ke Buah',         'desc'=>'Pastikan buah mengisi sebagian besar frame foto. Hindari latar belakang yang terlalu ramai.'],
            ['icon'=>'📏', 'title'=>'Jarak Tepat',           'desc'=>'Ambil foto dari jarak 20-40cm. Jangan terlalu jauh atau terlalu dekat dari buah.'],
            ['icon'=>'🔄', 'title'=>'Coba Beberapa Sudut',   'desc'=>'Jika tidak terdeteksi, coba foto dari sudut yang berbeda atau dengan pencahayaan lebih baik.'],
        ];
        foreach ($tips as $i => $tip): ?>
        <div class="col-md-6 animate-fade-up" style="animation-delay:<?= $i * 0.1 ?>s">
            <div class="tip-card d-flex gap-3">
                <div style="font-size:32px;flex-shrink:0"><?= $tip['icon'] ?></div>
                <div>
                    <div style="font-weight:700;color:var(--primary);margin-bottom:4px"><?= $tip['title'] ?></div>
                    <div style="color:var(--text-muted);font-size:0.875rem"><?= $tip['desc'] ?></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- FAQ -->
    <h6 style="font-weight:700;color:var(--primary);margin-bottom:16px">
        <i class="bi bi-question-circle me-2"></i>Pertanyaan Umum (FAQ)
    </h6>
    <div class="d-flex flex-column gap-3 mb-4">
        <?php
        $faqs = [
            ['q'=>'Buah apa saja yang bisa dideteksi?',                         'a'=>'Saat ini aplikasi mendukung 3 jenis buah: Apel (Apple), Pisang (Banana), dan Jeruk (Orange). Setiap jenis bisa dideteksi apakah dalam kondisi segar atau busuk.'],
            ['q'=>'Kenapa hasil deteksi menunjukkan "Tidak Terdeteksi"?',        'a'=>'Hal ini bisa terjadi karena: foto terlalu gelap, buah tidak jelas, latar belakang terlalu ramai, atau buah bukan termasuk jenis yang didukung. Coba ambil foto ulang dengan pencahayaan lebih baik.'],
            ['q'=>'Apakah data saya aman?',                                      'a'=>'Ya! Foto yang kamu upload hanya digunakan untuk proses analisis AI dan tidak disimpan secara permanen di server. Hanya hasil deteksi (label dan keyakinan) yang tersimpan di riwayatmu.'],
            ['q'=>'Berapa tingkat akurasi aplikasi ini?',                        'a'=>'Model AI kami dilatih dengan lebih dari 10.000 foto buah dan mencapai akurasi 99.95% pada dataset pengujian. Namun hasil bisa bervariasi tergantung kualitas foto.'],
            ['q'=>'Apakah bisa digunakan di HP?',                                'a'=>'Ya! Aplikasi ini berbasis web dan responsif, bisa diakses dari HP, tablet, maupun PC melalui browser tanpa perlu install aplikasi tambahan.'],
        ];
        foreach ($faqs as $i => $faq): ?>
        <div class="faq-item animate-fade-up" style="animation-delay:<?= $i * 0.1 ?>s" onclick="toggleFaq(this)">
            <div class="d-flex justify-content-between align-items-center">
                <span style="font-weight:600;color:var(--text);font-size:0.95rem"><?= $faq['q'] ?></span>
                <i class="bi bi-chevron-down" style="color:var(--text-muted);transition:all 0.3s;flex-shrink:0;margin-left:12px"></i>
            </div>
            <div class="faq-answer"><?= $faq['a'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function toggleFaq(el) {
    const answer = el.querySelector('.faq-answer');
    const icon = el.querySelector('i');
    const isOpen = answer.style.display === 'block';
    answer.style.display = isOpen ? 'none' : 'block';
    icon.className = isOpen ? 'bi bi-chevron-down' : 'bi bi-chevron-up';
    icon.style.color = isOpen ? 'var(--text-muted)' : 'var(--primary)';
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