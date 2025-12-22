<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // 1. Cari user berdasarkan email
    $query = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        
        // 2. Verifikasi Password dengan BCRYPT
        if (password_verify($password, $admin['password'])) {
            
            // 3. Simpan data ke dalam Session
            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_fullname'] = $admin['fullname'];
            $_SESSION['admin_email']    = $admin['email'];
            $_SESSION['admin_role']     = $admin['role'];
            $_SESSION['admin_picture']  = $admin['profile_picture'];
            $_SESSION['is_logged_in']   = true;

            // 4. Catat Log Login
            createLog($admin['id'], "Admin successfully logged in", "info");

            // 5. Lempar ke Dashboard
            header("Location: ../dashboard.php");
            exit();
        }
    }

    // Gagal Login: Catat log upaya gagal jika email ditemukan tapi password salah
    createLog(null, "Failed login attempt for email: $email", "warning");
    header("Location: ../login.php?error=wrong_credentials");
    exit();
}