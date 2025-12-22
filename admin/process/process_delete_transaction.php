<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id']) || !isset($_GET['id'])) {
    header("Location: ../financial_report.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$id = mysqli_real_escape_string($conn, $_GET['id']);

// 1. Ambil data sebelum dihapus untuk kebutuhan Log & File
$sql_data = "SELECT description, amount, type, attachment FROM financial_transactions WHERE id = '$id'";
$res_data = mysqli_query($conn, $sql_data);
$data = mysqli_fetch_assoc($res_data);

if ($data) {
    // Hapus file fisik jika ada
    if (!empty($data['attachment'])) {
        $path = "../../uploads/finance/" . $data['attachment'];
        if (file_exists($path)) { unlink($path); }
    }

    // 2. Eksekusi Hapus
    $sql_delete = "DELETE FROM financial_transactions WHERE id = '$id'";
    
    if (mysqli_query($conn, $sql_delete)) {
        // --- 4. Catat ke Admin Logs untuk Audit ---
        $action_type = ($data['type'] === 'in') ? "Income" : "Expense";
        $log_activity = "Deleted $action_type: " . $data['description'] . " (Rp " . number_format($data['amount'], 0, ',', '.') . ")";
        $ip_address = $_SERVER['REMOTE_ADDR'];

        $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) 
                    VALUES ('$admin_id', '$log_activity', 'success', '$ip_address')";
        mysqli_query($conn, $log_sql);

        header("Location: ../financial_report.php?status=deleted");
    } else {
        header("Location: ../financial_report.php?status=db_error");
    }
} else {
    header("Location: ../financial_report.php?status=not_found");
}
exit();