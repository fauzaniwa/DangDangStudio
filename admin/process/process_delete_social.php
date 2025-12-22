<?php
require_once './config.php';
session_start();

// 1. Validasi Akses & Parameter ID
if (isset($_SESSION['admin_id']) && isset($_GET['id'])) {
    $admin_id = $_SESSION['admin_id'];
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // 2. Ambil informasi data sebelum dihapus (untuk log dan hapus file)
    $check_query = "SELECT title, thumbnail FROM social_media WHERE id = '$id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $data = mysqli_fetch_assoc($check_result);
        $title = $data['title'];
        $thumbnail = $data['thumbnail'];

        // 3. Hapus file fisik thumbnail jika ada
        if (!empty($thumbnail)) {
            $file_path = "../../uploads/social/" . $thumbnail;
            if (file_exists($file_path)) {
                unlink($file_path); // Menghapus file dari folder uploads
            }
        }

        // 4. Query Hapus Data dari Database
        $delete_sql = "DELETE FROM social_media WHERE id = '$id'";

        if (mysqli_query($conn, $delete_sql)) {
            // 5. IMPLEMENTASI ADMIN LOGS
            $activity = mysqli_real_escape_string($conn, "Deleted social media plan: '$title'");
            $ip = $_SERVER['REMOTE_ADDR'];
            $type = 'danger'; // Tipe danger karena aksi penghapusan

            $sql_log = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                        VALUES ('$admin_id', '$activity', '$type', '$ip', NOW())";
            
            mysqli_query($conn, $sql_log);

            // Berhasil, arahkan kembali dengan status success
            header("Location: ../social_media_plan.php?status=success");
        } else {
            // Gagal menghapus di database
            header("Location: ../social_media_plan.php?status=error");
        }
    } else {
        // Data tidak ditemukan
        header("Location: ../social_media_plan.php?status=not_found");
    }
} else {
    // Akses ilegal
    header("Location: ../social_media_plan.php");
}
exit();