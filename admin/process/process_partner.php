<?php
require_once 'config.php';
session_start();

// Proteksi akses langsung
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id               = $_POST['id']; // Jika kosong = Insert, Jika ada = Update
    $partner_name     = mysqli_real_escape_string($conn, $_POST['partner_name']);
    $cooperation_type = mysqli_real_escape_string($conn, $_POST['cooperation_type']);
    $partnership_date = $_POST['partnership_date'];
    $description      = mysqli_real_escape_string($conn, $_POST['description']);
    $admin_id         = $_SESSION['admin_id'];

    // 1. Logika Upload Logo
    $logo_sql = "";
    $new_logo_name = "";

    if (isset($_FILES['partner_logo']) && $_FILES['partner_logo']['error'] === 0) {
        $target_dir = "../../uploads/partners/";
        
        // Buat folder jika belum ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_ext = pathinfo($_FILES['partner_logo']['name'], PATHINFO_EXTENSION);
        $new_logo_name = "partner_" . time() . "_" . rand(100, 999) . "." . $file_ext;
        $target_file = $target_dir . $new_logo_name;

        // Validasi ekstensi
        $allowed_ext = ['jpg', 'jpeg', 'png', 'svg', 'webp'];
        if (in_array(strtolower($file_ext), $allowed_ext)) {
            if (move_uploaded_file($_FILES['partner_logo']['tmp_name'], $target_file)) {
                
                // Jika ini adalah UPDATE, hapus logo lama agar tidak memenuhi server
                if (!empty($id)) {
                    $old_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT partner_logo FROM partners WHERE id = '$id'"));
                    if ($old_data['partner_logo'] && file_exists($target_dir . $old_data['partner_logo'])) {
                        unlink($target_dir . $old_data['partner_logo']);
                    }
                }
                $logo_sql = ", partner_logo = '$new_logo_name'";
            }
        }
    }

    // 2. Eksekusi Database
    if (empty($id)) {
        // --- MODE INSERT ---
        $stmt = $conn->prepare("INSERT INTO partners (partner_name, partner_logo, cooperation_type, partnership_date, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $partner_name, $new_logo_name, $cooperation_type, $partnership_date, $description);
        $log_msg = "Added new partner: $partner_name";
    } else {
        // --- MODE UPDATE ---
        // Jika ada logo baru, masukkan ke query, jika tidak, biarkan logo lama
        $query = "UPDATE partners SET 
                    partner_name = ?, 
                    cooperation_type = ?, 
                    partnership_date = ?, 
                    description = ? 
                    $logo_sql 
                  WHERE id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $partner_name, $cooperation_type, $partnership_date, $description, $id);
        $log_msg = "Updated partner details: $partner_name";
    }

    if ($stmt->execute()) {
        // Panggil fungsi log yang sudah kita buat di config.php
        createLog($admin_id, $log_msg, "info");
        header("Location: ../partner_list?status=success");
    } else {
        header("Location: ../partner_list?status=error");
    }
}
exit();