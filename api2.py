from flask import Flask, request, jsonify
from flask_cors import CORS
import requests
import os

app = Flask(__name__)
CORS(app, origins="*")

API_KEY = "szPAq1dmfLBxMLkWD3HB"
MODEL_ID = "buah-segar-v2/3"

print("Siap!", flush=True)

@app.route('/')
def home():
    return jsonify({'status': 'API Instafruit berjalan!'})

@app.route('/predict', methods=['POST', 'OPTIONS'])
def predict():
    if request.method == 'OPTIONS':
        return jsonify({'status': 'ok'}), 200

    print("Request masuk!", flush=True)

    if 'image' not in request.files:
        return jsonify({'error': 'Tidak ada gambar!'}), 400

    file = request.files['image']
    img_bytes = file.read()

    try:
        response = requests.post(
            f"https://serverless.roboflow.com/{MODEL_ID}",
            params={"api_key": API_KEY},
            files={"file": ("image.jpg", img_bytes, "image/jpeg")}
        )

        result = response.json()
        print("Full Result: " + str(result), flush=True)

        label = ''
        confidence = 0.0

        if result.get('top'):
            label = result['top']
            confidence = round(result.get('confidence', 0) * 100, 2)
        elif result.get('predictions') and len(result['predictions']) > 0:
            top = result['predictions'][0]
            if isinstance(top, dict):
                label = top.get('class', '')
                confidence = round(top.get('confidence', 0) * 100, 2)

        print(f"Label: {label}, Confidence: {confidence}", flush=True)

        if not label or confidence < 20:
            return jsonify({
                'kondisi': 'unknown',
                'label': 'Tidak dikenali',
                'confidence': '0%',
                'pesan': 'Foto kurang jelas, coba ambil foto lebih dekat!'
            }), 200

        kondisi = 'fresh' if 'fresh' in label.lower() else 'rotten'

        return jsonify({
            'kondisi': kondisi,
            'label': label,
            'confidence': str(confidence) + '%'
        })

    except Exception as e:
        print("Error: " + str(e), flush=True)
        return jsonify({'error': str(e)}), 500

port = int(os.environ.get("PORT", 5000))
print("Starting server di port " + str(port), flush=True)
app.run(debug=False, port=port, host='0.0.0.0')