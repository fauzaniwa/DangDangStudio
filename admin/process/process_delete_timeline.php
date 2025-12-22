<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $admin_id = $_SESSION['admin_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    // 1. Ambil nama project dulu sebelum dihapus untuk keperluan log
    $res = mysqli_query($conn, "SELECT project_name FROM project_timelines WHERE id = '$id'");
    $data = mysqli_fetch_assoc($res);
    $project_name = $data['project_name'];

    // 2. Eksekusi Hapus
    $sql = "DELETE FROM project_timelines WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        // 3. Log Aktivitas Hapus
        $activity = "Deleted project timeline: $project_name";
        $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) 
                    VALUES ('$admin_id', '$activity', 'danger', '$ip_address')";
        mysqli_query($conn, $log_sql);

        header("Location: ../project_timeline.php?status=success&msg=Task deleted successfully");
    } else {
        header("Location: ../project_timeline.php?status=error&msg=Failed to delete task");
    }
} else {
    header("Location: ../project_timeline.php");
}
exit();