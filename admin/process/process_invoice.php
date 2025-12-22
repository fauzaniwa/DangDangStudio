<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../invoice_maker.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$ip_address = $_SERVER['REMOTE_ADDR'];

// 1. Tangkap Data Utama
$client_id    = mysqli_real_escape_string($conn, $_POST['client_id']);
$invoice_no   = mysqli_real_escape_string($conn, $_POST['invoice_no']);
$due_date     = mysqli_real_escape_string($conn, $_POST['due_date']);
$notes        = mysqli_real_escape_string($conn, $_POST['notes']);
$grand_total  = mysqli_real_escape_string($conn, $_POST['total_amount']);
$invoice_date = date('Y-m-d');

// Hitung Tax 11% dari total asli sebelum grand total (opsional, untuk database)
$tax_amount = $grand_total - ($grand_total / 1.11);

// 2. Mulai Transaction Database
mysqli_begin_transaction($conn);

try {
    // A. Simpan ke tabel invoices
    $sql_invoice = "INSERT INTO invoices (invoice_no, client_id, invoice_date, due_date, total_amount, tax_amount, notes, status) 
                    VALUES ('$invoice_no', '$client_id', '$invoice_date', '$due_date', '$grand_total', '$tax_amount', '$notes', 'unpaid')";
    
    if (!mysqli_query($conn, $sql_invoice)) {
        throw new Exception("Gagal menyimpan header invoice.");
    }

    // Ambil ID invoice yang baru saja masuk
    $invoice_id = mysqli_insert_id($conn);

    // B. Simpan Detail Item (Looping)
    $descriptions = $_POST['desc'];
    $qtys         = $_POST['qty'];
    $prices       = $_POST['price'];

    foreach ($descriptions as $index => $desc) {
        $desc_clean  = mysqli_real_escape_string($conn, $desc);
        $qty_clean   = mysqli_real_escape_string($conn, $qtys[$index]);
        $price_clean = mysqli_real_escape_string($conn, $prices[$index]);
        $row_total   = $qty_clean * $price_clean;

        $sql_item = "INSERT INTO invoice_items (invoice_id, description, qty, price, row_total) 
                     VALUES ('$invoice_id', '$desc_clean', '$qty_clean', '$price_clean', '$row_total')";
        
        if (!mysqli_query($conn, $sql_item)) {
            throw new Exception("Gagal menyimpan item invoice baris ke-" . ($index + 1));
        }
    }

    // C. Catat ke Admin Logs (Sesuai format Anda)
    $log_activity = "Created Invoice: $invoice_no (Grand Total: Rp " . number_format($grand_total, 0, ',', '.') . ")";
    $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) 
                VALUES ('$admin_id', '$log_activity', 'success', '$ip_address')";
    mysqli_query($conn, $log_sql);

    // Jika sampai sini tidak ada error, commit semua data
    mysqli_commit($conn);
    header("Location: ../invoice_maker.php?status=success");

} catch (Exception $e) {
    // Jika ada yang gagal, batalkan semua perubahan di database
    mysqli_rollback($conn);
    
    // Log kegagalan jika perlu
    $log_fail = "Failed to Create Invoice: $invoice_no Error: " . $e->getMessage();
    mysqli_query($conn, "INSERT INTO admin_logs (admin_id, activity, type, ip_address) VALUES ('$admin_id', '$log_fail', 'error', '$ip_address')");
    
    header("Location: ../invoice_create.php?status=error&msg=" . urlencode($e->getMessage()));
}

exit();