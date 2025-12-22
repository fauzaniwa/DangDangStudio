<?php
require_once './config.php';
session_start();

// 1. Proteksi Akses & Validasi Metode
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['admin_id'])) {
    header("Location: ../testimonials.php");
    exit();
}

// 2. Ambil dan Amankan Data Form
$id = mysqli_real_escape_string($conn, $_POST['id']);
$name = mysqli_real_escape_string($conn, $_POST['name']);
$role = mysqli_real_escape_string($conn, $_POST['role']);
$stars = intval($_POST['stars']);
$content = mysqli_real_escape_string($conn, $_POST['content']);

$avatar_update_sql = "";

// 3. Proses Penggantian Avatar (Jika ada file baru)
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
    $target_dir = "../../uploads/testimonials/";
    
    // Pastikan direktori ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Ambil data lama untuk menghapus file avatar yang lama dari storage
    $query_old = "SELECT avatar FROM testimonials WHERE id = '$id'";
    $result_old = mysqli_query($conn, $query_old);
    $old_data = mysqli_fetch_assoc($result_old);

    $file_info = pathinfo($_FILES["avatar"]["name"]);
    $file_ext = strtolower($file_info['extension']);
    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($file_ext, $allowed_ext)) {
        // Hapus file lama jika ada
        if ($old_data['avatar'] && file_exists($target_dir . $old_data['avatar'])) {
            unlink($target_dir . $old_data['avatar']);
        }

        // Generate nama file baru yang unik
        $new_avatar_name = "user_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $file_ext;
        $target_file = $target_dir . $new_avatar_name;

        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            $avatar_update_sql = ", avatar = '$new_avatar_name'";
        }
    }
}

// 4. Query Update ke Database
$sql = "UPDATE testimonials SET 
        name = '$name', 
        role = '$role', 
        stars = '$stars', 
        content = '$content' 
        $avatar_update_sql 
        WHERE id = '$id'";

if (mysqli_query($conn, $sql)) {
    // 5. Catat Log Aktivitas Admin (Opsional)
    $admin_id = $_SESSION['admin_id'];
    $activity = mysqli_real_escape_string($conn, "Updated testimonial ID: $id ($name)");
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $sql_log = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                VALUES ('$admin_id', '$activity', 'info', '$ip', NOW())";
    mysqli_query($conn, $sql_log);

    // Redirect dengan status 'updated' untuk memicu alert hijau
    header("Location: ../testimonials.php?status=updated");
} else {
    // Redirect dengan status error jika query gagal
    header("Location: ../testimonials.php?status=error");
}

exit();