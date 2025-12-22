<?php
require_once 'config.php';
session_start();

// Ambil IP Address User
$ip_address = $_SERVER['REMOTE_ADDR'];

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = $_SESSION['admin_id'];
    
    // 1. Ambil data LAMA untuk perbandingan
    $stmt_old = $conn->prepare("SELECT fullname, email, profile_picture FROM admins WHERE id = ?");
    $stmt_old->bind_param("i", $admin_id);
    $stmt_old->execute();
    $old_data = $stmt_old->get_result()->fetch_assoc();

    // 2. Tangkap data BARU
    $new_fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $changes = []; 
    $update_fields = [];
    $types_param = "";
    $params = [];

    // Cek perubahan Nama
    if ($new_fullname !== $old_data['fullname']) {
        $update_fields[] = "fullname = ?";
        $params[] = $new_fullname;
        $types_param .= "s";
        $changes[] = "Nama diubah ({$old_data['fullname']} -> {$new_fullname})";
    }

    // Cek perubahan Email
    if ($new_email !== $old_data['email']) {
        $update_fields[] = "email = ?";
        $params[] = $new_email;
        $types_param .= "s";
        $changes[] = "Email diubah ({$old_data['email']} -> {$new_email})";
    }

    // Cek perubahan Password
    if (!empty($new_password)) {
        if ($new_password === $confirm_password) {
            $hashed = password_hash($new_password, PASSWORD_BCRYPT);
            $update_fields[] = "password = ?";
            $params[] = $hashed;
            $types_param .= "s";
            $changes[] = "Password diperbarui";
        } else {
            // Log Gagal karena password tidak cocok
            $log_act = "Gagal update profil: Konfirmasi password tidak cocok";
            $log_type = "warning";
            $stmt_log = $conn->prepare("INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt_log->bind_param("isss", $admin_id, $log_act, $log_type, $ip_address);
            $stmt_log->execute();

            header("Location: ../edit_profile.php?status=pass_mismatch");
            exit();
        }
    }

    // Cek Foto Profil
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $filename = "AVATAR_" . $admin_id . "_" . time() . "." . $ext;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], "../uploads/profiles/" . $filename)) {
            $update_fields[] = "profile_picture = ?";
            $params[] = $filename;
            $types_param .= "s";
            $changes[] = "Foto profil diperbarui";
        }
    }

    // 3. Jalankan Update
    if (!empty($update_fields)) {
        $sql = "UPDATE admins SET " . implode(", ", $update_fields) . ", updated_at = NOW() WHERE id = ?";
        $params[] = $admin_id;
        $types_param .= "i";

        $stmt_update = $conn->prepare($sql);
        $stmt_update->bind_param($types_param, ...$params);

        if ($stmt_update->execute()) {
            // PENCATATAN LOG KE TABEL admin_logs
            $log_activity = "Update Profil Berhasil: " . implode(", ", $changes);
            $log_type = "info";
            
            $stmt_log = $conn->prepare("INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt_log->bind_param("isss", $admin_id, $log_activity, $log_type, $ip_address);
            $stmt_log->execute();

            // Update session nama jika berubah
            $_SESSION['admin_fullname'] = $new_fullname;

            header("Location: ../edit_profile.php?status=success");
        } else {
            // Log Error Sistem
            $log_activity = "Error sistem saat update profil";
            $log_type = "danger";
            $stmt_log = $conn->prepare("INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt_log->bind_param("isss", $admin_id, $log_activity, $log_type, $ip_address);
            $stmt_log->execute();

            header("Location: ../edit_profile.php?status=error");
        }
    } else {
        header("Location: ../edit_profile.php");
    }
}
exit();