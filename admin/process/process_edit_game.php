<?php
require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 0. Ambil Data Identitas
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $admin_id = $_SESSION['admin_id'] ?? 0;
    $ip_address = $_SERVER['REMOTE_ADDR']; // Mengambil IP Address Admin

    // 1. Ambil data lama (untuk hapus file fisik & info log)
    $old_query = "SELECT title, header_image, game_logo, screenshots FROM games WHERE id = '$id'";
    $old_res = mysqli_query($conn, $old_query);
    $old_data = mysqli_fetch_assoc($old_res);
    $old_screenshots = json_decode($old_data['screenshots'], true) ?: [];

    // Path folder sesuai instruksi baru kamu
    $upload_path = "../../uploads/game/";

    // 2. Tangani Update Header Image
    $header_name = $old_data['header_image'];
    if (!empty($_FILES['header_image']['name'])) {
        if (!empty($header_name) && file_exists($upload_path . $header_name)) unlink($upload_path . $header_name);
        $header_name = time() . '_header_' . $_FILES['header_image']['name'];
        move_uploaded_file($_FILES['header_image']['tmp_name'], $upload_path . $header_name);
    }

    // 3. Tangani Update Game Logo
    $logo_name = $old_data['game_logo'];
    if (!empty($_FILES['game_logo']['name'])) {
        if (!empty($logo_name) && file_exists($upload_path . $logo_name)) unlink($upload_path . $logo_name);
        $logo_name = time() . '_logo_' . $_FILES['game_logo']['name'];
        move_uploaded_file($_FILES['game_logo']['tmp_name'], $upload_path . $logo_name);
    }

    // 4. Logika Hybrid Screenshot (Urutan & Upload Baru)
    $final_screenshots = [];
    $screenshot_order = json_decode($_POST['screenshot_order'], true);
    $new_upload_index = 0;

    if ($screenshot_order) {
        foreach ($screenshot_order as $item) {
            if ($item['type'] === 'existing') {
                $final_screenshots[] = $item['value'];
            } else if ($item['type'] === 'new') {
                $file_tmp = $_FILES['screenshots']['tmp_name'][$new_upload_index];
                $new_name = time() . '_ss_' . $_FILES['screenshots']['name'][$new_upload_index];
                if (move_uploaded_file($file_tmp, $upload_path . $new_name)) {
                    $final_screenshots[] = $new_name;
                }
                $new_upload_index++;
            }
        }
    }

    // Bersihkan file fisik yang dihapus user dari list
    foreach ($old_screenshots as $old_ss) {
        if (!in_array($old_ss, $final_screenshots)) {
            if (file_exists($upload_path . $old_ss)) unlink($upload_path . $old_ss);
        }
    }

    // 5. Tangani Links & Teks
    $links = [];
    if (isset($_POST['link_platform'])) {
        foreach ($_POST['link_platform'] as $idx => $plat) {
            if (!empty($_POST['link_url'][$idx])) {
                $links[] = [
                    'platform' => mysqli_real_escape_string($conn, $plat), 
                    'url' => mysqli_real_escape_string($conn, $_POST['link_url'][$idx])
                ];
            }
        }
    }

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $trailer_url = mysqli_real_escape_string($conn, $_POST['trailer_url']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $short_desc = mysqli_real_escape_string($conn, $_POST['short_desc']);
    $long_desc = mysqli_real_escape_string($conn, $_POST['long_desc']);
    $screenshots_json = mysqli_real_escape_string($conn, json_encode($final_screenshots));
    $links_json = mysqli_real_escape_string($conn, json_encode($links));

    // 6. Jalankan Update Database
    $update_query = "UPDATE games SET 
        title = '$title', trailer_url = '$trailer_url', category = '$category', 
        short_desc = '$short_desc', long_desc = '$long_desc', 
        header_image = '$header_name', game_logo = '$logo_name', 
        screenshots = '$screenshots_json', distribution_links = '$links_json', 
        updated_at = NOW() WHERE id = '$id'";

    if (mysqli_query($conn, $update_query)) {
        
        // 7. MEKANIS ADMIN LOGS (Disesuaikan dengan struktur tabel kamu)
        $activity = "Updated game data: " . $old_data['title'];
        $type = "success"; // Sesuai ENUM kamu

        $log_query = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                      VALUES ('$admin_id', '$activity', '$type', '$ip_address', NOW())";
        mysqli_query($conn, $log_query);

        echo "success";
    } else {
        // Log jika terjadi kegagalan (Optional tapi bagus untuk keamanan)
        $error_msg = "Failed to update game: " . mysqli_error($conn);
        $log_error = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                      VALUES ('$admin_id', '$error_msg', 'danger', '$ip_address', NOW())";
        mysqli_query($conn, $log_error);

        http_response_code(500);
        echo "Error: " . mysqli_error($conn);
    }
}