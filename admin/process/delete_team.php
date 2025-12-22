<?php
require_once './config.php';
session_start();

if (isset($_GET['id'])) {
    // Pastikan admin sudah login
    if (!isset($_SESSION['admin_id'])) {
        http_response_code(403);
        echo "Unauthorized";
        exit();
    }

    $admin_id = $_SESSION['admin_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // 1. Ambil data lengkap sebelum dihapus untuk keperluan Log & Hapus File
    $query = "SELECT name, division, member_image FROM team WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $staff_name = $data['name'];
        $staff_div = $data['division'];
        $imageName = $data['member_image'];
        $filePath = "../../uploads/team/" . $imageName;

        // 2. Hapus file gambar secara fisik
        if (!empty($imageName) && file_exists($filePath)) {
            unlink($filePath);
        }

        // 3. Hapus data dari database
        $sqlDelete = "DELETE FROM team WHERE id = '$id'";
        
        if (mysqli_query($conn, $sqlDelete)) {
            
            // 4. MEKANISME ADMIN LOGS (Success)
            $activity = "Deleted team member: $staff_name (Division: $staff_div)";
            $type = "success";

            $log_query = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                          VALUES ('$admin_id', '$activity', '$type', '$ip_address', NOW())";
            mysqli_query($conn, $log_query);

            header("Location: ../team.php?status=deleted");
        } else {
            
            // 5. MEKANISME ADMIN LOGS (Danger/Error)
            $error_msg = "Failed to delete staff $staff_name: " . mysqli_error($conn);
            $log_error = "INSERT INTO admin_logs (admin_id, activity, type, ip_address, created_at) 
                          VALUES ('$admin_id', '$error_msg', 'danger', '$ip_address', NOW())";
            mysqli_query($conn, $log_error);

            header("Location: ../team.php?status=error");
        }
    } else {
        header("Location: ../team.php");
    }
} else {
    header("Location: ../team.php");
}
exit();