<?php
require_once 'config.php';
session_start();
header('Content-Type: application/json');

// Proteksi: Hanya admin login yang bisa menambah data
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $admin_id = $_SESSION['admin_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Name cannot be empty.']);
        exit;
    }

    // Tentukan tabel berdasarkan type
    $table = '';
    $column = '';
    $display_type = '';

    switch ($type) {
        case 'genre':
            $table = 'game_genres';
            $column = 'genre_name';
            $display_type = 'Genre';
            break;
        case 'status':
            $table = 'project_statuses';
            $column = 'status_name';
            $display_type = 'Status';
            break;
        case 'platform':
            $table = 'game_platforms';
            $column = 'platform_name';
            $display_type = 'Platform';
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid type.']);
            exit;
    }

    // 1. Cek apakah data sudah ada (Duplicate Check)
    $check_sql = "SELECT id FROM $table WHERE $column = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("s", $name);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => "$display_type '$name' already exists."]);
        exit;
    }

    // 2. Insert Data Baru
    $sql = "INSERT INTO $table ($column) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);

    if ($stmt->execute()) {
        // 3. Catat ke Admin Logs
        $activity = "Quick Add: Menambahkan $display_type baru ($name)";
        $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) VALUES (?, ?, 'success', ?, NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("iss", $admin_id, $activity, $ip_address);
        $log_stmt->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }
}