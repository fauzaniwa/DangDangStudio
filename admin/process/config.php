<?php
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