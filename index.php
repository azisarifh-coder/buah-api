<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instafruit</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 480px;
            text-align: center;
        }
        .icon { font-size: 48px; margin-bottom: 10px; }
        h1 { color: #2e7d32; font-size: 26px; margin-bottom: 6px; }
        .subtitle { color: #888; font-size: 14px; margin-bottom: 28px; }
        .upload-area {
            border: 2px dashed #66bb6a;
            border-radius: 14px;
            padding: 28px;
            cursor: pointer;
            margin-bottom: 20px;
            background: #f9fff9;
            transition: 0.3s;
        }
        .upload-area:hover { background: #f1fff1; border-color: #2e7d32; }
        .upload-area img {
            max-width: 100%;
            max-height: 220px;
            border-radius: 10px;
            display: none;
        }
        .upload-icon { font-size: 36px; margin-bottom: 8px; }
        .upload-text { color: #aaa; font-size: 14px; }
        input[type="file"] { display: none; }
        .btn {
            background: linear-gradient(135deg, #2e7d32, #66bb6a);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }
        .btn:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn:disabled { opacity: 0.6; cursor: not-allowed; }
        .loading {
            margin-top: 20px;
            color: #888;
            font-size: 14px;
            display: none;
        }
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #ccc;
            border-top-color: #2e7d32;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 6px;
            vertical-align: middle;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .result {
            margin-top: 24px;
            padding: 24px;
            border-radius: 14px;
            display: none;
            animation: fadeIn 0.4s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; } }
        .fresh { background: #e8f5e9; border: 1px solid #a5d6a7; }
        .rotten { background: #fdecea; border: 1px solid #ef9a9a; }
        .result-icon { font-size: 42px; margin-bottom: 8px; }
        .result h2 { font-size: 22px; margin-bottom: 6px; }
        .fresh h2 { color: #2e7d32; }
        .rotten h2 { color: #c62828; }
        .result p { font-size: 14px; color: #666; }
        .confidence {
            display: inline-block;
            margin-top: 10px;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }
        .fresh .confidence { background: #c8e6c9; color: #2e7d32; }
        .rotten .confidence { background: #ffcdd2; color: #c62828; }
    </style>
</head>
<body>
<div class="container">
    <div class="icon">🍎</div>
    <h1>Instafruit</h1>
    <p class="subtitle">Upload foto buah untuk mengetahui kondisinya</p>

    <div class="upload-area" onclick="document.getElementById('fileInput').click()">
        <img id="preview" src="" alt="Preview">
        <div id="uploadPlaceholder">
            <div class="upload-icon">📷</div>
            <p class="upload-text">Klik untuk upload foto buah</p>
        </div>
    </div>

    <input type="file" id="fileInput" accept="image/*" onchange="previewImage(event)">
    <button class="btn" id="btnCek" onclick="checkFruit()">🔍 Cek Sekarang</button>

    <div class="loading" id="loading">
        <span class="spinner"></span> Sedang menganalisis foto...
    </div>

    <div class="result" id="result">
        <div class="result-icon" id="resultIcon"></div>
        <h2 id="resultLabel"></h2>
        <p id="resultDesc"></p>
        <span class="confidence" id="resultConfidence"></span>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('preview');
        preview.src = e.target.result;
        preview.style.display = 'block';
        document.getElementById('uploadPlaceholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
    document.getElementById('result').style.display = 'none';
}

function checkFruit() {
    const file = document.getElementById('fileInput').files[0];
    if (!file) { alert('Pilih foto buah dulu!'); return; }

    const btn = document.getElementById('btnCek');
    btn.disabled = true;
    document.getElementById('loading').style.display = 'block';
    document.getElementById('result').style.display = 'none';

    const formData = new FormData();
    formData.append('image', file);

    fetch('http://localhost:5000/predict', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('loading').style.display = 'none';
        btn.disabled = false;

        const result = document.getElementById('result');
        const label = data.kondisi;
        const isFresh = label === 'fresh';

        result.className = 'result ' + (isFresh ? 'fresh' : 'rotten');
        document.getElementById('resultIcon').textContent = isFresh ? '✅' : '❌';
        document.getElementById('resultLabel').textContent = isFresh ? 'Buah Segar!' : 'Buah Busuk!';
        document.getElementById('resultDesc').textContent = isFresh
            ? 'Buah ini dalam kondisi baik dan layak konsumsi.'
            : 'Buah ini sudah tidak segar dan tidak layak konsumsi.';
        document.getElementById('resultConfidence').textContent = 'Keyakinan: ' + data.confidence;
        result.style.display = 'block';
    })
    .catch(err => {
        document.getElementById('loading').style.display = 'none';
        btn.disabled = false;
        alert('❌ Gagal konek ke API!\nPastikan Flask API sudah berjalan di CMD.');
    });
}
</script>
</body>
</html>