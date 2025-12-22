<?php
require_once './config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    
    // 1. Ambil & Sanitize Data
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $publish_date = mysqli_real_escape_string($conn, $_POST['publish_date']);
    $publish_time = mysqli_real_escape_string($conn, $_POST['publish_time']);
    $caption = mysqli_real_escape_string($conn, $_POST['caption']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Olah Platform (Array ke String)
    $platforms_array = isset($_POST['platforms']) ? $_POST['platforms'] : [];
    $platforms_string = implode(', ', $platforms_array);

    // 2. Ambil data lama untuk cek thumbnail lama
    $old_query = "SELECT thumbnail FROM social_media WHERE id = '$id'";
    $old_result = mysqli_query($conn, $old_query);
    $old_data = mysqli_fetch_assoc($old_result);
    $current_thumbnail = $old_data['thumbnail'] ?? '';

    // 3. Logika Upload Gambar
    $thumbnail_to_save = $current_thumbnail; // Default pakai yang lama

    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
        $target_dir = "../../uploads/social/";
        
        // Hapus file fisik lama jika ada file baru yang diupload
        if (!empty($current_thumbnail)) {
            $old_file_path = $target_dir . $current_thumbnail;
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
        }

        // Proses upload file baru
        $file_ext = strtolower(pathinfo($_FILES["thumbnail"]["name"], PATHINFO_EXTENSION));
        $new_filename = "social_" . time() . "_" . uniqid() . "." . $file_ext;
        
        if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_dir . $new_filename)) {
            $thumbnail_to_save = $new_filename;
        }
    }

    // 4. Query Update Database
    $sql_update = "UPDATE social_media SET 
                   title = '$title',
                   thumbnail = '$thumbnail_to_save',
                   platforms = '$platforms_string',
                   publish_date = '$publish_date',
                   publish_time = '$publish_time',
                   caption = '$caption',
                   status = '$status'
                   WHERE id = '$id'";

    if (mysqli_query($conn, $sql_update)) {
        // 5. Catat ke Admin Logs
        $log_activity = "Updated Social Media Plan: " . $title;
        $ip = $_SERVER['REMOTE_ADDR'];
        $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) 
                    VALUES ('$admin_id', '$log_activity', 'info', '$ip')";
        mysqli_query($conn, $log_sql);

        header("Location: ../social_media_plan.php?status=success");
    } else {
        header("Location: ../social_media_plan.php?status=error");
    }
} else {
    header("Location: ../social_media_plan.php");
}
exit();