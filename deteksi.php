<?php require 'config.php'; cekLogin(); $nama = $_SESSION['nama']; $user_id = $_SESSION['user_id']; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deteksi - Instafruit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        body { background: var(--bg); }

        .camera-wrapper { position: relative; border-radius: var(--radius); overflow: hidden; }
        #camera-stream { width: 100%; border-radius: var(--radius); display: none; }
        #preview { max-width: 100%; max-height: 280px; border-radius: var(--radius); display: none; object-fit: cover; }

        .tab-pill {
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            background: transparent;
            color: var(--text-muted);
            transition: var(--transition);
            cursor: pointer;
        }
        .tab-pill.active {
            background: linear-gradient(135deg, var(--primary-mid), var(--accent));
            color: white;
            box-shadow: 0 4px 12px rgba(46,125,50,0.3);
        }
        .tab-pill:hover:not(.active) { background: var(--success-light); color: var(--primary-mid); }

        .desc-box {
            border-radius: var(--radius-sm);
            padding: 14px 16px;
            margin-top: 12px;
            font-size: 0.875rem;
            line-height: 1.6;
        }
        .desc-box.fresh  { background: #E8F5E9; border-left: 4px solid #43A047; color: #1B5E20; }
        .desc-box.rotten { background: #FFEBEE; border-left: 4px solid #E53935; color: #B71C1C; }

        /* Hero banner di atas form */
        .deteksi-hero {
            background: linear-gradient(135deg, #1B5E20, #43A047);
            border-radius: 20px;
            padding: 24px 28px;
            color: white;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }
        .deteksi-hero::after {
            content: '🍎🍌🍊';
            position: absolute;
            right: 20px; top: 50%;
            transform: translateY(-50%);
            font-size: 40px;
            opacity: 0.15;
            letter-spacing: 4px;
        }
        .deteksi-hero h5 { font-weight: 800; margin-bottom: 4px; }
        .deteksi-hero p  { opacity: 0.8; font-size: 0.875rem; margin: 0; }
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
    </div>
</nav>

<div class="container py-4 page-wrapper">

    <div class="d-flex align-items-center mb-4">
        <a href="dashboard.php" class="btn-outline-custom me-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <h4 style="font-weight:800; color:var(--primary); margin:0">Deteksi Buah</h4>
    </div>

    <!-- Hero Banner -->
    <div class="deteksi-hero animate-fade-up">
        <h5>Analisis Kesegaran Buah dengan AI 🔍</h5>
        <p>Upload foto atau gunakan kamera real-time untuk mendeteksi apakah buah segar atau busuk.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card-modern p-4 animate-fade-up delay-1">

                <!-- Tab Switch -->
                <div class="d-flex gap-2 mb-4 p-1" style="background:#F3F4F6; border-radius:50px">
                    <button class="tab-pill active flex-fill" id="tab-upload" onclick="switchTab('upload')">
                        <i class="bi bi-upload me-1"></i> Upload Foto
                    </button>
                    <button class="tab-pill flex-fill" id="tab-kamera" onclick="switchTab('kamera')">
                        <i class="bi bi-camera-video me-1"></i> Kamera Real-Time
                    </button>
                </div>

                <!-- Panel Upload -->
                <div id="panel-upload">
                    <div class="upload-area-modern mb-3" onclick="document.getElementById('fileInput').click()">
                        <img id="preview" src="" alt="Preview">
                        <div id="uploadPlaceholder">
                            <div style="font-size:48px; margin-bottom:8px">📸</div>
                            <p style="font-weight:600; color:var(--primary); margin:0">Klik untuk upload foto buah</p>
                            <small style="color:var(--text-muted)">Format: JPG, PNG • Max 10MB</small>
                        </div>
                    </div>
                    <input type="file" id="fileInput" accept="image/*" onchange="previewImage(event)" hidden>
                    <button class="btn-primary-custom ripple w-100" id="btnCek" onclick="checkFruit()">
                        <i class="bi bi-search me-2"></i>Analisis Sekarang
                    </button>
                    <div id="loading" class="text-center mt-3 d-none">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <div class="spinner-border spinner-border-sm text-success"></div>
                            <span style="color:var(--text-muted); font-size:0.9rem">AI sedang menganalisis...</span>
                        </div>
                    </div>
                    <div id="result" class="mt-3 d-none"></div>
                </div>

                <!-- Panel Kamera -->
                <div id="panel-kamera" style="display:none">
                    <div class="camera-wrapper mb-3">
                        <div class="realtime-result" id="realtimeResult"></div>
                        <div class="scanning-line" id="scanningLine"></div>
                        <video id="camera-stream" autoplay playsinline></video>
                        <canvas id="canvas" style="display:none"></canvas>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn-primary-custom ripple" id="btnStartCamera" onclick="startRealtimeCamera()">
                            <i class="bi bi-camera-video me-2"></i>Mulai Deteksi Real-Time
                        </button>
                        <button class="btn btn-danger fw-bold d-none ripple" id="btnStopCamera" onclick="stopCamera()">
                            <i class="bi bi-stop-circle me-2"></i>Stop Kamera
                        </button>
                    </div>
                    <div id="realtimeResultBox" class="mt-3 d-none"></div>
                </div>

            </div>

            <!-- Info buah didukung -->
            <div class="card-modern p-3 mt-3 animate-fade-up delay-2"
                 style="background:linear-gradient(135deg,#E8F5E9,#F1F8E9); border:1.5px solid var(--accent-light)">
                <div style="font-size:0.82rem; color:var(--primary-mid); font-weight:600; margin-bottom:6px">
                    <i class="bi bi-info-circle me-1"></i> Buah yang didukung:
                </div>
                <div style="font-size:0.85rem; color:var(--text-muted)">🍎 Apel &nbsp;·&nbsp; 🍌 Pisang &nbsp;·&nbsp; 🍊 Jeruk</div>
            </div>
        </div>
    </div>
</div>

<script>
const userId = <?= $user_id ?>;
const API_URL = 'http://127.0.0.1:5000/predict';
let currentMode = 'upload';
let stream = null;
let realtimeInterval = null;
let isAnalyzing = false;

const fruitInfo = {
    'apple-fresh':   { emoji:'🍎', name:'Apel', status:'Segar', desc:'Apel ini dalam kondisi segar. Warna kulit cerah, tekstur padat, dan tidak ada tanda-tanda pembusukan. Layak untuk dikonsumsi.' },
    'apple-rotten':  { emoji:'🍎', name:'Apel', status:'Busuk', desc:'Apel ini terdeteksi busuk. Terdapat perubahan warna gelap, tekstur lunak, atau bercak jamur pada permukaan kulit.' },
    'banana-fresh':  { emoji:'🍌', name:'Pisang', status:'Segar', desc:'Pisang ini dalam kondisi segar. Kulit berwarna kuning cerah dengan sedikit bercak coklat alami. Layak untuk dikonsumsi.' },
    'banana-rotten': { emoji:'🍌', name:'Pisang', status:'Busuk', desc:'Pisang ini terdeteksi busuk. Kulit berwarna coklat kehitaman secara berlebihan yang mengindikasikan pembusukan.' },
    'orange-fresh':  { emoji:'🍊', name:'Jeruk', status:'Segar', desc:'Jeruk ini dalam kondisi segar. Kulit oranye cerah, tekstur padat, dan tidak ada tanda-tanda kerusakan. Layak konsumsi.' },
    'orange-rotten': { emoji:'🍊', name:'Jeruk', status:'Busuk', desc:'Jeruk ini terdeteksi busuk. Terdapat bercak jamur, pelunakan kulit, atau perubahan warna yang mengindikasikan pembusukan.' },
};

function switchTab(mode) {
    currentMode = mode;
    document.getElementById('panel-upload').style.display = mode === 'upload' ? 'block' : 'none';
    document.getElementById('panel-kamera').style.display = mode === 'kamera' ? 'block' : 'none';
    document.getElementById('tab-upload').classList.toggle('active', mode === 'upload');
    document.getElementById('tab-kamera').classList.toggle('active', mode === 'kamera');
    if (mode === 'upload') stopCamera();
}

function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('preview').src = e.target.result;
        document.getElementById('preview').style.display = 'block';
        document.getElementById('uploadPlaceholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
    document.getElementById('result').classList.add('d-none');
}

function checkFruit() {
    const file = document.getElementById('fileInput').files[0];
    if (!file) { alert('Pilih foto buah dulu!'); return; }
    const formData = new FormData();
    formData.append('image', file);
    document.getElementById('btnCek').disabled = true;
    document.getElementById('loading').classList.remove('d-none');
    document.getElementById('result').classList.add('d-none');
    fetch(API_URL, { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        document.getElementById('loading').classList.add('d-none');
        document.getElementById('btnCek').disabled = false;
        showResult(data, 'result');
        if (data.kondisi !== 'unknown') {
            const preview = document.getElementById('preview');
            const canvas2 = document.createElement('canvas');
            const maxSize = 300;
            let w = preview.naturalWidth;
            let h = preview.naturalHeight;
            if (w > h) { if (w > maxSize) { h = Math.round(h * maxSize / w); w = maxSize; } }
            else { if (h > maxSize) { w = Math.round(w * maxSize / h); h = maxSize; } }
            canvas2.width = w;
            canvas2.height = h;
            canvas2.getContext('2d').drawImage(preview, 0, 0, w, h);
            const foto_base64 = canvas2.toDataURL('image/jpeg', 0.6);
            fetch('simpan_riwayat.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ user_id: userId, filename: file.name, label: data.label, kondisi: data.kondisi, confidence: data.confidence, foto_base64: foto_base64 })
            });
        }
    })
    .catch(() => {
        document.getElementById('loading').classList.add('d-none');
        document.getElementById('btnCek').disabled = false;
        alert('❌ Gagal konek ke API!');
    });
}

function showResult(data, targetId) {
    const isUnknown = data.kondisi === 'unknown';
    const isFresh = data.kondisi === 'fresh';
    const el = document.getElementById(targetId);
    const info = fruitInfo[data.label] || null;
    if (isUnknown) {
        el.innerHTML = `<div class="result-box unknown"><div style="font-size:40px">⚠️</div><h5 style="font-weight:800;margin:8px 0 4px">Tidak Terdeteksi</h5><p style="color:var(--text-muted);font-size:0.9rem;margin:0">${data.pesan || 'Coba ambil foto lebih dekat dengan pencahayaan yang baik!'}</p></div>`;
    } else {
        const descHtml = info ? `<div class="desc-box ${isFresh ? 'fresh' : 'rotten'}"><strong>${info.emoji} ${info.name} — ${info.status}</strong><br>${info.desc}</div>` : '';
        el.innerHTML = `<div class="result-box ${isFresh ? 'fresh' : 'rotten'}">
            <div style="font-size:48px">${isFresh ? '✅' : '❌'}</div>
            <h4 style="font-weight:800;margin:8px 0 4px;color:${isFresh ? '#1B5E20' : '#B71C1C'}">${isFresh ? 'Buah Segar!' : 'Buah Busuk!'}</h4>
            <p style="font-size:0.9rem;margin-bottom:8px;color:var(--text-muted)">${isFresh ? 'Buah ini layak untuk dikonsumsi' : 'Buah ini tidak layak untuk dikonsumsi'}</p>
            <span style="background:${isFresh ? '#43A047' : '#E53935'};color:white;padding:6px 16px;border-radius:50px;font-size:0.85rem;font-weight:700">${data.label} · ${data.confidence}</span>
            ${descHtml}</div>`;
    }
    el.classList.remove('d-none');
}

async function startRealtimeCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
        const video = document.getElementById('camera-stream');
        video.srcObject = stream;
        video.style.display = 'block';
        document.getElementById('btnStartCamera').classList.add('d-none');
        document.getElementById('btnStopCamera').classList.remove('d-none');
        document.getElementById('scanningLine').style.display = 'block';
        document.getElementById('realtimeResult').style.display = 'block';
        document.getElementById('realtimeResult').innerHTML = '🔍 Mendeteksi...';
        document.getElementById('realtimeResult').style.background = 'rgba(0,0,0,0.6)';
        document.getElementById('realtimeResult').style.color = 'white';
        realtimeInterval = setInterval(analyzeFrame, 3000);
    } catch(e) { alert('Gagal membuka kamera!'); }
}

function analyzeFrame() {
    if (isAnalyzing) return;
    isAnalyzing = true;
    const video = document.getElementById('camera-stream');
    const canvas = document.getElementById('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    canvas.toBlob(blob => {
        const formData = new FormData();
        formData.append('image', blob, 'realtime.jpg');
        fetch(API_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            isAnalyzing = false;
            const realtimeEl = document.getElementById('realtimeResult');
            if (data.kondisi === 'unknown') {
                realtimeEl.innerHTML = '🔍 Arahkan ke buah...';
                realtimeEl.style.background = 'rgba(0,0,0,0.6)';
            } else if (data.kondisi === 'fresh') {
                realtimeEl.innerHTML = `✅ ${data.label} · ${data.confidence}`;
                realtimeEl.style.background = 'rgba(27,94,32,0.85)';
                showResult(data, 'realtimeResultBox');
            } else {
                realtimeEl.innerHTML = `❌ ${data.label} · ${data.confidence}`;
                realtimeEl.style.background = 'rgba(183,28,28,0.85)';
                showResult(data, 'realtimeResultBox');
            }
        })
        .catch(() => { isAnalyzing = false; });
    }, 'image/jpeg', 0.8);
}

function stopCamera() {
    if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
    if (realtimeInterval) { clearInterval(realtimeInterval); realtimeInterval = null; }
    isAnalyzing = false;
    document.getElementById('camera-stream').style.display = 'none';
    document.getElementById('btnStartCamera').classList.remove('d-none');
    document.getElementById('btnStopCamera').classList.add('d-none');
    document.getElementById('scanningLine').style.display = 'none';
    document.getElementById('realtimeResult').style.display = 'none';
    document.getElementById('realtimeResultBox').classList.add('d-none');
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