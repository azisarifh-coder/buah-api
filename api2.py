from flask import Flask, request, jsonify
from flask_cors import CORS
from inference_sdk import InferenceHTTPClient
import tempfile
import os

app = Flask(__name__)
CORS(app, origins="*")

CLIENT = InferenceHTTPClient(
    api_url="https://serverless.roboflow.com",
    api_key="szPAq1dmfLBxMLkWD3HB"
)
print("Siap!", flush=True)

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
    tmp = tempfile.NamedTemporaryFile(delete=False, suffix='.jpg')
    file.save(tmp.name)
    tmp.close()

    try:
        result = CLIENT.infer(tmp.name, model_id="buah-segar-v2/2")
        print("Result: " + str(result), flush=True)

        if os.path.exists(tmp.name):
            os.unlink(tmp.name)

        label = result.get('top', '')
        confidence = round(result.get('confidence', 0) * 100, 2)

        if not label:
            return jsonify({
                'kondisi': 'unknown',
                'label': 'Tidak dikenali',
                'confidence': '0%',
                'pesan': 'Foto kurang jelas, coba ambil foto lebih dekat dengan pencahayaan terang!'
            }), 200

        kondisi = 'fresh' if 'fresh' in label.lower() else 'rotten'

        return jsonify({
            'kondisi': kondisi,
            'label': label,
            'confidence': str(confidence) + '%'
        })

    except Exception as e:
        print("Error: " + str(e), flush=True)
        if os.path.exists(tmp.name):
            os.unlink(tmp.name)
        return jsonify({'error': str(e)}), 500

print("Starting server...", flush=True)
app.run(debug=False, port=5000, host='0.0.0.0')