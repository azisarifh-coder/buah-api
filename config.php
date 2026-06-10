<?php
session_start();

if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    $conn = new mysqli("localhost", "root", "", "buah_buah_kesehatan");
} else {
    $conn = new mysqli("sql312.infinityfree.com", "if0_41923053", "hV7RCqLEG7NF", "if0_41923053_deteksi_buah_ai");
}

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

function cekLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}
?>