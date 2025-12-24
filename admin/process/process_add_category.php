<?php
require_once 'config.php';
session_start();

header('Content-Type: application/json');

// Pastikan admin sudah login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['admin_id'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $admin_id = $_SESSION['admin_id'];

    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Nama kategori tidak boleh kosong!']);
        exit;
    }

    // Cek duplikasi agar tidak ada kategori ganda
    $check = mysqli_query($conn, "SELECT id FROM transaction_categories WHERE category_name = '$name' AND type = '$type'");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['success' => false, 'message' => 'Kategori ini sudah ada dalam sistem!']);
        exit;
    }

    $sql = "INSERT INTO transaction_categories (category_name, type) VALUES ('$name', '$type')";
    
    if (mysqli_query($conn, $sql)) {
        // Mencatat log aktivitas admin
        $log_type = ($type === 'in') ? "Pemasukan" : "Pengeluaran";
        createLog($admin_id, "Menambahkan kategori $log_type baru: $name", "info");
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan ke database']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak atau sesi berakhir']);
}