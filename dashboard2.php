<?php
require_once 'config.php';

// Header Anti-Cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Ambil Status Pengaturan dari Database
$query_setting = "SELECT * FROM pengaturan_spmb WHERE id = 1 LIMIT 1";
$res_setting   = $conn->query($query_setting);
$setting       = $res_setting ? $res_setting->fetch_assoc() : ['status_registrasi' => 'TUTUP', 'status_observasi' => 'TUTUP'];

$is_registrasi_open = ($setting['status_registrasi'] === 'BUKA');
$is_observasi_open  = ($setting['status_observasi'] === 'BUKA');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Layanan SPMB - SDMM</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="shortcut icon" href="assets/OBS.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen flex flex-col justify-between font-sans">

  <!-- Header -->
  <header class="bg-blue-900 text-white shadow-lg py-4 px-6">
    <div class="max-w-6xl mx-auto flex justify-between items-center">
      <div class="flex items-center space-x-3">
        <div class="bg-yellow-400 text-blue-900 p-2 rounded-full font-bold">SDMM</div>
        <div>
          <h1 class="font-bold text-lg leading-tight">SD Muhammadiyah Manyar</h1>
          <p class="text-xs text-blue-200">Portal Layanan SPMB & Observasi Calon Murid Baru</p>
        </div>
        <a href="logout.php" class="text-sm bg-red-500 hover:bg-red-600 px-3 py-1.5 rounded text-white font-medium transition flex items-center shadow">
        <i class="fa-solid fa-sign-out-alt mr-1"></i> Keluar
      </a>
      </div>
    </div>
  </header>

  <!-- 3 Pilihan Utama Layanan -->
  <main class="max-w-6xl w-full mx-auto p-6 my-auto flex-grow flex items-center">
    <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-6">
      
      <!-- LAYANAN 1: INDEN / TITIP NAMA (SEALALU BUKA) -->
      <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 flex flex-col justify-between hover:shadow-xl transition">
        <div>
          <div class="w-14 h-14 bg-yellow-100 text-yellow-700 rounded-xl flex items-center justify-center text-2xl mb-4">
            <i class="fa-solid fa-user-plus"></i>
          </div>
          <span class="text-xs font-bold text-yellow-700 bg-yellow-100 px-3 py-1 rounded-full uppercase">Buka Sepanjang Tahun</span>
          <h3 class="text-xl font-bold text-slate-800 mt-3 mb-2">1. Inden / Titip Nama</h3>
          <p class="text-xs text-slate-500 leading-relaxed">
            Amankan kuota pendaftaran ananda lebih awal untuk TA 2029/2030 s.d. 2033/2034. Dapatkan <strong>potongan Rp 500.000</strong> biaya observasi.
          </p>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-100">
          <a href="inden_form.php" class="w-full bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-bold py-3 px-4 rounded-xl shadow transition flex items-center justify-center gap-2 text-sm">
            <i class="fa-solid fa-pen-to-square"></i> Form Titip Nama
          </a>
        </div>
      </div>

      <!-- LAYANAN 2: REGISTRASI SPMB (KONDISIONAL ADMIN) -->
      <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 flex flex-col justify-between hover:shadow-xl transition">
        <div>
          <div class="w-14 h-14 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center text-2xl mb-4">
            <i class="fa-solid fa-clipboard-list"></i>
          </div>
          <?php if ($is_registrasi_open): ?>
            <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full uppercase">Pendaftaran Dibuka</span>
          <?php else: ?>
            <span class="text-xs font-bold text-red-700 bg-red-100 px-3 py-1 rounded-full uppercase">Pendaftaran Belum Dibuka</span>
          <?php endif; ?>
          <h3 class="text-xl font-bold text-slate-800 mt-3 mb-2">2. Registrasi SPMB</h3>
          <p class="text-xs text-slate-500 leading-relaxed">
            Formulir pendaftaran resmi calon murid baru untuk mengikuti tahapan observasi sekolah. Masukkan Token Inden untuk klaim potongan.
          </p>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-100">
          <?php if ($is_registrasi_open): ?>
            <a href="register.php" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 px-4 rounded-xl shadow transition flex items-center justify-center gap-2 text-sm">
              <i class="fa-solid fa-right-to-bracket"></i> Registrasi SPMB
            </a>
          <?php else: ?>
            <button disabled class="w-full bg-slate-200 text-slate-400 font-bold py-3 px-4 rounded-xl cursor-not-allowed flex items-center justify-center gap-2 text-sm">
              <i class="fa-solid fa-lock"></i> Registrasi Belum Dibuka
            </button>
          <?php endif; ?>
        </div>
      </div>

      <!-- LAYANAN 3: HASIL OBSERVASI (KONDISIONAL ADMIN) -->
      <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 flex flex-col justify-between hover:shadow-xl transition">
        <div>
          <div class="w-14 h-14 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center text-2xl mb-4">
            <i class="fa-solid fa-square-poll-vertical"></i>
          </div>
          <?php if ($is_observasi_open): ?>
            <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full uppercase">Pengumuman Dibuka</span>
          <?php else: ?>
            <span class="text-xs font-bold text-red-700 bg-red-100 px-3 py-1 rounded-full uppercase">Pengumuman Belum Dibuka</span>
          <?php endif; ?>
          <h3 class="text-xl font-bold text-slate-800 mt-3 mb-2">3. Hasil Observasi</h3>
          <p class="text-xs text-slate-500 leading-relaxed">
            Lihat dan unduh surat pengumuman keputusan hasil observasi calon murid baru SDMM TA 2028/2029[cite: 1, 14, 16].
          </p>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-100">
          <?php if ($is_observasi_open): ?>
            <a href="index.php" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3 px-4 rounded-xl shadow transition flex items-center justify-center gap-2 text-sm">
              <i class="fa-solid fa-file-invoice"></i> Cek Hasil Observasi
            </a>
          <?php else: ?>
            <button disabled class="w-full bg-slate-200 text-slate-400 font-bold py-3 px-4 rounded-xl cursor-not-allowed flex items-center justify-center gap-2 text-sm">
              <i class="fa-solid fa-lock"></i> Pengumuman Belum Dibuka
            </button>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-slate-800 text-slate-400 text-xs py-5 text-center">
    <p>&copy; 2026 SD Muhammadiyah Manyar (SDMM) Gresik. All Rights Reserved.</p>
  </footer>

</body>
</html>