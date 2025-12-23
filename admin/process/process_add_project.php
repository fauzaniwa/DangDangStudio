<?php
require_once 'config.php';
session_start();

// Proteksi akses langsung
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Ambil & Sanitasi Data
    $project_name = mysqli_real_escape_string($conn, $_POST['project_name']);
    $genre        = mysqli_real_escape_string($conn, $_POST['genre']);
    $status       = mysqli_real_escape_string($conn, $_POST['status']);
    $progress     = intval($_POST['progress']);
    $brief        = mysqli_real_escape_string($conn, $_POST['brief']);
    $platforms    = isset($_POST['platforms']) ? json_encode($_POST['platforms']) : json_encode([]);

    $admin_id     = $_SESSION['admin_id'];
    $ip_address   = $_SERVER['REMOTE_ADDR'];

    // 2. Logika Upload Icon
    $icon_name = "default_icon.png"; 
    if (isset($_FILES['project_icon']) && $_FILES['project_icon']['error'] == 0) {
        // Path disesuaikan: keluar folder 'process' lalu masuk ke 'uploads/projects'
        $target_dir = "../../uploads/projects/";
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_info = pathinfo($_FILES["project_icon"]["name"]);
        $file_ext  = strtolower($file_info['extension']);
        $allowed   = ['jpg', 'jpeg', 'png', 'webp'];

        // Validasi Ekstensi & Ukuran (Max 2MB)
        if (in_array($file_ext, $allowed) && $_FILES["project_icon"]["size"] < 2000000) {
            $icon_name   = "icon_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $file_ext;
            $target_file = $target_dir . $icon_name;

            if (!move_uploaded_file($_FILES["project_icon"]["tmp_name"], $target_file)) {
                $icon_name = "default_icon.png"; // Fallback jika gagal upload
            }
        }
    }

    // 3. Simpan ke Database (Prepared Statements)
    $sql  = "INSERT INTO projects (project_name, project_icon, genre, status, progress, platforms, brief, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiss", 
        $project_name, 
        $icon_name, 
        $genre, 
        $status, 
        $progress, 
        $platforms, 
        $brief
    );

    if ($stmt->execute()) {
        // 4. Catat ke Admin Logs
        $log_activity = "Berhasil meluncurkan project baru: $project_name";
        $log_type     = "success";
        
        $log_stmt = $conn->prepare("INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
        $log_stmt->bind_param("isss", $admin_id, $log_activity, $log_type, $ip_address);
        $log_stmt->execute();

        header("Location: ../project_manager.php?status=success&msg=Project launched successfully!");
    } else {
        // Log Error jika gagal query
        $log_error = "Gagal menambah project: " . $conn->error;
        $log_stmt = $conn->prepare("INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) VALUES (?, ?, 'danger', ?, NOW())");
        $log_stmt->bind_param("iss", $admin_id, $log_error, $ip_address);
        $log_stmt->execute();

        header("Location: ../project_manager.php?status=error&msg=Failed to save project");
    }
} else {
    header("Location: ../project_manager.php");
}
exit();