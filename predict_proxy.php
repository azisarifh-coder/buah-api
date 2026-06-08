<?php
require 'config.php';

// API URL disimpan di sini (tidak terlihat dari browser)
$API_URL = 'https://buah-api-production.up.railway.app/predict';

// Terima file dari client
if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

$file     = $_FILES['file'];
$tmpPath  = $file['tmp_name'];
$fileName = $file['name'];
$mimeType = $file['type'];

// Forward ke Railway API
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL            => $API_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => [
        'file' => new CURLFile($tmpPath, $mimeType, $fileName)
    ],
    CURLOPT_TIMEOUT        => 30,
]);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$error    = curl_error($curl);
curl_close($curl);

if ($error) {
    http_response_code(500);
    echo json_encode(['error' => $error]);
    exit;
}

http_response_code($httpCode);
header('Content-Type: application/json');
echo $response;