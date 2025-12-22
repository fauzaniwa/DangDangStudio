<?php
require_once './config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['admin_id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $delete_assets = $_POST['delete_assets']; // Berisi string ID (contoh: "10,12")

    // 1. PENANGANAN COVER IMAGE
    $cover_update_sql = "";
    if (!empty($_FILES['article_cover']['name'])) {
        // Ambil nama cover lama untuk dihapus
        $old_cover_res = mysqli_query($conn, "SELECT cover_image FROM articles WHERE id = '$id'");
        $old_cover_data = mysqli_fetch_assoc($old_cover_res);
        
        $target_dir = "../../uploads/articles/";
        $file_ext = pathinfo($_FILES["article_cover"]["name"], PATHINFO_EXTENSION);
        $new_cover_name = "cover_" . time() . "_" . uniqid() . "." . $file_ext;
        $target_file = $target_dir . $new_cover_name;

        if (move_uploaded_file($_FILES["article_cover"]["tmp_name"], $target_file)) {
            // Hapus file fisik lama jika ada
            if (!empty($old_cover_data['cover_image']) && file_exists($target_dir . $old_cover_data['cover_image'])) {
                unlink($target_dir . $old_cover_data['cover_image']);
            }
            $cover_update_sql = ", cover_image = '$new_cover_name'";
        }
    }

    // 2. UPDATE DATA UTAMA
    $sql_update = "UPDATE articles SET 
                    title = '$title', 
                    category = '$category', 
                    status = '$status', 
                    content = '$content',
                    updated_at = NOW() 
                    $cover_update_sql 
                  WHERE id = '$id'";

    if (mysqli_query($conn, $sql_update)) {
        
        // 3. HAPUS ASET GALERI YANG DITANDAI
        if (!empty($delete_assets)) {
            $ids_to_delete = explode(',', $delete_assets);
            foreach ($ids_to_delete as $asset_id) {
                $asset_id = mysqli_real_escape_string($conn, $asset_id);
                
                // Cari nama file sebelum dihapus dari DB
                $get_asset = mysqli_query($conn, "SELECT image_url FROM article_gallery WHERE id = '$asset_id'");
                if ($asset_data = mysqli_fetch_assoc($get_asset)) {
                    $file_path = "../../uploads/articles/gallery/" . $asset_data['image_url'];
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                    // Hapus dari database
                    mysqli_query($conn, "DELETE FROM article_gallery WHERE id = '$asset_id'");
                }
            }
        }

        // 4. TAMBAH ASET GALERI BARU (Jika ada)
        if (!empty($_FILES['article_images']['name'][0])) {
            $gallery_dir = "../../uploads/articles/gallery/";
            foreach ($_FILES['article_images']['tmp_name'] as $key => $tmp_name) {
                $gal_ext = pathinfo($_FILES["article_images"]["name"][$key], PATHINFO_EXTENSION);
                $gal_name = "gal_" . time() . "_" . uniqid() . "." . $gal_ext;
                
                if (move_uploaded_file($tmp_name, $gallery_dir . $gal_name)) {
                    mysqli_query($conn, "INSERT INTO article_gallery (article_id, image_url) VALUES ('$id', '$gal_name')");
                }
            }
        }

        // Log Aktivitas
        $admin_id = $_SESSION['admin_id'];
        $activity = mysqli_real_escape_string($conn, "Updated article: '$title'");
        $ip = $_SERVER['REMOTE_ADDR'];
        mysqli_query($conn, "INSERT INTO admin_logs (admin_id, activity, type, ip_address) VALUES ('$admin_id', '$activity', 'info', '$ip')");

        header("Location: ../articles.php?status=updated");
    } else {
        header("Location: ../articles.php?status=error");
    }
} else {
    header("Location: ../articles.php");
}
exit();