<?php
require_once 'config.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['admin_id'])) {
    $division_name = mysqli_real_escape_string($conn, $_POST['division_name']);
    $admin_id = $_SESSION['admin_id'];

    // Cek duplikasi
    $check = mysqli_query($conn, "SELECT id FROM divisions WHERE division_name = '$division_name'");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Divisi ini sudah ada!']);
        exit;
    }

    $sql = "INSERT INTO divisions (division_name) VALUES ('$division_name')";
    if (mysqli_query($conn, $sql)) {
        createLog($admin_id, "Menambahkan divisi baru: $division_name", "info");
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke database']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak']);
}