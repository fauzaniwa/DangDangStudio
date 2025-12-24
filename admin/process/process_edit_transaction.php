<?php
require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $id          = mysqli_real_escape_string($conn, $_POST['id']);
    $type        = mysqli_real_escape_string($conn, $_POST['type']);
    $amount      = preg_replace('/[^0-9]/', '', $_POST['amount']);
    $category    = mysqli_real_escape_string($conn, $_POST['category']);
    $date        = mysqli_real_escape_string($conn, $_POST['date']);
    $description = mysqli_real_escape_string($conn, $_POST['desc']);
    
    // Ambil data lama untuk cek file
    $old_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT attachment FROM financial_transactions WHERE id = '$id'"));

    $attachment_sql = "";
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
        $target_dir = "../uploads/finance/";
        $file_ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
        $new_filename = "FIN_EDIT_" . date('Ymd_His') . "_" . uniqid() . "." . $file_ext;

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_dir . $new_filename)) {
            if ($old_data['attachment'] && file_exists($target_dir . $old_data['attachment'])) {
                unlink($target_dir . $old_data['attachment']);
            }
            $attachment_sql = ", attachment = '$new_filename'";
        }
    }

    $sql = "UPDATE financial_transactions SET 
            type = '$type', amount = '$amount', category = '$category', 
            transaction_date = '$date', description = '$description' $attachment_sql
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        // Log Audit
        $action_type = ($type === 'in') ? "Income" : "Expense";
        $log_activity = "Updated $action_type #$id: $description (Rp " . number_format($amount, 0, ',', '.') . ")";
        $ip_address = $_SERVER['REMOTE_ADDR'];

        $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) 
                    VALUES ('$admin_id', '$log_activity', 'success', '$ip_address')";
        mysqli_query($conn, $log_sql);

        header("Location: ../financial_report.php?status=updated");
    } else {
        header("Location: ../financial_report.php?status=error&msg=Database_Error");
    }
} else {
    header("Location: ../financial_report.php");
}