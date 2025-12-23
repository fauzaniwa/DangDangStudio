<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $admin_id = $_SESSION['admin_id'];

    // 1. Cari nama logo untuk dihapus dari folder
    $stmt_get = $conn->prepare("SELECT partner_name, partner_logo FROM partners WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $data = $stmt_get->get_result()->fetch_assoc();

    if ($data) {
        $partner_name = $data['partner_name'];
        $logo_name = $data['partner_logo'];

        // 2. Hapus Data
        $stmt_del = $conn->prepare("DELETE FROM partners WHERE id = ?");
        $stmt_del->bind_param("i", $id);

        if ($stmt_del->execute()) {
            // Hapus file fisik jika ada
            if ($logo_name && file_exists("../../uploads/partners/" . $logo_name)) {
                unlink("../../uploads/partners/" . $logo_name);
            }

            // Catat Log
            createLog($admin_id, "Deleted partner: $partner_name", "danger");
            header("Location: ../partner_list?status=success&msg=Partner deleted");
        } else {
            header("Location: ../partner_list?status=error&msg=Delete failed");
        }
    }
}
exit();