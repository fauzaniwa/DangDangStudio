<?php
// 1. Mulai session untuk mengakses data yang ada
session_start();

// 2. Hubungkan ke config untuk mencatat log terakhir jika diperlukan
require_once './process/config.php';

// 3. Catat aktivitas logout ke dalam database (Opsional tapi sangat disarankan)
if (isset($_SESSION['admin_id'])) {
    createLog($_SESSION['admin_id'], "Admin logged out safely", "info");
}

// 4. Hapus semua variabel session
$_SESSION = array();

// 5. Jika ingin menghapus session hingga ke cookie browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 6. Hancurkan session secara total
session_destroy();

// 7. Alihkan kembali ke halaman login dengan pesan sukses
header("Location: login.php?logout=success");
exit();