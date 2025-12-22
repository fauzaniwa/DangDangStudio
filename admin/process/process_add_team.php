<?php
require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = $_SESSION['admin_id'] ?? 0;
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // 1. Ambil & Bersihkan Input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $division = mysqli_real_escape_string($conn, $_POST['division']);
    $level = mysqli_real_escape_string($conn, $_POST['level']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // 2. Tangani Upload Foto (Potrait)
    $image_name = "default_avatar.jpg"; // Default jika tidak upload
    $upload_path = "../../uploads/team/";

    // Buat folder jika belum ada
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    if (!empty($_FILES['member_image']['name'])) {
        $file_extension = pathinfo($_FILES['member_image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . '_member_' . preg_replace("/[^a-zA-Z0-9]/", "", $name) . '.' . $file_extension;
        
        if (!move_uploaded_file($_FILES['member_image']['tmp_name'], $upload_path . $image_name)) {
            $image_name = "default_avatar.jpg"; // Fallback jika gagal upload
        }
    }

    // 3. Query Insert ke Tabel Team
    $query = "INSERT INTO team (name, member_image, division, level, status, email, phone, created_at) 
              VALUES ('$name', '$image_name', '$division', '$level', '$status', '$email', '$phone', NOW())";

    if (mysqli_query($conn, $query)) {
        $new_member_id = mysqli_insert_id($conn);

        // 4. Catat ke Admin Logs
        $activity = "Added new team member: $name ($division)";
        $type = "success";

        $log_query = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                      VALUES ('$admin_id', '$activity', '$type', '$ip_address', NOW())";
        mysqli_query($conn, $log_query);

        // Redirect ke halaman team dengan pesan sukses
        header("Location: ../team.php?status=success");
        exit();
    } else {
        // Jika Gagal
        $error_msg = "Failed to add member: " . mysqli_error($conn);
        $log_error = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                      VALUES ('$admin_id', '$error_msg', 'danger', '$ip_address', NOW())";
        mysqli_query($conn, $log_error);

        header("Location: ../team_add.php?status=error");
        exit();
    }
} else {
    header("Location: ../team_add.php");
    exit();
}