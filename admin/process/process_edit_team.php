<?php
require_once './config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['admin_id'])) {
        http_response_code(403);
        echo "Unauthorized";
        exit();
    }

    $admin_id = $_SESSION['admin_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $division = mysqli_real_escape_string($conn, $_POST['division']);
    $level = mysqli_real_escape_string($conn, $_POST['level']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // 1. Ambil data lama untuk perbandingan log & hapus foto
    $query_old = "SELECT * FROM team WHERE id = '$id'";
    $result_old = mysqli_query($conn, $query_old);
    $old_data = mysqli_fetch_assoc($result_old);

    if (!$old_data) {
        echo "Staff not found";
        exit();
    }

    // 2. Deteksi Perubahan Data untuk Activity Log
    $changes = [];
    if ($old_data['name'] !== $name) $changes[] = "Nama (" . $old_data['name'] . " → " . $name . ")";
    if ($old_data['division'] !== $division) $changes[] = "Divisi (" . $old_data['division'] . " → " . $division . ")";
    if ($old_data['level'] !== $level) $changes[] = "Level (" . $old_data['level'] . " → " . $level . ")";
    if ($old_data['status'] !== $status) $changes[] = "Status (" . $old_data['status'] . " → " . $status . ")";
    if ($old_data['email'] !== $email) $changes[] = "Email (" . $old_data['email'] . " → " . $email . ")";
    if ($old_data['phone'] !== $phone) $changes[] = "WhatsApp (" . $old_data['phone'] . " → " . $phone . ")";

    $image_name = $old_data['member_image']; 

    // 3. Logika Upload Foto
    if (isset($_FILES['member_image']) && $_FILES['member_image']['error'] === 0) {
        $target_dir = "../../uploads/team/";
        $file_extension = pathinfo($_FILES['member_image']['name'], PATHINFO_EXTENSION);
        $new_file_name = "team_" . time() . "_" . uniqid() . "." . $file_extension;
        $target_file = $target_dir . $new_file_name;

        $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array(strtolower($file_extension), $allowed_types)) {
            if (move_uploaded_file($_FILES['member_image']['tmp_name'], $target_file)) {
                if (!empty($old_data['member_image']) && file_exists($target_dir . $old_data['member_image'])) {
                    unlink($target_dir . $old_data['member_image']);
                }
                $image_name = $new_file_name;
                $changes[] = "Foto Profil diupdate";
            }
        }
    }

    // 4. Update Database
    $update_query = "UPDATE team SET 
            name = '$name', division = '$division', level = '$level', 
            status = '$status', email = '$email', phone = '$phone', 
            member_image = '$image_name' WHERE id = '$id'";

    if (mysqli_query($conn, $update_query)) {
        
        // 5. MEKANISME ADMIN LOGS (Success)
        // Jika tidak ada perubahan sama sekali, tulis "No data changed"
        $detail_changes = !empty($changes) ? implode(", ", $changes) : "No specific field changed (Re-saved)";
        $activity = "Updated staff: " . $old_data['name'] . ". Changes: " . $detail_changes;
        $type = "success"; 

        $log_query = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                      VALUES ('$admin_id', '$activity', '$type', '$ip_address', NOW())";
        mysqli_query($conn, $log_query);

        header("Location: ../team.php?status=updated");
    } else {
        // 6. MEKANISME ADMIN LOGS (Danger/Error)
        $error_msg = "Failed to update staff " . $old_data['name'] . ": " . mysqli_error($conn);
        $log_error = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                      VALUES ('$admin_id', '$error_msg', 'danger', '$ip_address', NOW())";
        mysqli_query($conn, $log_error);

        http_response_code(500);
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: ../team.php");
}
exit();