<?php
require_once 'config.php';
session_start();

// 1. Validasi Keamanan & Sesi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $tx_data = $_POST['tx'] ?? [];
    $target_dir = "../uploads/finance/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Gunakan Transaction agar jika salah satu gagal, semua dibatalkan
    mysqli_begin_transaction($conn);

    try {
        foreach ($tx_data as $index => $item) {
            // Sanitasi Input
            $raw_type    = strtolower($item['type']);
            $type        = (in_array($raw_type, ['in', 'pemasukan'])) ? 'in' : 'out';
            $amount      = preg_replace('/[^0-9]/', '', $item['amount']);
            $category    = mysqli_real_escape_string($conn, $item['category']);
            $date        = mysqli_real_escape_string($conn, $item['date']);
            $description = mysqli_real_escape_string($conn, $item['desc']);
            
            // Penanganan Upload File
            $attachment = "";
            $file_key = "file_" . $index;
            if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === 0) {
                $file_ext   = pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION);
                $attachment = "FIN_" . date('Ymd_His') . "_" . $index . "_" . uniqid() . "." . $file_ext;
                move_uploaded_file($_FILES[$file_key]['tmp_name'], $target_dir . $attachment);
            }

            // 2. Insert ke tabel financial_transactions
            $sql = "INSERT INTO financial_transactions 
                    (type, amount, description, category, transaction_date, attachment, admin_id) 
                    VALUES ('$type', '$amount', '$description', '$category', '$date', '$attachment', '$admin_id')";
            
            if (mysqli_query($conn, $sql)) {
                // 3. Catat ke Admin Logs untuk Audit (Sesuai Permintaan)
                $action_type = ($type === 'in') ? "Income" : "Expense";
                $log_activity = "Added $action_type: $description (Rp " . number_format($amount, 0, ',', '.') . ")";
                $ip_address = $_SERVER['REMOTE_ADDR'];

                $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) 
                            VALUES ('$admin_id', '$log_activity', 'success', '$ip_address')";
                mysqli_query($conn, $log_sql);
            } else {
                throw new Exception("Database error on row " . ($index + 1));
            }
        }

        // Jika semua baris berhasil diproses
        mysqli_commit($conn);
        header("Location: ../financial_report.php?status=success");
        exit();

    } catch (Exception $e) {
        // Jika ada satu saja yang gagal, batalkan semua perubahan
        mysqli_rollback($conn);
        header("Location: ../financial_report.php?status=error&msg=" . urlencode($e->getMessage()));
        exit();
    }

} else {
    // Jika akses langsung ke file tanpa POST atau tanpa sesi
    header("Location: ../financial_report.php");
    exit();
}