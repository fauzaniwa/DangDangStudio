<?php
require_once 'config.php';
session_start();

// Proteksi Admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Ambil & Sanitasi Data Dasar
    $project_name  = mysqli_real_escape_string($conn, $_POST['project_name']);
    $client_id     = !empty($_POST['client_id']) ? mysqli_real_escape_string($conn, $_POST['client_id']) : "NULL";
    $priority      = mysqli_real_escape_string($conn, $_POST['priority']);
    $deadline_date = mysqli_real_escape_string($conn, $_POST['deadline_date']);
    $deadline_time = mysqli_real_escape_string($conn, $_POST['deadline_time']);
    $brief_link    = mysqli_real_escape_string($conn, $_POST['brief_link']);
    $color_label   = mysqli_real_escape_string($conn, $_POST['color_label']);
    $notes         = mysqli_real_escape_string($conn, $_POST['notes']);
    
    // 2. Olah Data Team Tags (Array to JSON)
    // Jika tidak ada tim yang dipilih, simpan sebagai array kosong
    $team_tags_array = isset($_POST['team_tag']) ? $_POST['team_tag'] : [];
    $team_tags_json  = mysqli_real_escape_string($conn, json_encode($team_tags_array));

    // 3. Query Insert
    // Perhatikan: client_id tidak memakai tanda petik jika nilainya NULL
    $sql = "INSERT INTO project_timelines (
                project_name, 
                client_id, 
                priority, 
                deadline_date, 
                deadline_time, 
                brief_link, 
                color_label, 
                notes, 
                team_tags, 
                status
            ) VALUES (
                '$project_name', 
                $client_id, 
                '$priority', 
                '$deadline_date', 
                '$deadline_time', 
                '$brief_link', 
                '$color_label', 
                '$notes', 
                '$team_tags_json', 
                'Pending'
            )";

    if (mysqli_query($conn, $sql)) {
        // 4. Log Aktivitas
        $admin_id   = $_SESSION['admin_id'];
        $activity   = "Created new project timeline: $project_name";
        $ip_address = $_SERVER['REMOTE_ADDR'];
        
        $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) 
                    VALUES ('$admin_id', '$activity', 'success', '$ip_address')";
        mysqli_query($conn, $log_sql);

        // Success Redirect
        header("Location: ../project_timeline.php?status=success&msg=New task has been published");
    } else {
        // Error Redirect
        header("Location: ../project_timeline_add.php?status=error&msg=" . urlencode(mysqli_error($conn)));
    }
} else {
    header("Location: ../project_timeline.php");
}
exit();