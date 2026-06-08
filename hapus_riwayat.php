<?php
require 'config.php';
cekLogin();

header('Content-Type: application/json');

$id      = intval($_POST['id'] ?? 0);
$user_id = $_SESSION['user_id'];

if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
    exit;
}

$stmt = $conn->prepare("SELECT foto_path FROM riwayat WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$stmt->bind_result($foto_path);
$found = $stmt->fetch();
$stmt->close();

if (!$found) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
    exit;
}

if (!empty($foto_path)) {
    @unlink($_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($foto_path, '/'));
    @unlink(__DIR__ . '/' . $foto_path);
}

$stmt2 = $conn->prepare("DELETE FROM riwayat WHERE id=? AND user_id=?");
$stmt2->bind_param("ii", $id, $user_id);

if ($stmt2->execute()) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal hapus dari database']);
}