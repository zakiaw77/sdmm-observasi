<?php
require_once 'config.php';

$pesan_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_siswa = trim($_POST['nama_siswa']);
    $asal_tk    = trim($_POST['asal_tk']);
    $alamat     = trim($_POST['alamat']);
    $no_wa      = trim($_POST['no_wa']);
    $username   = trim($_POST['username']);
    $password   = $_POST['password'];
    $status     = 'MENUNGGU HASIL'; // Status awal default

    // Cek apakah username sudah ada yang memakai
    $cek_username = $conn->prepare("SELECT id FROM pengumuman WHERE username = ?");
    $cek_username->bind_param("s", $username);
    $cek_username->execute();
    $hasil_cek = $cek_username->get_result();

    if ($hasil_cek->num_rows > 0) {
        $pesan_error = 'Username sudah digunakan, silakan pilih username lain.';
    } else {
        // Simpan data pendaftar baru
        $query = "INSERT INTO pengumuman (nama_siswa, asal_tk, alamat, no_wa, username, password, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssss", $nama_siswa, $asal_tk, $alamat, $no_wa, $username, $password, $status);
        
        if ($stmt->execute()) {
            echo "<script>
                    alert('Registrasi Berhasil! Silakan login untuk mengecek pengumuman.');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        } else {
            $pesan_error = 'Terjadi kesalahan sistem, data gagal disimpan.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Wali Murid - SDMM</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 font-sans">

  <div class="max-w-xl w-full bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
    <div class="bg-gradient-to-r from-blue-800 to-blue-600 p-6 text-white text-center">
      <i class="fa-solid fa-user-plus text-4xl mb-2 text-yellow-300"></i>
      <h2 class="text-2xl font-bold">Registrasi Calon Siswa Baru</h2>
      <p class="text-xs text-blue-100 mt-1">Lengkapi data di bawah ini untuk membuat akun portal</p>
    </div>
    
    <form method="POST" class="p-6 md:p-8 space-y-4">
      
      <?php if ($pesan_error): ?>
      <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg text-sm mb-4 border border-red-300 flex items-center">
        <i class="fa-solid fa-triangle-exclamation mr-2"></i> <?= $pesan_error ?>
      </div>
      <?php endif; ?>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="col-span-1 md:col-span-2">
          <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap Anak</label>
          <input type="text" name="nama_siswa" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        
        <div class="col-span-1 md:col-span-2">
          <label class="block text-sm font-semibold text-slate-700 mb-1">Asal TK</label>
          <input type="text" name="asal_tk" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div class="col-span-1 md:col-span-2">
          <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Rumah</label>
          <textarea name="alamat" rows="2" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
        </div>

        <div class="col-span-1 md:col-span-2">
          <label class="block text-sm font-semibold text-slate-700 mb-1">Nomor WhatsApp (Wali Murid)</label>
          <input type="number" name="no_wa" required placeholder="Contoh: 081234567890" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div class="col-span-1">
          <label class="block text-sm font-semibold text-slate-700 mb-1">Buat Username</label>
          <input type="text" name="username" required placeholder="Tanpa spasi" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div class="col-span-1">
          <label class="block text-sm font-semibold text-slate-700 mb-1">Buat Password</label>
          <input type="password" name="password" required placeholder="Minimal 6 karakter" minlength="6" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
      </div>

      <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-bold py-3 rounded-lg shadow transition mt-6">
        Daftar Sekarang
      </button>
      
      <p class="text-center text-sm text-slate-500 mt-4">
        Sudah mendaftar? <a href="index.php" class="text-blue-700 font-bold hover:underline">Masuk di sini</a>
      </p>
    </form>
  </div>

</body>
</html>