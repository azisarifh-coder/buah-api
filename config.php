<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$conn = new mysqli(
    "sql312.infinityfree.com",
    "if0_41923053",
    "y5Kv9pGROa",
    "if0_41923053_instafruit"
);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
function cekLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}