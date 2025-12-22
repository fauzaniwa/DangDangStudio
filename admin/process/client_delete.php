<?php
require_once 'config.php';
session_start();

// Proteksi: Pastikan Admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (empty($id)) {
    header("Location: ../client_list.php");
    exit();
}

// 1. Ambil nama klien terlebih dahulu untuk keperluan Log
$check_client = mysqli_query($conn, "SELECT company_name FROM clients WHERE id = '$id'");
$client_data  = mysqli_fetch_assoc($check_client);

if (!$client_data) {
    header("Location: ../client_list.php?status=error&msg=Client not found");
    exit();
}

$company_name = $client_data['company_name'];

// 2. Cek apakah klien masih memiliki invoice
// Ini untuk mencegah data invoice menjadi "Yatim Piatu" (Orphan Data)
$check_invoice = mysqli_query($conn, "SELECT id FROM invoices WHERE client_id = '$id' LIMIT 1");

if (mysqli_num_rows($check_invoice) > 0) {
    // Jika ada invoice, batalkan hapus
    header("Location: ../client_list.php?status=error&msg=Cannot delete client. They still have active invoices/projects.");
    exit();
}

// 3. Proses Hapus
$sql = "DELETE FROM clients WHERE id = '$id'";

if (mysqli_query($conn, $sql)) {
    // 4. Catat ke Admin Logs
    $admin_id   = $_SESSION['admin_id'];
    $activity   = "Deleted client: $company_name";
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) 
                VALUES ('$admin_id', '$activity', 'danger', '$ip_address')";
    mysqli_query($conn, $log_sql);

    header("Location: ../client_list.php?status=success&msg=Client has been permanently deleted");
} else {
    header("Location: ../client_list.php?status=error&msg=Database error: " . urlencode(mysqli_error($conn)));
}
exit();