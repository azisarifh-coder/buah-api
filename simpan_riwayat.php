<?php
require 'config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak valid']);
    exit;
}

$user_id     = intval($data['user_id'] ?? 0);
$filename    = $data['label'] ?? $data['filename'] ?? '';
$kondisi     = $data['kondisi'] ?? '';
$confidence  = $data['confidence'] ?? '';
$foto_base64 = $data['foto_base64'] ?? '';

$stmt = $conn->prepare("INSERT INTO riwayat (user_id, filename, kondisi, confidence, foto_base64) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $user_id, $filename, $kondisi, $confidence, $foto_base64);

if ($stmt->execute()) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}
?>