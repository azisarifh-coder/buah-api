from flask import Flask, request, jsonify
from flask_cors import CORS
import torch
import torch.nn as nn
import torchvision.transforms as transforms
import torchvision.models as models
from PIL import Image
import io
import os
import gdown

app = Flask(__name__)
CORS(app, origins="*")

MODEL_PATH = "buah_model.pth"
GDRIVE_ID = "1vke_QI1WovUScB5pvHHVWwRxcSz1gdTF"

# Download model dari Google Drive kalau belum ada
if not os.path.exists(MODEL_PATH):
    print("Downloading model dari Google Drive...", flush=True)
    gdown.download(f"https://drive.google.com/uc?id={GDRIVE_ID}", MODEL_PATH, quiet=False)
    print("Model berhasil didownload!", flush=True)

# Load model PyTorch
print("Loading model...", flush=True)
checkpoint = torch.load(MODEL_PATH, map_location="cpu", weights_only=False)
classes = checkpoint['classes']
print("Kelas:", classes, flush=True)

model = models.mobilenet_v2(weights=None)
model.classifier[1] = nn.Linear(model.last_channel, len(classes))
model.load_state_dict(checkpoint['model_state_dict'])
model.eval()
print("Model siap!", flush=True)

transform = transforms.Compose([
    transforms.Resize((224, 224)),
    transforms.ToTensor(),
    transforms.Normalize([0.485, 0.456, 0.406], [0.229, 0.224, 0.225])
])

@app.route('/')
def home():
    return jsonify({'status': 'API berjalan!'})

@app.route('/predict', methods=['POST', 'OPTIONS'])
def predict():
    if request.method == 'OPTIONS':
        return jsonify({'status': 'ok'}), 200

    print("Request masuk!", flush=True)

    if 'image' not in request.files:
        return jsonify({'error': 'Tidak ada gambar!'}), 400

    file = request.files['image']
    img = Image.open(io.BytesIO(file.read())).convert('RGB')
    tensor = transform(img).unsqueeze(0)

    with torch.no_grad():
        output = model(tensor)
        probs = torch.softmax(output, dim=1)
        confidence, predicted = torch.max(probs, 1)

    label = classes[predicted.item()]
    confidence_pct = round(confidence.item() * 100, 2)
    kondisi = 'fresh' if 'fresh' in label.lower() else 'rotten'

    print(f"Hasil: {label} ({confidence_pct}%)", flush=True)

    return jsonify({
        'kondisi': kondisi,
        'label': label,
        'confidence': str(confidence_pct) + '%'
    })

port = int(os.environ.get("PORT", 5000))
print("Starting server di port " + str(port), flush=True)
app.run(debug=False, port=port, host='0.0.0.0')