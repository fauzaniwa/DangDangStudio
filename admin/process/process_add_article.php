<?php
require_once './config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Validasi Auth
    if (!isset($_SESSION['admin_id'])) {
        http_response_code(403);
        exit("Unauthorized");
    }

    $admin_id = $_SESSION['admin_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    // 2. Sanitasi Input User
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    
    // Membuat Slug URL (Contoh: "Hello World" -> "hello-world")
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    $cover_name = "";
    $target_dir = "../../uploads/articles/";

    // 3. Proses Upload Main Cover
    if (isset($_FILES['article_cover']) && $_FILES['article_cover']['error'] === 0) {
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $ext = pathinfo($_FILES['article_cover']['name'], PATHINFO_EXTENSION);
        $cover_name = "cover_" . time() . "_" . uniqid() . "." . $ext;
        move_uploaded_file($_FILES['article_cover']['tmp_name'], $target_dir . $cover_name);
    }

    // 4. Query Insert Artikel Utama
    $sql_article = "INSERT INTO articles (title, slug, category, content, cover_image, status, admin_id, created_at) 
                    VALUES ('$title', '$slug', '$category', '$content', '$cover_name', '$status', '$admin_id', NOW())";

    if (mysqli_query($conn, $sql_article)) {
        $article_id = mysqli_insert_id($conn); // Ambil ID untuk relasi gallery
        $uploaded_gallery_count = 0;

        // 5. Proses Upload Gallery Assets (Multiple Images)
        if (!empty($_FILES['article_images']['name'][0])) {
            $gallery_dir = "../../uploads/articles/gallery/";
            if (!is_dir($gallery_dir)) {
                mkdir($gallery_dir, 0777, true);
            }

            foreach ($_FILES['article_images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['article_images']['error'][$key] === 0) {
                    $file_ext = pathinfo($_FILES['article_images']['name'][$key], PATHINFO_EXTENSION);
                    $new_gallery_name = "asset_" . $article_id . "_" . time() . "_" . $key . "." . $file_ext;

                    if (move_uploaded_file($tmp_name, $gallery_dir . $new_gallery_name)) {
                        $sql_gallery = "INSERT INTO article_gallery (article_id, image_url) VALUES ('$article_id', '$new_gallery_name')";
                        mysqli_query($conn, $sql_gallery);
                        $uploaded_gallery_count++;
                    }
                }
            }
        }

        // 6. MEKANISME ADMIN LOGS (SUCCESS)
        $raw_activity = "Created new article: '$title' ($category) with $uploaded_gallery_count gallery assets.";
        $activity = mysqli_real_escape_string($conn, $raw_activity); // PENTING: Mencegah SQL error tanda kutip

        $log_query = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                      VALUES ('$admin_id', '$activity', 'success', '$ip_address', NOW())";
        mysqli_query($conn, $log_query);

        header("Location: ../articles.php?status=success");
        
    } else {
        // 7. MEKANISME ADMIN LOGS (DANGER/ERROR)
        $raw_error = "Failed to create article: " . mysqli_error($conn);
        $activity_error = mysqli_real_escape_string($conn, $raw_error);
        
        $log_error = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                      VALUES ('$admin_id', '$activity_error', 'danger', '$ip_address', NOW())";
        mysqli_query($conn, $log_error);

        header("Location: ../articles.php?status=error");
    }
} else {
    header("Location: ../articles.php");
}
exit();