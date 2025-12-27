<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = $_SESSION['admin_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $division_name = mysqli_real_escape_string($conn, $_POST['division_name']);
    $upload_dir = "../../uploads/headers/";

    $image_name = "";
    if (isset($_FILES['header_image']) && $_FILES['header_image']['error'] === 0) {
        $ext = pathinfo($_FILES['header_image']['name'], PATHINFO_EXTENSION);
        $new_name = strtolower(str_replace(' ', '_', $division_name)) . '_' . time() . '.' . $ext;
        
        if (move_uploaded_file($_FILES['header_image']['tmp_name'], $upload_dir . $new_name)) {
            $image_name = $new_name;
            // Hapus gambar lama jika mode Edit
            if (!empty($id)) {
                $old = mysqli_query($conn, "SELECT header_image FROM divisions WHERE id = '$id'");
                $old_data = mysqli_fetch_assoc($old);
                if ($old_data && !empty($old_data['header_image']) && $old_data['header_image'] != 'default_team.jpg') {
                    if (file_exists($upload_dir . $old_data['header_image'])) unlink($upload_dir . $old_data['header_image']);
                }
            }
        }
    }

    if (!empty($id)) {
        // --- MODE EDIT ---
        $sql = !empty($image_name) 
            ? "UPDATE divisions SET division_name = '$division_name', header_image = '$image_name' WHERE id = '$id'"
            : "UPDATE divisions SET division_name = '$division_name' WHERE id = '$id'";
        
        $activity = "Updated division: " . $division_name . (empty($image_name) ? "" : " (New header uploaded)");
        $log_type = "info";
    } else {
        // --- MODE ADD ---
        $final_img = !empty($image_name) ? $image_name : 'default_team.jpg';
        $sql = "INSERT INTO divisions (division_name, header_image) VALUES ('$division_name', '$final_img')";
        
        $activity = "Created new division: " . $division_name;
        $log_type = "success";
    }

    if (mysqli_query($conn, $sql)) {
        // Catat Log
        $log_query = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                      VALUES ('$admin_id', '$activity', '$log_type', '$ip_address', NOW())";
        mysqli_query($conn, $log_query);

        header("Location: ../team_divisions.php?status=success");
    } else {
        header("Location: ../team_divisions.php?status=error");
    }
}