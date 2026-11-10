<?php
require_once 'config.php';

// Proteksi Akses Admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login_admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status_registrasi = $_POST['status_registrasi'];
    $status_observasi  = $_POST['status_observasi'];

    $query = "UPDATE pengaturan_spmb SET status_registrasi = ?, status_observasi = ? WHERE id = 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $status_registrasi, $status_observasi);
    
    if ($stmt->execute()) {
        echo "<script>alert('Pengaturan status fitur portal berhasil diperbarui!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui pengaturan.'); window.location.href='admin.php';</script>";
    }
    $stmt->close();
} else {
    header("Location: admin.php");
    exit();
}
?>