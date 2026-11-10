<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT id, username, nama_siswa FROM pengumuman WHERE username = ? AND password = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // 1. Buat ID Sesi Baru DULUAN (Mencegah Race Condition)
        session_regenerate_id(true);
        
        // 2. Simpan identitas akun secara pasti
        $_SESSION['login_siswa'] = true;
        $_SESSION['username']    = $row['username'];
        $_SESSION['nama_siswa']  = $row['nama_siswa'];

        // 3. Alihkan ke dashboard dengan parameter unik waktu & hash
        $nocache = time();
        header("Location: dashboard.php?v=" . $nocache);
        exit();
    } else {
        echo "<script>alert('Username atau Password salah!'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>