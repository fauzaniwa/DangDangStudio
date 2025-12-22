<?php
require_once './config.php';
session_start();

// Proteksi: Pastikan Admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Tangkap dan bersihkan data (Sanitization)
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $category     = mysqli_real_escape_string($conn, $_POST['category']);
    $website      = mysqli_real_escape_string($conn, $_POST['website']);
    $pic_name     = mysqli_real_escape_string($conn, $_POST['pic_name']);
    $email        = mysqli_real_escape_string($conn, $_POST['email']);
    $phone        = mysqli_real_escape_string($conn, $_POST['phone']);
    $position     = mysqli_real_escape_string($conn, $_POST['position']);
    $admin_id     = $_SESSION['admin_id'];
    $ip_address   = $_SERVER['REMOTE_ADDR'];

    // 2. Query Insert ke tabel clients
    // Pastikan nama kolom di tabel Anda sesuai (company_name, category, website, pic_name, email, phone, position)
    $sql = "INSERT INTO clients (company_name, category, website, pic_name, email, phone, position) 
            VALUES ('$company_name', '$category', '$website', '$pic_name', '$email', '$phone', '$position')";

    if (mysqli_query($conn, $sql)) {
        $client_id = mysqli_insert_id($conn);

        // 3. Catat aktivitas ke Admin Logs (Audit Trail)
        $activity = "Registered new client: $company_name";
        $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) 
                    VALUES ('$admin_id', '$activity', 'info', '$ip_address')";
        mysqli_query($conn, $log_sql);

        // Redirect sukses
        header("Location: ../client_list.php?status=success&msg=Client registered successfully");
    } else {
        // Redirect gagal
        header("Location: ../client_add.php?status=error&msg=" . urlencode(mysqli_error($conn)));
    }
} else {
    header("Location: ../client_add.php");
}
exit();