<?php
require_once 'config.php';
session_start();

// Proteksi: Hanya Super Admin dan tidak boleh menghapus diri sendiri
if (isset($_GET['id']) && $_SESSION['admin_role'] === 'Super Admin') {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $current_admin_id = $_SESSION['admin_id'];

    if ($id == $current_admin_id) {
        header("Location: ../admin_manager.php?status=self_delete_error");
        exit();
    }

    // Ambil nama admin sebelum dihapus untuk keperluan Log
    $query_target = mysqli_query($conn, "SELECT fullname FROM admins WHERE id='$id'");
    if (mysqli_num_rows($query_target) > 0) {
        $target = mysqli_fetch_assoc($query_target);
        $target_name = $target['fullname'];

        // Proses Hapus
        $delete_query = "DELETE FROM admins WHERE id='$id'";
        
        if (mysqli_query($conn, $delete_query)) {
            // CATAT KE LOGS
            createLog($current_admin_id, "Deleted admin account: " . $target_name, "danger");
            header("Location: ../admin_manager.php?status=deleted");
        } else {
            header("Location: ../admin_manager.php?status=error");
        }
    } else {
        header("Location: ../admin_manager.php?status=not_found");
    }
} else {
    header("Location: ../dashboard.php");
}
exit();