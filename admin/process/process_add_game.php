<?php
require_once 'config.php'; 

session_start();

// Ambil IP Address User
$ip_address = $_SERVER['REMOTE_ADDR'];

// Proteksi Akses
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// FUNGSI HELPER: Membuat Slug dari Judul
function createSlug($string) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    return $slug;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = $_SESSION['admin_id'];

    // 1. Tangkap Data Teks
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $trailer_url = mysqli_real_escape_string($conn, $_POST['trailer_url']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $short_desc = mysqli_real_escape_string($conn, $_POST['short_desc']);
    $long_desc = mysqli_real_escape_string($conn, $_POST['long_desc']);

    // --- IMPLEMENTASI SLUG ---
    $slug = createSlug($title);
    
    // Cek apakah slug sudah ada (untuk mencegah duplikasi URL)
    $check_slug = mysqli_query($conn, "SELECT id FROM games WHERE slug = '$slug'");
    if (mysqli_num_rows($check_slug) > 0) {
        // Jika ada yang sama, tambahkan suffix waktu unik
        $slug = $slug . '-' . time();
    }
    // -------------------------

    // 2. Olah Link Distribution (JSON)
    $links = [];
    if (isset($_POST['link_platform'])) {
        foreach ($_POST['link_platform'] as $index => $platform) {
            $url = $_POST['link_url'][$index];
            if (!empty($url)) {
                $links[] = ['platform' => $platform, 'url' => $url];
            }
        }
    }
    $links_json = json_encode($links);

    // 3. Konfigurasi Upload Gambar
    $upload_dir = "../../uploads/game/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    function uploadGameImage($file_array, $prefix, $upload_dir) {
        $ext = pathinfo($file_array['name'], PATHINFO_EXTENSION);
        $new_name = $prefix . "_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
        if (move_uploaded_file($file_array['tmp_name'], $upload_dir . $new_name)) {
            return $new_name;
        }
        return null;
    }

    $header_image = "";
    if (isset($_FILES['header_image']) && $_FILES['header_image']['error'] === 0) {
        $header_image = uploadGameImage($_FILES['header_image'], 'HEADER', $upload_dir);
    }

    $game_logo = "";
    if (isset($_FILES['game_logo']) && $_FILES['game_logo']['error'] === 0) {
        $game_logo = uploadGameImage($_FILES['game_logo'], 'LOGO', $upload_dir);
    }

    // Upload Multiple Screenshots
    $screenshot_names = [];
    if (isset($_FILES['screenshots'])) {
        foreach ($_FILES['screenshots']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['screenshots']['error'][$key] === 0) {
                $file_info = [
                    'name' => $_FILES['screenshots']['name'][$key],
                    'tmp_name' => $tmp_name
                ];
                $res = uploadGameImage($file_info, 'SS', $upload_dir);
                if ($res) $screenshot_names[] = $res;
            }
        }
    }
    $screenshots_json = json_encode($screenshot_names);

    // 4. Insert ke Database (Ditambahkan kolom 'slug')
    $sql = "INSERT INTO games (title, slug, trailer_url, category, short_desc, long_desc, header_image, game_logo, screenshots, distribution_links, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    // Bind parameter ditambahkan satu "s" untuk slug
    $stmt->bind_param("ssssssssss", $title, $slug, $trailer_url, $category, $short_desc, $long_desc, $header_image, $game_logo, $screenshots_json, $links_json);

    if ($stmt->execute()) {
        // 5. Catat ke admin_logs
        $log_activity = "Berhasil menambahkan game baru: $title";
        $log_type = "success";
        
        $log_stmt = $conn->prepare("INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
        $log_stmt->bind_param("isss", $admin_id, $log_activity, $log_type, $ip_address);
        $log_stmt->execute();

        header("Location: ../games.php?status=success");
    } else {
        header("Location: ../game_add.php?status=error");
    }
}
exit();