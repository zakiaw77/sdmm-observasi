<?php
// Memanggil koneksi dan mengaktifkan session
require_once 'config.php';

// Menghapus session khusus admin
unset($_SESSION['admin_logged_in']);

// Mengarahkan kembali ke halaman login admin
header("Location: login_admin.php");
exit();
?>