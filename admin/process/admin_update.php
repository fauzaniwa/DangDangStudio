<?php
require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['admin_role'] === 'Super Admin') {
    $id = mysqli_real_escape_string($conn, $_POST['admin_id']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']); // Tangkap Role Baru
    $password = $_POST['password'];

    // Ambil data lama untuk log yang detail
    $check_old = mysqli_query($conn, "SELECT fullname, role FROM admins WHERE id='$id'");
    $old_data = mysqli_fetch_assoc($check_old);
    $target_name = $old_data['fullname'];
    $old_role = $old_data['role'];

    // Pesan log dasar
    $log_msg = "Updated admin: $target_name. ";
    if ($old_role !== $role) {
        $log_msg .= "Role changed from $old_role to $role. ";
    }

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = "UPDATE admins SET fullname='$fullname', email='$email', role='$role', password='$hashed_password', updated_at=NOW() WHERE id='$id'";
        $log_msg .= "Password was reset.";
    } else {
        $query = "UPDATE admins SET fullname='$fullname', email='$email', role='$role', updated_at=NOW() WHERE id='$id'";
    }

    if (mysqli_query($conn, $query)) {
        createLog($_SESSION['admin_id'], $log_msg, "info");
        header("Location: ../admin_manager.php?status=updated");
    } else {
        header("Location: ../admin_manager.php?status=error");
    }
}
exit();