<?php
require_once './config.php';
session_start();

// 1. Proteksi Akses
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['admin_id'])) {
    header("Location: ../testimonials.php");
    exit();
}

// 2. Ambil Data Form
$name = mysqli_real_escape_string($conn, $_POST['name']);
$role = mysqli_real_escape_string($conn, $_POST['role']);
$stars = intval($_POST['stars']);
$content = mysqli_real_escape_string($conn, $_POST['content']);
$avatar_name = null; // Default jika tidak ada foto

// 3. Proses Upload Avatar (Jika ada)
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
    $target_dir = "../../uploads/testimonials/";
    
    // Pastikan direktori ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_info = pathinfo($_FILES["avatar"]["name"]);
    $file_ext = strtolower($file_info['extension']);
    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($file_ext, $allowed_ext)) {
        // Generate nama file unik: user_timestamp_random.ext
        $avatar_name = "user_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $file_ext;
        $target_file = $target_dir . $avatar_name;

        if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            // Jika gagal upload, set null atau berikan error
            $avatar_name = null;
        }
    }
}

// 4. Query Insert ke Database
$sql = "INSERT INTO testimonials (name, role, avatar, stars, content, created_at) 
        VALUES ('$name', '$role', " . ($avatar_name ? "'$avatar_name'" : "NULL") . ", '$stars', '$content', NOW())";

if (mysqli_query($conn, $sql)) {
    // 5. Catat Log Aktivitas Admin
    $admin_id = $_SESSION['admin_id'];
    $admin_name = $_SESSION['admin_name'] ?? 'Admin';
    $activity = mysqli_real_escape_string($conn, "Added new testimonial from: $name");
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $sql_log = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                VALUES ('$admin_id', '$activity', 'success', '$ip', NOW())";
    mysqli_query($conn, $sql_log);

    // Redirect sukses
    header("Location: ../testimonials.php?status=success&msg=Testimonial+published+successfully");
} else {
    // Redirect gagal
    $error = mysqli_real_escape_string($conn, mysqli_error($conn));
    header("Location: ../testimonial_add.php?status=error&msg=" . urlencode($error));
}

exit();