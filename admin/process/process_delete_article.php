<?php
require_once './config.php';
session_start();

if (isset($_GET['id']) && isset($_SESSION['admin_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $admin_id = $_SESSION['admin_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // 1. Ambil nama file gambar sebelum data dihapus (untuk dihapus dari folder)
    $check_sql = "SELECT title, cover_image FROM articles WHERE id = '$id'";
    $res = mysqli_query($conn, $check_sql);
    $data = mysqli_fetch_assoc($res);

    if ($data) {
        $title = $data['title'];
        $cover_file = "../../uploads/articles/" . $data['cover_image'];

        // 2. Ambil semua gambar gallery terkait
        $gal_sql = "SELECT image_url FROM article_gallery WHERE article_id = '$id'";
        $gal_res = mysqli_query($conn, $gal_sql);
        
        // 3. Hapus data artikel (Table gallery akan terhapus otomatis karena ON DELETE CASCADE di SQL)
        $delete_sql = "DELETE FROM articles WHERE id = '$id'";
        
        if (mysqli_query($conn, $delete_sql)) {
            // Hapus file fisik Cover
            if (!empty($data['cover_image']) && file_exists($cover_file)) {
                unlink($cover_file);
            }

            // Hapus file fisik Gallery Assets
            while ($gal = mysqli_fetch_assoc($gal_res)) {
                $gal_file = "../../uploads/articles/gallery/" . $gal['image_url'];
                if (file_exists($gal_file)) {
                    unlink($gal_file);
                }
            }

            // Log Aktivitas
            $activity = mysqli_real_escape_string($conn, "Deleted article: '$title'");
            mysqli_query($conn, "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                                VALUES ('$admin_id', '$activity', 'danger', '$ip_address', NOW())");

            header("Location: ../articles.php?status=deleted");
        } else {
            header("Location: ../articles.php?status=error");
        }
    } else {
        header("Location: ../articles.php?status=error");
    }
} else {
    header("Location: ../articles.php");
}
exit();