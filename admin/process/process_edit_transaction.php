<?php
require_once './config.php';
session_start();

if (!isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../financial_report.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$id = mysqli_real_escape_string($conn, $_POST['id']);
$type = mysqli_real_escape_string($conn, $_POST['type']);
$amount = mysqli_real_escape_string($conn, $_POST['amount']);
$category = mysqli_real_escape_string($conn, $_POST['category']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$date = mysqli_real_escape_string($conn, $_POST['date']);

// Logika File Attachment
$file_query_part = "";
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
    $target_dir = "../../uploads/finance/";
    
    // Hapus file lama
    $old_res = mysqli_query($conn, "SELECT attachment FROM financial_transactions WHERE id = '$id'");
    $old_data = mysqli_fetch_assoc($old_res);
    if (!empty($old_data['attachment']) && file_exists($target_dir . $old_data['attachment'])) {
        unlink($target_dir . $old_data['attachment']);
    }

    $file_ext = pathinfo($_FILES["attachment"]["name"], PATHINFO_EXTENSION);
    $new_filename = "FIN_" . time() . "_" . rand(100, 999) . "." . $file_ext;
    if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_dir . $new_filename)) {
        $file_query_part = ", attachment='$new_filename'";
    }
}

// 3. Eksekusi Update
$sql_update = "UPDATE financial_transactions SET 
               type='$type', amount='$amount', category='$category', 
               description='$description', transaction_date='$date' 
               $file_query_part WHERE id='$id'";

if (mysqli_query($conn, $sql_update)) {
    // --- 4. Catat ke Admin Logs untuk Audit ---
    $action_type = ($type === 'in') ? "Income" : "Expense";
    $log_activity = "Updated $action_type: $description (Rp " . number_format($amount, 0, ',', '.') . ")";
    $ip_address = $_SERVER['REMOTE_ADDR'];

    $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) 
                VALUES ('$admin_id', '$log_activity', 'success', '$ip_address')";
    mysqli_query($conn, $log_sql);

    header("Location: ../financial_report.php?status=updated");
} else {
    header("Location: ../financial_report.php?status=db_error");
}
exit();