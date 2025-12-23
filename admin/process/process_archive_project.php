<?php
require_once 'config.php';
session_start();

// 1. Keamanan: Pastikan admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// 2. Ambil parameter ID dan aksi (default: archive)
$project_id = $_GET['id'] ?? null;
$action     = $_GET['action'] ?? 'archive'; // Bisa 'archive' atau 'restore'
$admin_id   = $_SESSION['admin_id'];
$ip_address = $_SERVER['REMOTE_ADDR'];

if (!$project_id) {
    header("Location: ../project_manager.php?status=error");
    exit();
}

// Tentukan nilai bit is_archived berdasarkan aksi
$archive_value = ($action === 'restore') ? 0 : 1;
$activity_text = ($action === 'restore') ? "Restored" : "Archived";

// Mulai Database Transaction agar data konsisten
mysqli_begin_transaction($conn);

try {
    // 3. Ambil nama proyek untuk log sebelum diupdate
    $stmt_name = $conn->prepare("SELECT project_name FROM projects WHERE id = ?");
    $stmt_name->bind_param("i", $project_id);
    $stmt_name->execute();
    $res_name = $stmt_name->get_result();
    $project = $res_name->fetch_assoc();

    if (!$project) {
        throw new Exception("Project not found.");
    }

    $project_name = $project['project_name'];

    // 4. Update status proyek
    $stmt_upd = $conn->prepare("UPDATE projects SET is_archived = ? WHERE id = ?");
    $stmt_upd->bind_param("ii", $archive_value, $project_id);
    $stmt_upd->execute();

    // 5. Catat ke Admin Logs
    $log_msg = "$activity_text Project: Memindahkan '$project_name' " . ($action === 'restore' ? "kembali ke Daftar Aktif" : "ke folder Arsip");
    $log_type = ($action === 'restore') ? 'success' : 'warning';
    
    $stmt_log = $conn->prepare("INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt_log->bind_param("isss", $admin_id, $log_msg, $log_type, $ip_address);
    $stmt_log->execute();

    // Jika sampai sini tanpa error, simpan semua perubahan
    mysqli_commit($conn);

    header("Location: ../project_manager.php?status=success&msg=" . urlencode("Project $activity_text successfully"));
    exit();

} catch (Exception $e) {
    // Jika ada yang gagal, batalkan semua perubahan di database
    mysqli_rollback($conn);
    header("Location: ../project_manager.php?status=error&msg=" . urlencode($e->getMessage()));
    exit();
}