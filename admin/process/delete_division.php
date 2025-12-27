<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login");
    exit();
}

if (isset($_GET['id'])) {
    $admin_id = $_SESSION['admin_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $upload_dir = "../../uploads/headers/";

    // Ambil data sebelum dihapus untuk keperluan Log & Hapus File
    $query = mysqli_query($conn, "SELECT division_name, header_image FROM divisions WHERE id = '$id'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $div_name = $data['division_name'];

        // 1. Hapus File Gambar
        if (!empty($data['header_image']) && $data['header_image'] !== 'default_team.jpg') {
            $file_path = $upload_dir . $data['header_image'];
            if (file_exists($file_path)) unlink($file_path);
        }

        // 2. Hapus dari Database
        if (mysqli_query($conn, "DELETE FROM divisions WHERE id = '$id'")) {
            
            // 3. Catat ke Admin Logs
            $activity = "Deleted division: " . $div_name . " (Header asset cleared)";
            $type = "danger";

            $log_query = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                          VALUES ('$admin_id', '$activity', '$type', '$ip_address', NOW())";
            mysqli_query($conn, $log_query);

            header("Location: ../team_divisions.php?status=deleted");
            exit();
        }
    }
    header("Location: ../team_divisions.php?status=error");
}