<?php
require_once 'config.php';

// 1. Matikan cache secara mutlak agar halaman setelah logout tidak bisa di-back
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// 2. Kosongkan semua array variabel session
$_SESSION = array();

// 3. Hapus cookie session dari browser jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 4. Hancurkan session di server secara total
session_unset();
session_destroy();

// 5. Alihkan pengguna kembali ke halaman utama (login)
header("Location: index.php");
exit();
?>