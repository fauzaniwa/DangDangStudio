<?php
require_once 'config.php';
session_start();

// Set header agar browser tahu ini adalah data JSON
header('Content-Type: application/json');

// Proteksi: Pastikan hanya admin yang bisa menambah tim
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Ambil data dari AJAX (POST)
    $name  = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $color = isset($_POST['color']) ? mysqli_real_escape_string($conn, $_POST['color']) : '';

    // 2. Validasi input sederhana
    if (empty($name) || empty($color)) {
        echo json_encode(['success' => false, 'message' => 'Team name and color are required']);
        exit();
    }

    // 3. Query Insert ke tabel teams
    $sql = "INSERT INTO teams (team_name, color_class) VALUES ('$name', '$color')";

    if (mysqli_query($conn, $sql)) {
        // Jika berhasil, kirim respon sukses
        echo json_encode([
            'success' => true, 
            'message' => 'New team added successfully',
            'team_id' => mysqli_insert_id($conn) // Opsional: kirim ID baru
        ]);
    } else {
        // Jika gagal database
        echo json_encode([
            'success' => false, 
            'message' => 'Database error: ' . mysqli_error($conn)
        ]);
    }
} else {
    // Jika diakses langsung tanpa POST
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
exit();