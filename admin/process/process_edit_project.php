<?php
require_once 'config.php';
session_start();

// 1. Proteksi Admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id   = $_SESSION['admin_id'];
    $ip_address = $_SERVER['REMOTE_ADDR']; // Alamat IP Admin
    
    // 2. Ambil Data dari Form
    $id             = intval($_POST['id']);
    $project_name   = mysqli_real_escape_string($conn, $_POST['project_name']);
    $genre          = mysqli_real_escape_string($conn, $_POST['genre']);
    $status         = mysqli_real_escape_string($conn, $_POST['status']);
    $progress       = intval($_POST['progress']);
    $brief          = mysqli_real_escape_string($conn, $_POST['brief']);
    
    // Konversi Platform Array ke JSON untuk Database
    $platforms_json = isset($_POST['platforms']) ? json_encode($_POST['platforms']) : json_encode([]);

    // 3. Ambil data lama untuk komparasi Log & penghapusan file lama
    $old_stmt = $conn->prepare("SELECT project_name, project_icon, genre, status, progress, platforms FROM projects WHERE id = ?");
    $old_stmt->bind_param("i", $id);
    $old_stmt->execute();
    $old_data = $old_stmt->get_result()->fetch_assoc();

    if (!$old_data) {
        header("Location: ../project_manager.php?status=error&msg=Project not found");
        exit();
    }

    $final_icon = $old_data['project_icon'];

    // 4. Proses Upload Icon Baru
    if (isset($_FILES['project_icon']) && $_FILES['project_icon']['error'] === 0) {
        $target_dir = "../../uploads/projects/";
        $file_ext = pathinfo($_FILES['project_icon']['name'], PATHINFO_EXTENSION);
        $new_file_name = "icon_" . time() . "_" . uniqid() . "." . $file_ext;

        if (in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png', 'webp'])) {
            if (move_uploaded_file($_FILES['project_icon']['tmp_name'], $target_dir . $new_file_name)) {
                // Hapus file fisik lama jika ada agar folder upload tidak penuh
                if (!empty($old_data['project_icon']) && file_exists($target_dir . $old_data['project_icon'])) {
                    unlink($target_dir . $old_data['project_icon']);
                }
                $final_icon = $new_file_name;
            }
        }
    }

    // 5. Update Database Projects
    $stmt = $conn->prepare("UPDATE projects SET project_name=?, project_icon=?, genre=?, status=?, progress=?, platforms=?, brief=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param("ssssissi", $project_name, $final_icon, $genre, $status, $progress, $platforms_json, $brief, $id);

    if ($stmt->execute()) {
        
        // 6. DETEKSI PERUBAHAN DATA (Audit Trail)
        $diff = [];
        if ($old_data['project_name'] !== $project_name) {
            $diff[] = "Name: '{$old_data['project_name']}' → '$project_name'";
        }
        if ($old_data['genre'] !== $genre) {
            $diff[] = "Genre: '{$old_data['genre']}' → '$genre'";
        }
        if ($old_data['status'] !== $status) {
            $diff[] = "Status: '{$old_data['status']}' → '$status'";
        }
        if (intval($old_data['progress']) !== $progress) {
            $diff[] = "Progress: {$old_data['progress']}% → $progress%";
        }
        if ($final_icon !== $old_data['project_icon']) {
            $diff[] = "Icon changed";
        }
        if ($old_data['platforms'] !== $platforms_json) {
            $diff[] = "Platforms updated";
        }

        // Susun Pesan Aktivitas
        if (empty($diff)) {
            $log_activity = "Updated project '{$project_name}' (ID: $id) with no value changes";
        } else {
            $log_activity = "Updated '{$project_name}' (ID: $id): " . implode(", ", $diff);
        }

        // 7. PENCATATAN KE TABEL admin_logs
        $log_type = "info";
        $stmt_log = $conn->prepare("INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt_log->bind_param("isss", $admin_id, $log_activity, $log_type, $ip_address);
        $stmt_log->execute();

        header("Location: ../project_manager.php?status=success&msg=Project updated successfully");
    } else {
        header("Location: ../project_manager.php?status=error&msg=Database error occurred");
    }
} else {
    header("Location: ../project_manager.php");
}
exit();