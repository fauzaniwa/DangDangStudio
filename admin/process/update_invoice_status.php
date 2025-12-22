<?php
require_once 'config.php';
session_start();

// Proteksi akses
if (!isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../invoice_maker.php");
    exit();
}

$admin_id   = $_SESSION['admin_id'];
$invoice_id = mysqli_real_escape_string($conn, $_POST['invoice_id']);
$new_status = mysqli_real_escape_string($conn, $_POST['status']);
$ip_address = $_SERVER['REMOTE_ADDR'];

// Ambil data invoice lama untuk pengecekan
$get_inv = mysqli_query($conn, "SELECT invoice_no, payment_proof FROM invoices WHERE id = '$invoice_id'");
$inv_data = mysqli_fetch_assoc($get_inv);
$invoice_no = $inv_data['invoice_no'];

$proof_path = $inv_data['payment_proof']; // Simpan path lama sebagai default

mysqli_begin_transaction($conn);

try {
    // 1. Logika Khusus status 'PAID'
    if ($new_status === 'paid') {
        if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === 0) {
            // Path disesuaikan: ../uploads/proofs/ (asumsi folder process ada di root atau sejajar assets)
            $target_dir = "../../uploads/proofs/";
            
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_ext = strtolower(pathinfo($_FILES["payment_proof"]["name"], PATHINFO_EXTENSION));
            $file_name = "PROOF_" . str_replace('/', '-', $invoice_no) . "_" . time() . "." . $file_ext;
            $target_file = $target_dir . $file_name;

            $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];
            if (!in_array($file_ext, $allowed_types)) {
                throw new Exception("Format file tidak didukung. Gunakan JPG, PNG, atau PDF.");
            }

            if (move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $target_file)) {
                $proof_path = $file_name;
            } else {
                throw new Exception("Gagal mengupload file ke server.");
            }
        } elseif (empty($proof_path)) {
            // Jika status paid tapi tidak ada file baru dan tidak ada file lama
            throw new Exception("Bukti pembayaran wajib diunggah untuk status PAID.");
        }
    }

    // 2. Update Database (Satu Query untuk Status & Proof)
    $sql_update = "UPDATE invoices SET 
                   status = '$new_status', 
                   payment_proof = '$proof_path' 
                   WHERE id = '$invoice_id'";
                   
    if (!mysqli_query($conn, $sql_update)) {
        throw new Exception("Gagal memperbarui database.");
    }

    // 3. Catat ke Admin Logs
    $activity = "Updated Invoice $invoice_no to " . strtoupper($new_status);
    $log_sql = "INSERT INTO admin_logs (admin_id, activity, type, ip_address) 
                VALUES ('$admin_id', '$activity', 'info', '$ip_address')";
    
    mysqli_query($conn, $log_sql);

    mysqli_commit($conn);
    header("Location: ../invoice_maker.php?status=success");

} catch (Exception $e) {
    mysqli_rollback($conn);
    header("Location: ../invoice_maker.php?status=error&msg=" . urlencode($e->getMessage()));
}
exit();