<?php
// --- OTOMATIS CLEAN URL HELPER ---
ob_start(function($buffer) {
    /**
     * Regex ini mencari href="nama_file.php"
     * (?![^"]*process\/) -> JANGAN hapus jika link mengandung kata 'process/'
     * (?![^"]*\/)        -> JANGAN hapus jika link mengandung folder lain (ada tanda /)
     */
    return preg_replace('/href="(?![^"]*process\/|[^"]*\/)([^"]+)\.php(\??[^"]*)"/', 'href="$1$2"', $buffer);
});
// ... (sisanya adalah kode koneksi database dan fungsi createLog Anda)
// ... kode koneksi database Anda ...
// Database Configuration
$host     = "localhost";
$user     = "root"; 
$password = "";     
$database = "dangdang";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fungsi Global untuk Log Aktivitas
function createLog($admin_id, $activity, $type = 'info') {
    global $conn;
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt = $conn->prepare("INSERT INTO admin_logs (admin_id, activity, type, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $admin_id, $activity, $type, $ip);
    $stmt->execute();
}
?>