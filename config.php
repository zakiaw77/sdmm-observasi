<?php
// Atur konfigurasi keamanan cookie sesi sebelum session_start()
header('X-LiteSpeed-Cache-Control: no-cache');

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1); // Melindungi dari Session Fixation

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Koneksi Database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sdmm_observasi";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>