<?php
require_once 'config.php';

/*
 * Cegah browser menggunakan halaman lama/cache.
 */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");


/*
 * CEK LOGIN
 */
if (
    !isset($_SESSION['login_siswa']) ||
    $_SESSION['login_siswa'] !== true ||
    !isset($_SESSION['username']) ||
    $_SESSION['username'] === ''
) {
    header("Location: index.php");
    exit();
}


/*
 * AMBIL USERNAME DARI SESSION
 *
 * Ini adalah IDENTITAS USER YANG SEDANG LOGIN.
 */
$username_login = trim($_SESSION['username']);


/*
 * AMBIL DATA SISWA BERDASARKAN USERNAME SESSION
 *
 * LIMIT 1 digunakan sebagai perlindungan tambahan.
 */
$query = "SELECT id, username, nama_siswa, asal_tk, status
          FROM pengumuman
          WHERE username = ?
          LIMIT 1";

$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Query database gagal diproses.");
}

$stmt->bind_param("s", $username_login);
$stmt->execute();

$result = $stmt->get_result();


/*
 * USERNAME HARUS MENEMUKAN TEPAT SATU DATA
 */
if ($result->num_rows !== 1) {

    /*
     * Kalau data user tidak ditemukan,
     * hapus session agar tidak ada data aneh.
     */
    session_unset();
    session_destroy();

    header("Location: index.php");
    exit();
}


/*
 * DATA USER YANG SEDANG LOGIN
 */
$data = $result->fetch_assoc();


/*
 * Pastikan username database benar-benar sama
 * dengan username pada session.
 */
if ($data['username'] !== $username_login) {

    session_unset();
    session_destroy();

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  
  <title>Dashboard Hasil Observasi - SDMM</title>
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
          <p class="text-xs text-blue-200">Portal Pengumuman Observasi Calon Murid Baru SPMB TA 2028/2029</p>
        </div>
      </div>
      <a href="logout.php" class="text-sm bg-red-500 hover:bg-red-600 px-3 py-1.5 rounded text-white font-medium transition flex items-center shadow">
        <i class="fa-solid fa-sign-out-alt mr-1"></i> Keluar
      </a>
    </div>
  </header>

  <!-- Main Content Dashboard -->
  <main class="max-w-4xl w-full mx-auto p-4 my-8 flex-grow">
    
    <div class="bg-white rounded-xl shadow-lg p-8 border border-slate-200 mb-6 relative overflow-hidden">
      
      <!-- Ringkasan Informasi Siswa -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-6 mb-6 gap-4">
        <div>
          <span class="text-xs text-slate-400 uppercase tracking-wider font-bold">Calon MUrid SPMB TA 2028/2029</span>
          <h2 class="text-3xl font-black text-slate-800 mt-1"><?= htmlspecialchars($data['nama_siswa']) ?></h2>
          <p class="text-md text-slate-600 mt-2"><i class="fa-solid fa-school mr-2"></i> Asal TK: <strong><?= htmlspecialchars($data['asal_tk']) ?></strong></p>
        </div>
        <div class="md:text-right">
          <span class="block text-xs text-slate-400 uppercase tracking-wider font-bold mb-2">Hasil Observasi</span>
          <?php 
            $status = strtoupper(trim($data['status']));
            if($status === 'DAPAT MELANJUTKAN'): 
          ?>
            <span class="bg-emerald-100 text-emerald-800 border border-emerald-300 px-5 py-2.5 rounded-full font-black text-sm flex items-center shadow-sm">
              <i class="fa-solid fa-circle-check text-lg mr-2 text-emerald-600"></i> DAPAT MELANJUTKAN TAHAP SPMB TA 2028/2029
            </span>
          <?php elseif($status === 'BELUM DAPAT MELANJUTKAN'): ?>
            <span class="bg-amber-100 text-amber-800 border border-amber-300 px-5 py-2.5 rounded-full font-black text-sm flex items-center shadow-sm">
              <i class="fa-solid fa-clock text-lg mr-2 text-amber-600"></i> BELUM DAPAT MELANJUTKAN TAHAP SPMB TA 2028/2029
            </span>
          <?php else: ?>
            <span class="bg-slate-100 text-slate-800 border border-slate-300 px-5 py-2.5 rounded-full font-black text-sm flex items-center shadow-sm">
              <i class="fa-solid fa-spinner text-lg mr-2 text-slate-600"></i> <?= htmlspecialchars($status) ?>
            </span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Box Unduh Surat -->
      <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-200 rounded-xl p-8 text-center shadow-sm">
        <i class="fa-solid fa-file-signature text-6xl text-blue-700 mb-4 drop-shadow-sm"></i>
        <h3 class="text-xl font-bold text-slate-800 mb-2">Surat Pengumuman Hasil Observasi</h3>
        <p class="text-slate-600 mb-8 max-w-lg mx-auto">Dokumen resmi pengumuman hasil observasi calon Murid SPMB TA 2028/2029 SDMM telah diterbitkan secara resmi.</p>
        
        <!-- Tambahkan parameter ?v=<?= time() ?> pada tautan surat_pengumuman.php -->
		<a href="surat_pengumuman.php?u=<?= urlencode($data['username']) ?>&v=<?= time() ?>" target="_blank" class="inline-flex items-center justify-center gap-3 bg-blue-700 hover:bg-blue-800 text-white px-8 py-3.5 rounded-lg font-bold shadow-md hover:shadow-lg transition duration-200">
    		<i class="fa-solid fa-print text-xl"></i> Lihat & Unduh Surat Resmi (PDF)
		</a>
      </div>

    </div>

  </main>

  <!-- Footer -->
  <footer class="bg-slate-800 text-slate-400 text-xs py-5 text-center">
    <p>&copy; Observasi V1.0 - 2026 Labkom SD Muhammadiyah Manyar (SDMM) Gresik. All Rights Reserved.</p>
  </footer>

</body>
</html>