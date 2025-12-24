<?php
require_once 'config.php';
session_start();

// Proteksi akses
if (!isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../invoice_maker.php");
    exit();
}

$admin_id   = $_SESSION['admin_id'];
$ip_address = $_SERVER['REMOTE_ADDR'];

// 1. Tangkap Data dari Form
$invoice_id     = mysqli_real_escape_string($conn, $_POST['id']);
$client_id      = mysqli_real_escape_string($conn, $_POST['client_id']);
$due_date       = mysqli_real_escape_string($conn, $_POST['due_date']);
$notes          = mysqli_real_escape_string($conn, $_POST['notes']);
$tax_amount     = mysqli_real_escape_string($conn, $_POST['tax_amount']);

// PERBAHAN: Tangkap tax_percentage
$tax_percentage = isset($_POST['tax_percentage']) ? mysqli_real_escape_string($conn, $_POST['tax_percentage']) : 0;

$total_amount   = mysqli_real_escape_string($conn, $_POST['total_amount']);

// 2. Mulai Transaction
mysqli_begin_transaction($conn);

try {
    // A. Update Header Invoice
    $sql_update = "UPDATE invoices SET 
                    client_id = '$client_id', 
                    due_date = '$due_date', 
                    tax_amount = '$tax_amount', 
                    tax_percentage = '$tax_percentage', 
                    total_amount = '$total_amount', 
                    notes = '$notes' 
                   WHERE id = '$invoice_id'";
    
    if (!mysqli_query($conn, $sql_update)) {
        throw new Exception("Gagal memperbarui header invoice: " . mysqli_error($conn));
    }

    // B. Refresh Items (Hapus yang lama, masukkan yang baru)
    // Ini adalah cara paling aman untuk menangani penambahan/pengurangan baris saat edit
    mysqli_query($conn, "DELETE FROM invoice_items WHERE invoice_id = '$invoice_id'");

    if (isset($_POST['desc']) && is_array($_POST['desc'])) {
        foreach ($_POST['desc'] as $index => $desc) {
            $desc_clean  = mysqli_real_escape_string($conn, $desc);
            $qty_clean   = (int)$_POST['qty'][$index];
            $price_clean = (float)$_POST['price'][$index];
            $row_total   = $qty_clean * $price_clean;

            $sql_item = "INSERT INTO invoice_items (invoice_id, description, qty, price, row_total) 
                         VALUES ('$invoice_id', '$desc_clean', '$qty_clean', '$price_clean', '$row_total')";
            
            if (!mysqli_query($conn, $sql_item)) {
                throw new Exception("Gagal menyimpan item pada baris ke-" . ($index + 1));
            }
        }
    }

    // C. Log Activity
    $log_activity = "Updated Invoice ID: $invoice_id (Total: Rp " . number_format($total_amount, 0, ',', '.') . ")";
    $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) VALUES ('$admin_id', '$log_activity', 'info', '$ip_address')";
    mysqli_query($conn, $log_sql);

    mysqli_commit($conn);
    header("Location: ../invoice_maker.php?status=success_edit");

} catch (Exception $e) {
    // Batalkan semua perubahan jika ada error
    mysqli_rollback($conn);
    header("Location: ../invoice_edit.php?id=$invoice_id&status=error&msg=" . urlencode($e->getMessage()));
}
exit();