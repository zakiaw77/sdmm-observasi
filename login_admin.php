<?php
require_once 'config.php';

// Jika admin sudah login, langsung arahkan ke admin.php
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password_input = $_POST['password'];
    
    // Set password khusus untuk Tim Observer SDMM di sini
    $password_rahasia = 'observerSDMM2026'; 

    if ($password_input === $password_rahasia) {
        // Buat session khusus admin
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = 'Password salah! Akses ditolak.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin Observer - SDMM</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="shortcut icon" href="assets/OBS.ico" type="image/x-icon">
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">

  <div class="max-w-md w-full bg-white rounded-xl shadow-2xl p-8 border border-slate-700">
    <div class="text-center mb-6">
      <div class="inline-block p-3 bg-blue-100 text-blue-900 rounded-full mb-3">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
      </div>
      <h2 class="text-2xl font-bold text-slate-800">Admin Observer SDMM</h2>
      <p class="text-xs text-slate-500 mt-1">Masukkan kata sandi khusus panitia</p>
    </div>

    <?php if ($error): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-xs mb-4 text-center">
        <?= $error ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1">Password Admin</label>
        <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-slate-800">
      </div>
      <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-2.5 rounded-lg shadow transition text-sm">
        Masuk Dashboard
      </button>
    </form>
  </div>

</body>
</html>