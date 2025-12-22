<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id           = mysqli_real_escape_string($conn, $_POST['id']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $category     = mysqli_real_escape_string($conn, $_POST['category']);
    $website      = mysqli_real_escape_string($conn, $_POST['website']);
    $pic_name     = mysqli_real_escape_string($conn, $_POST['pic_name']);
    $email        = mysqli_real_escape_string($conn, $_POST['email']);
    $phone        = mysqli_real_escape_string($conn, $_POST['phone']);
    $position     = mysqli_real_escape_string($conn, $_POST['position']);
    
    $admin_id     = $_SESSION['admin_id'];
    $ip_address   = $_SERVER['REMOTE_ADDR'];

    // Query Update dengan kolom updated_at
    $sql = "UPDATE clients SET 
                company_name = '$company_name', 
                category     = '$category', 
                website      = '$website', 
                pic_name     = '$pic_name', 
                email        = '$email', 
                phone        = '$phone', 
                position     = '$position',
                updated_at   = NOW() 
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        $activity = "Updated client data: $company_name";
        $log_sql  = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) 
                     VALUES ('$admin_id', '$activity', 'info', '$ip_address')";
        mysqli_query($conn, $log_sql);

        header("Location: ../client_list.php?status=success&msg=Client data updated successfully");
    } else {
        header("Location: ../client_edit.php?id=$id&status=error&msg=" . urlencode(mysqli_error($conn)));
    }
} else {
    header("Location: ../client_list.php");
}
exit();