<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $admin_id = $_SESSION['admin_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // 1. Ambil DATA LAMA dari database untuk perbandingan
    $stmt_old = $conn->prepare("SELECT * FROM project_timelines WHERE id = ?");
    $stmt_old->bind_param("i", $id);
    $stmt_old->execute();
    $old_data = $stmt_old->get_result()->fetch_assoc();

    if (!$old_data) {
        header("Location: ../project_timeline.php?status=error&msg=Data not found");
        exit();
    }

    // 2. Ambil DATA BARU dari POST
    $new_project_name  = $_POST['project_name'];
    $new_client_id     = !empty($_POST['client_id']) ? intval($_POST['client_id']) : null;
    $new_priority      = $_POST['priority'];
    $new_deadline_date = $_POST['deadline_date'];
    $new_deadline_time = $_POST['deadline_time'];
    $new_brief_link    = $_POST['brief_link'];
    $new_color_label   = $_POST['color_label'];
    $new_notes         = $_POST['notes'];
    $new_team_tags     = isset($_POST['team_tag']) ? json_encode($_POST['team_tag']) : json_encode([]);

    // 3. IDENTIFIKASI PERUBAHAN
    $changes = [];
    if ($old_data['project_name'] != $new_project_name)   $changes[] = "Nama Project: '{$old_data['project_name']}' -> '$new_project_name'";
    if ($old_data['priority'] != $new_priority)           $changes[] = "Priority: '{$old_data['priority']}' -> '$new_priority'";
    if ($old_data['deadline_date'] != $new_deadline_date) $changes[] = "Deadline: '{$old_data['deadline_date']}' -> '$new_deadline_date'";
    if ($old_data['status'] != ($_POST['status'] ?? $old_data['status'])) $changes[] = "Status berubah";
    
    // Jika tidak ada perubahan sama sekali
    if (empty($changes)) {
        $log_activity = "Update Project: '{$new_project_name}' (Tidak ada data yang diubah)";
    } else {
        $log_activity = "Update Project '{$new_project_name}': " . implode(", ", $changes);
    }

    // 4. EKSEKUSI UPDATE
    $sql_update = "UPDATE project_timelines SET 
                    project_name = ?, client_id = ?, priority = ?, 
                    deadline_date = ?, deadline_time = ?, brief_link = ?, 
                    color_label = ?, notes = ?, team_tags = ?, updated_at = NOW()
                   WHERE id = ?";

    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sisssssssi", 
        $new_project_name, $new_client_id, $new_priority, 
        $new_deadline_date, $new_deadline_time, $new_brief_link, 
        $new_color_label, $new_notes, $new_team_tags, $id
    );

    if ($stmt->execute()) {
        // 5. PENCATATAN LOG KE TABEL admin_logs
        $log_type = "info";
        $stmt_log = $conn->prepare("INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt_log->bind_param("isss", $admin_id, $log_activity, $log_type, $ip_address);
        $stmt_log->execute();

        header("Location: ../project_timeline.php?status=success&msg=Update successful");
    } else {
        header("Location: ../project_timeline.php?status=error&msg=Database error");
    }
} else {
    header("Location: ../project_timeline.php");
}
exit();