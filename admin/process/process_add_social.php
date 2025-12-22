<?php
require_once './config.php';
session_start();

// 1. Validasi Akses Admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    
    // 2. Ambil dan Sanitasi Data Form
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $publish_date = mysqli_real_escape_string($conn, $_POST['publish_date']);
    $publish_time = mysqli_real_escape_string($conn, $_POST['publish_time']);
    $caption = mysqli_real_escape_string($conn, $_POST['caption']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Proses Platforms (Array ke String)
    $platforms_array = isset($_POST['platforms']) ? $_POST['platforms'] : [];
    $platforms_string = implode(', ', $platforms_array);

    // 3. Proses Upload Thumbnail
    $thumbnail_name = null;
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
        $target_dir = "../../uploads/social/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_ext = strtolower(pathinfo($_FILES["thumbnail"]["name"], PATHINFO_EXTENSION));
        $thumbnail_name = "social_" . time() . "_" . uniqid() . "." . $file_ext;
        move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_dir . $thumbnail_name);
    }

    // 4. Query Insert ke Database
    $sql = "INSERT INTO social_media (thumbnail, platforms, title, publish_date, publish_time, caption, status) 
            VALUES ('$thumbnail_name', '$platforms_string', '$title', '$publish_date', '$publish_time', '$caption', '$status')";

    if (mysqli_query($conn, $sql)) {
        $new_id = mysqli_insert_id($conn); // Ambil ID data yang baru saja dibuat

        // 5. IMPLEMENTASI ADMIN LOGS
        $activity = mysqli_real_escape_string($conn, "Created a new social media plan: '$title' (Status: $status)");
        $ip = $_SERVER['REMOTE_ADDR'];
        $type = 'success'; // Tipe aktivitas

        $sql_log = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                    VALUES ('$admin_id', '$activity', '$type', '$ip', NOW())";
        
        mysqli_query($conn, $sql_log);

        // Berhasil, arahkan ke halaman plan
        header("Location: ../social_media_plan.php?status=success");
    } else {
        // Gagal, arahkan kembali dengan status error
        header("Location: ../social_media_plan.php?status=error");
    }
} else {
    // Jika akses ilegal (bukan POST)
    header("Location: ../social_media_add.php");
}
exit();