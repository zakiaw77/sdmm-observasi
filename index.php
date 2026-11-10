<?php
require_once 'config.php';

// Jika wali murid sudah dalam keadaan login, langsung arahkan ke dashboard
if (isset($_SESSION['login_siswa']) && $_SESSION['login_siswa'] === true) {
    header("Location: dashboard2.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Calon Wali Murid - SDMM</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="shortcut icon" href="assets/OBS.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen flex flex-col justify-between font-sans">

  <!-- Header / Navbar -->
  <header class="bg-blue-900 text-white shadow-lg py-4 px-6">
    <div class="max-w-6xl mx-auto flex justify-between items-center">
      <div class="flex items-center space-x-3">
        <div class="bg-yellow-400 text-blue-900 p-2 rounded-full font-bold">SDMM</div>
        <div>
          <h1 class="font-bold text-lg leading-tight">SD Muhammadiyah Manyar</h1>
          <p class="text-xs text-blue-200">Portal Pengumuman Observasi Calon Murid Baru SPMB</p>
        </div>
      </div>
    </div>
  </header>

  <!-- Form Login Area -->
  <main class="max-w-4xl w-full mx-auto p-4 my-8 flex-grow flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-xl shadow-md overflow-hidden border border-slate-200">
      <div class="bg-gradient-to-r from-blue-800 to-blue-600 p-6 text-white text-center">
        <i class="fa-solid fa-user-lock text-4xl mb-2 text-yellow-300"></i>
        <h2 class="text-xl font-bold">Masuk Portal Calon Wali Murid</h2>
        <p class="text-xs text-blue-100 mt-1">Masukkan Username dan Password Pendaftaran Anda</p>
      </div>
      
      <form action="login_process.php" method="POST" class="p-6 space-y-5">
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1">Username</label>
          <input type="text" name="username" required placeholder="Masukkan Username" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-slate-800">
        </div>
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
          <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-slate-800">
        </div>
        <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-bold py-3 rounded-lg shadow transition">
          Cek Hasil Observasi
        </button>
      </form>
      
      <div class="px-6 pb-6 text-center text-sm text-slate-600 border-t border-slate-100 pt-4 bg-slate-50">
        Belum mendaftarkan ananda? <br>
        <a href="registrasi.php" class="text-blue-700 font-bold hover:underline inline-block mt-1">Daftar Akun Baru Sekarang</a>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-slate-800 text-slate-400 text-xs py-5 text-center">
    <p>&copy; 2026 SD Muhammadiyah Manyar (SDMM) Gresik. All Rights Reserved.</p>
  </footer>

</body>
</html>