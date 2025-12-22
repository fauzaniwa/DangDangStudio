<?php
require_once 'config.php';
session_start();

// 1. Keamanan: Cek apakah user sudah login
if (!isset($_SESSION['is_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

// 2. Keamanan: Hanya Super Admin yang diizinkan menghapus log
if ($_SESSION['admin_role'] !== 'Super Admin') {
    // Jika bukan Super Admin, lempar balik ke dashboard
    header("Location: ../dashboard.php");
    exit();
}

// 3. Proses Pembersihan Tabel
// Menggunakan TRUNCATE agar ID (auto_increment) kembali ke 1
$query = "TRUNCATE TABLE admin_logs";

if (mysqli_query($conn, $query)) {
    // 4. Opsional: Catat bahwa logs baru saja dibersihkan
    // Karena tabel baru dikosongkan, ini akan menjadi baris pertama (ID 1)
    $admin_id = $_SESSION['admin_id'];
    $activity = "All system logs have been cleared by " . $_SESSION['admin_fullname'];
    $type = "warning";
    $ip = $_SERVER['REMOTE_ADDR'];

    $stmt = $conn->prepare("INSERT INTO admin_logs (admin_id, activity, type, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $admin_id, $activity, $type, $ip);
    $stmt->execute();

    // 5. Redirect kembali ke halaman logs dengan status sukses
    header("Location: ../admin_logs.php?status=cleared");
} else {
    // Jika gagal
    header("Location: ../admin_logs.php?status=error");
}
exit();