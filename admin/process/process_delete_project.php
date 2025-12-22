<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $admin_id = $_SESSION['admin_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // 1. Ambil info proyek sebelum dihapus (untuk nama proyek di log dan hapus file)
    $stmt_get = $conn->prepare("SELECT project_name, project_icon FROM projects WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $project = $stmt_get->get_result()->fetch_assoc();

    if ($project) {
        $project_name = $project['project_name'];
        $icon_name = $project['project_icon'];

        // 2. Hapus permanen dari database
        $stmt_del = $conn->prepare("DELETE FROM projects WHERE id = ?");
        $stmt_del->bind_param("i", $id);

        if ($stmt_del->execute()) {
            // 3. Hapus file ikon dari server
            if (!empty($icon_name)) {
                $file_path = "../../uploads/projects/" . $icon_name;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            // 4. Catat Log
            $log_activity = "Permanently deleted project: " . $project_name . " (ID: $id)";
            $log_type = "danger"; // Menggunakan tipe danger untuk penghapusan
            
            $stmt_log = $conn->prepare("INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt_log->bind_param("isss", $admin_id, $log_activity, $log_type, $ip_address);
            $stmt_log->execute();

            header("Location: ../project_manager.php?status=success&msg=Project deleted permanently");
        } else {
            header("Location: ../project_manager.php?status=error&msg=Failed to delete project");
        }
    } else {
        header("Location: ../project_manager.php?status=error&msg=Project not found");
    }
} else {
    header("Location: ../project_manager.php");
}
exit();