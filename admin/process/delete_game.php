<?php
require_once 'config.php';
session_start();

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $admin_id = $_SESSION['admin_id'] ?? 0;
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // 1. Ambil data game sebelum dihapus untuk mendapatkan list file gambar
    $query_select = "SELECT title, header_image, game_logo, screenshots FROM games WHERE id = '$id'";
    $result_select = mysqli_query($conn, $query_select);
    
    if ($row = mysqli_fetch_assoc($result_select)) {
        $game_title = $row['title'];
        $upload_path = "../../uploads/game/";

        // 2. Kumpulkan semua file yang harus dihapus
        $files_to_delete = [];
        
        // Tambahkan Header & Logo ke daftar hapus
        if (!empty($row['header_image'])) $files_to_delete[] = $row['header_image'];
        if (!empty($row['game_logo'])) $files_to_delete[] = $row['game_logo'];
        
        // Tambahkan semua Screenshots ke daftar hapus
        $screenshots = json_decode($row['screenshots'], true) ?: [];
        foreach ($screenshots as $ss) {
            if (!empty($ss)) $files_to_delete[] = $ss;
        }

        // 3. Eksekusi penghapusan file fisik
        foreach ($files_to_delete as $file) {
            $full_path = $upload_path . $file;
            if (file_exists($full_path)) {
                unlink($full_path);
            }
        }

        // 4. Hapus data dari database
        $query_delete = "DELETE FROM games WHERE id = '$id'";
        if (mysqli_query($conn, $query_delete)) {
            
            // 5. Catat ke Admin Logs
            $activity = "Deleted game: " . $game_title . " (All assets cleared)";
            $type = "danger"; // Menggunakan danger karena aksi penghapusan bersifat kritikal

            $log_query = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                          VALUES ('$admin_id', '$activity', '$type', '$ip_address', NOW())";
            mysqli_query($conn, $log_query);

            // Redirect kembali dengan status success
            header("Location: ../games.php?status=deleted");
            exit();
        } else {
            // Jika gagal menghapus di DB
            header("Location: ../games.php?status=error");
            exit();
        }
    } else {
        // Data game tidak ditemukan
        header("Location: ../games.php");
        exit();
    }
} else {
    // Tidak ada ID yang dikirim
    header("Location: ../games.php");
    exit();
}