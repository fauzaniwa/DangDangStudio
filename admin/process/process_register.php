<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil dan bersihkan data
    $fullname    = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email       = mysqli_real_escape_string($conn, $_POST['email']);
    $password    = $_POST['password']; // Jangan di-escape agar karakter khusus tidak berubah sebelum dihash
    $access_code = mysqli_real_escape_string($conn, $_POST['access_code']);
    
    // 1. Verifikasi Access Code ke tabel access_codes
    $query_code = "SELECT * FROM access_codes WHERE code = ? AND is_active = 1";
    $stmt_code = $conn->prepare($query_code);
    $stmt_code->bind_param("s", $access_code);
    $stmt_code->execute();
    $result_code = $stmt_code->get_result();

    if ($result_code->num_rows === 0) {
        // Catat sebagai ancaman keamanan di log
        createLog(null, "Unauthorized registration attempt using code: $access_code", "danger");
        header("Location: ../register.php?error=invalid_code");
        exit();
    }

    // 2. Cek duplikasi Email
    $query_email = "SELECT id FROM admins WHERE email = ?";
    $stmt_email = $conn->prepare($query_email);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    if ($stmt_email->get_result()->num_rows > 0) {
        header("Location: ../register.php?error=email_exists");
        exit();
    }

    // 3. Keamanan: Hash Password dengan BCRYPT
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // 4. Masukkan data ke tabel admins
    // Default profile picture diset ke default_avatar.png (pastikan file ada di folder upload Anda nantinya)
    $query_insert = "INSERT INTO admins (fullname, email, password, role, profile_picture) VALUES (?, ?, ?, 'Editor', 'default_avatar.png')";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param("sss", $fullname, $email, $hashed_password);

    if ($stmt_insert->execute()) {
        $new_admin_id = $conn->insert_id;
        
        // Catat log sukses
        createLog($new_admin_id, "New admin account created: $fullname", "success");
        
        // Redirect ke login dengan pesan sukses
        header("Location: ../login.php?registration=success");
        exit();
    } else {
        header("Location: ../register.php?error=system_error");
        exit();
    }
} else {
    // Jika diakses langsung tanpa POST
    header("Location: ../register.php");
    exit();
}