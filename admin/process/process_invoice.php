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
$client_id      = mysqli_real_escape_string($conn, $_POST['client_id']);
$invoice_no     = mysqli_real_escape_string($conn, $_POST['invoice_no']);
$due_date       = mysqli_real_escape_string($conn, $_POST['due_date']);
$notes          = mysqli_real_escape_string($conn, $_POST['notes']);
$tax_amount     = mysqli_real_escape_string($conn, $_POST['tax_amount']);

// PERUBAHAN: Tangkap tax_percentage dari form
// Jika toggle pajak dimatikan, kita pastikan nilainya 0
$tax_percentage = isset($_POST['tax_percentage']) ? mysqli_real_escape_string($conn, $_POST['tax_percentage']) : 0;

$total_amount   = mysqli_real_escape_string($conn, $_POST['total_amount']);
$invoice_date   = date('Y-m-d');

// 2. Mulai Transaction
mysqli_begin_transaction($conn);

try {
    // A. Simpan ke tabel invoices (Menambahkan kolom tax_percentage)
    $sql_invoice = "INSERT INTO invoices (
                        invoice_no, 
                        client_id, 
                        invoice_date, 
                        due_date, 
                        total_amount, 
                        tax_amount, 
                        tax_percentage, 
                        notes, 
                        status
                    ) VALUES (
                        '$invoice_no', 
                        '$client_id', 
                        '$invoice_date', 
                        '$due_date', 
                        '$total_amount', 
                        '$tax_amount', 
                        '$tax_percentage', 
                        '$notes', 
                        'unpaid'
                    )";
    
    if (!mysqli_query($conn, $sql_invoice)) {
        throw new Exception("Gagal menyimpan header invoice: " . mysqli_error($conn));
    }

    $invoice_id = mysqli_insert_id($conn);

    // B. Simpan Detail Item
    if (isset($_POST['desc']) && is_array($_POST['desc'])) {
        $descriptions = $_POST['desc'];
        $qtys         = $_POST['qty'];
        $prices       = $_POST['price'];

        foreach ($descriptions as $index => $desc) {
            $desc_clean  = mysqli_real_escape_string($conn, $desc);
            $qty_clean   = (int)$qtys[$index];
            $price_clean = (float)$prices[$index];
            $row_total   = $qty_clean * $price_clean;

            $sql_item = "INSERT INTO invoice_items (invoice_id, description, qty, price, row_total) 
                         VALUES ('$invoice_id', '$desc_clean', '$qty_clean', '$price_clean', '$row_total')";
            
            if (!mysqli_query($conn, $sql_item)) {
                throw new Exception("Gagal menyimpan item baris ke-" . ($index + 1));
            }
        }
    }

    // C. Log Activity
    $log_activity = "Created Invoice: $invoice_no (Total: Rp " . number_format($total_amount, 0, ',', '.') . ")";
    $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) VALUES ('$admin_id', '$log_activity', 'success', '$ip_address')";
    mysqli_query($conn, $log_sql);

    mysqli_commit($conn);
    header("Location: ../invoice_maker.php?status=success");

} catch (Exception $e) {
    mysqli_rollback($conn);
    header("Location: ../invoice_create.php?status=error&msg=" . urlencode($e->getMessage()));
}
exit();