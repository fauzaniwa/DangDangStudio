<?php
require_once 'config.php';
session_start();

header('Content-Type: application/json');

// 1. Cek Sesi
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];
    $admin_id = $_SESSION['admin_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // 2. Ambil nama project untuk log menggunakan Prepared Statement
    $stmt_name = $conn->prepare("SELECT project_name FROM project_timelines WHERE id = ?");
    $stmt_name->bind_param("i", $id);
    $stmt_name->execute();
    $res_name = $stmt_name->get_result();
    
    if ($res_name->num_rows > 0) {
        $data = $res_name->fetch_assoc();
        $project_name = $data['project_name'];

        // 3. Update Status Project
        $stmt_upd = $conn->prepare("UPDATE project_timelines SET status = ? WHERE id = ?");
        $stmt_upd->bind_param("si", $status, $id);

        if ($stmt_upd->execute()) {
            // 4. PENCATATAN LOG (Mengikuti standar script profil Anda)
            $log_activity = "Update Status Project '$project_name' menjadi: $status";
            $log_type = "info";
            
            $stmt_log = $conn->prepare("INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt_log->bind_param("isss", $admin_id, $log_activity, $log_type, $ip_address);
            $stmt_log->execute();

            echo json_encode(['success' => true]);
        } else {
            // Log Gagal Update
            $log_activity = "Gagal update status project: $project_name";
            $log_type = "danger";
            $stmt_log = $conn->prepare("INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt_log->bind_param("isss", $admin_id, $log_activity, $log_type, $ip_address);
            $stmt_log->execute();

            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui database']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid Request']);
}
exit();