<?php
require_once './config.php';
session_start();

// Cek apakah request dikirim melalui POST dan admin sudah login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    // 1. Ambil dan Sanitize Data Input
    $type           = mysqli_real_escape_string($conn, $_POST['type']); // 'in' atau 'out'
    $amount         = mysqli_real_escape_string($conn, $_POST['amount']);
    $description    = mysqli_real_escape_string($conn, $_POST['description']);
    $category       = mysqli_real_escape_string($conn, $_POST['category']);
    $transaction_date = mysqli_real_escape_string($conn, $_POST['date']);
    
    $attachment_name = null; // Default jika tidak ada upload

    // 2. Logika Upload File (Attachment)
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
        $target_dir = "../../uploads/finance/";
        
        // Buat folder jika belum ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = $_FILES["attachment"]["name"];
        $file_size = $_FILES["attachment"]["size"];
        $file_tmp  = $_FILES["attachment"]["tmp_name"];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Tentukan ekstensi yang diperbolehkan
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
        
        // Validasi Ekstensi dan Ukuran (Maks 2MB)
        if (in_array($file_ext, $allowed_extensions) && $file_size <= 2097152) {
            // Berikan nama unik untuk file guna menghindari duplikasi
            $attachment_name = "trx_" . time() . "_" . uniqid() . "." . $file_ext;
            $target_file = $target_dir . $attachment_name;

            if (!move_uploaded_file($file_tmp, $target_file)) {
                // Jika gagal upload, arahkan kembali dengan pesan error
                header("Location: ../financial_report.php?status=upload_failed");
                exit();
            }
        } else {
            // Jika format tidak sesuai atau file terlalu besar
            header("Location: ../financial_report.php?status=invalid_file");
            exit();
        }
    }

    // 3. Query Insert ke Database
    $sql = "INSERT INTO financial_transactions (
                type, 
                amount, 
                description, 
                category, 
                transaction_date, 
                attachment, 
                admin_id
            ) VALUES (
                '$type', 
                '$amount', 
                '$description', 
                '$category', 
                '$transaction_date', 
                '$attachment_name', 
                '$admin_id'
            )";

    if (mysqli_query($conn, $sql)) {
        // 4. Catat ke Admin Logs untuk Audit
        $action_type = ($type === 'in') ? "Income" : "Expense";
        $log_activity = "Added $action_type: $description (Rp " . number_format($amount, 0, ',', '.') . ")";
        $ip_address = $_SERVER['REMOTE_ADDR'];

        $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) 
                    VALUES ('$admin_id', '$log_activity', 'success', '$ip_address')";
        mysqli_query($conn, $log_sql);

        // Redirect sukses
        header("Location: ../financial_report.php?status=success");
    } else {
        // Redirect gagal query
        header("Location: ../financial_report.php?status=db_error");
    }
} else {
    // Jika akses langsung ke file tanpa POST
    header("Location: ../financial_report.php");
}
exit();