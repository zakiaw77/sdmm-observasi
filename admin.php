<?php
require_once 'config.php';

// Cek status login admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login_admin.php");
    exit();
}

// 1. Ambil status pengaturan fitur portal
$query_setting = "SELECT * FROM pengaturan_spmb WHERE id = 1 LIMIT 1";
$res_setting   = $conn->query($query_setting);
$setting       = ($res_setting && $res_setting->num_rows > 0) ? $res_setting->fetch_assoc() : ['status_registrasi' => 'TUTUP', 'status_observasi' => 'TUTUP'];

// 2. Query Data Observasi / Pengumuman
$query_table  = "SELECT * FROM pengumuman ORDER BY status DESC, nama_siswa ASC";
$result_table = $conn->query($query_table);

// 3. Query Data Inden (Titip Nama)
$query_inden  = "SELECT * FROM tb_inden ORDER BY created_at DESC";
$result_inden = $conn->query($query_inden);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin Observer - SDMM</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="shortcut icon" href="assets/OBS.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans">

  <!-- Navbar Admin -->
  <nav class="bg-slate-900 text-white p-4 font-bold flex justify-between items-center shadow-md">
    <div class="flex items-center gap-3">
        <div class="bg-yellow-400 text-blue-900 p-1.5 rounded font-bold text-sm">SDMM</div>
        <span>Admin Portal Observasi & Inden</span>
    </div>
    <div class="flex gap-4 items-center">
        <span class="text-xs font-normal bg-slate-700 px-2.5 py-1 rounded">Tim Observer</span>
        <a href="logout_admin.php" class="text-xs bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded transition shadow flex items-center">
          <i class="fa-solid fa-sign-out-alt mr-1"></i> Keluar
        </a>
    </div>
  </nav>

  <div class="max-w-7xl mx-auto p-6 space-y-8">

    <!-- PANEL KONTROL BUKA/TUTUP FITUR PORTAL -->
    <div class="bg-gradient-to-r from-blue-900 to-slate-800 text-white p-6 rounded-xl shadow-lg border border-slate-700">
      <div class="flex items-center gap-3 mb-4 border-b border-blue-700/50 pb-3">
        <i class="fa-solid fa-sliders text-yellow-400 text-xl"></i>
        <h3 class="font-bold text-lg">Panel Kontrol Akses Fitur Portal SPMB</h3>
      </div>
      
      <form action="update_setting.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
        <div class="bg-slate-800/80 p-4 rounded-lg border border-slate-700">
          <label class="block text-sm font-bold text-slate-200 mb-2">
            <i class="fa-solid fa-clipboard-list text-blue-400 mr-1.5"></i> Status Tombol Registrasi SPMB
          </label>
          <div class="flex gap-4">
            <label class="flex items-center gap-2 cursor-pointer bg-slate-900/60 px-4 py-2 rounded border border-slate-700">
              <input type="radio" name="status_registrasi" value="BUKA" <?= $setting['status_registrasi'] === 'BUKA' ? 'checked' : '' ?> class="text-emerald-500">
              <span class="text-sm font-semibold text-emerald-400">BUKA</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer bg-slate-900/60 px-4 py-2 rounded border border-slate-700">
              <input type="radio" name="status_registrasi" value="TUTUP" <?= $setting['status_registrasi'] === 'TUTUP' ? 'checked' : '' ?> class="text-red-500">
              <span class="text-sm font-semibold text-red-400">TUTUP</span>
            </label>
          </div>
        </div>

        <div class="bg-slate-800/80 p-4 rounded-lg border border-slate-700">
          <label class="block text-sm font-bold text-slate-200 mb-2">
            <i class="fa-solid fa-square-poll-vertical text-yellow-400 mr-1.5"></i> Status Tombol Hasil Observasi
          </label>
          <div class="flex gap-4">
            <label class="flex items-center gap-2 cursor-pointer bg-slate-900/60 px-4 py-2 rounded border border-slate-700">
              <input type="radio" name="status_observasi" value="BUKA" <?= $setting['status_observasi'] === 'BUKA' ? 'checked' : '' ?> class="text-emerald-500">
              <span class="text-sm font-semibold text-emerald-400">BUKA</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer bg-slate-900/60 px-4 py-2 rounded border border-slate-700">
              <input type="radio" name="status_observasi" value="TUTUP" <?= $setting['status_observasi'] === 'TUTUP' ? 'checked' : '' ?> class="text-red-500">
              <span class="text-sm font-semibold text-red-400">TUTUP</span>
            </label>
          </div>
        </div>

        <div class="md:col-span-2 text-right">
          <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-bold px-6 py-2.5 rounded-lg text-sm shadow transition inline-flex items-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Pengaturan
          </button>
        </div>
      </form>
    </div>

    <!-- TABEL 1: DATA PENDAFTAR INDEN / TITIP NAMA -->
    <div id="tabel-inden" class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-2">
        <div>
          <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
            <i class="fa-solid fa-id-card text-amber-500"></i> Data Pendaftar Inden (Titip Nama & Token)
          </h3>
          <p class="text-xs text-slate-500">Pendaftar yang telah mengisi form inden dan membayar biaya komitmen Rp 500.000[cite: 5]</p>
        </div>
        <span class="text-xs bg-amber-100 text-amber-800 px-3 py-1 rounded-full font-bold border border-amber-200">
          Total: <?= $result_inden ? $result_inden->num_rows : 0 ?> Calon Siswa
        </span>
      </div>
      
      <div class="overflow-x-auto">
        <table class="w-full text-xs text-left border-collapse">
          <thead>
            <tr class="bg-slate-100 border-b border-slate-200 text-slate-700">
              <th class="p-3">Token Inden</th>
              <th class="p-3">Nama Siswa</th>
              <th class="p-3">Tahun Ajaran</th>
              <th class="p-3">Asal Sekolah & Jenjang</th>
              <th class="p-3">Kontak Orang Tua</th>
              <th class="p-3">Bukti Bayar</th>
              <th class="p-3 text-center">Status Bayar</th>
              <th class="p-3 text-center">Aksi Verifikasi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            if ($result_inden && $result_inden->num_rows > 0): 
                while ($row = $result_inden->fetch_assoc()): 
                    $wa_clean = preg_replace('/[^0-9]/', '', $row['wa_ayah']);
                    if (substr($wa_clean, 0, 1) === '0') {
                        $wa_clean = '62' . substr($wa_clean, 1);
                    }
            ?>
            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
              <td class="p-3 font-mono font-bold text-blue-900 bg-blue-50/50 rounded select-all">
                <?= htmlspecialchars($row['token_inden']) ?>[cite: 3]
              </td>
              <td class="p-3">
                <strong class="text-slate-800 text-sm block"><?= htmlspecialchars($row['nama_siswa']) ?></strong>[cite: 3]
                <span class="text-[11px] text-slate-500">JK: <?= htmlspecialchars($row['jenis_kelamin']) ?> | Tgl Lahir: <?= date('d/m/Y', strtotime($row['tanggal_lahir'])) ?>[cite: 3]</span>
              </td>
              <td class="p-3 font-bold text-amber-700">
                TA <?= htmlspecialchars($row['tahun_ajaran']) ?>[cite: 3]
              </td>
              <td class="p-3 text-slate-600">
                <?= htmlspecialchars($row['asal_sekolah']) ?>[cite: 3]<br>
                <span class="text-[11px] text-slate-400">(<?= htmlspecialchars($row['jenjang']) ?>)[cite: 3]</span>
              </td>
              <td class="p-3 text-slate-600">
                <div>Ayah: <strong><?= htmlspecialchars($row['nama_ayah']) ?></strong></div>[cite: 3]
                <div>Ibu: <strong><?= htmlspecialchars($row['nama_ibu']) ?></strong></div>[cite: 3]
                <a href="https://wa.me/<?= $wa_clean ?>" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-emerald-600 font-bold hover:underline mt-0.5">
                  <i class="fa-brands fa-whatsapp"></i> WA: <?= htmlspecialchars($row['wa_ayah']) ?>[cite: 3]
                </a>
              </td>
              <td class="p-3">
                <?php if (!empty($row['bukti_pembayaran'])): ?>[cite: 3]
                  <a href="uploads/bukti_inden/<?= htmlspecialchars($row['bukti_pembayaran']) ?>" target="_blank" class="inline-flex items-center gap-1 text-[11px] bg-slate-100 hover:bg-slate-200 text-slate-700 px-2 py-1 rounded border border-slate-300 font-semibold transition">
                    <i class="fa-solid fa-image text-blue-600"></i> Lihat Bukti[cite: 3]
                  </a>
                <?php else: ?>
                  <span class="text-slate-400 italic">-</span>
                <?php endif; ?>
              </td>
              <td class="p-3 text-center">
                <?php if ($row['status_pembayaran'] === 'Verified'): ?>[cite: 3]
                  <span class="bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-full font-bold border border-emerald-300 text-[10px]">VERIFIED</span>[cite: 3]
                <?php else: ?>
                  <span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full font-bold border border-amber-300 text-[10px]">PENDING</span>[cite: 3]
                <?php endif; ?>
              </td>
              <td class="p-3 text-center">
                <?php if ($row['status_pembayaran'] === 'Verified'): ?>[cite: 3]
                  <a href="verify_inden.php?id=<?= $row['id'] ?>&status=Pending" onclick="return confirm('Batalkan status verifikasi pendaftar ini?')" class="text-[11px] bg-red-100 hover:bg-red-200 text-red-700 px-2.5 py-1 rounded font-semibold transition">
                    Batalkan
                  </a>
                <?php else: ?>
                  <a href="verify_inden.php?id=<?= $row['id'] ?>&status=Verified" onclick="return confirm('Verifikasi pembayaran Rp 500.000 untuk pendaftar ini?')" class="text-[11px] bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 py-1 rounded font-bold shadow transition">
                    Verifikasi
                  </a>
                <?php endif; ?>
              </td>
            </tr>
            <?php 
                endwhile; 
            else: 
            ?>
            <tr>
              <td colspan="8" class="p-8 text-center text-slate-500 italic">Belum ada data pendaftar Inden (Titip Nama).</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- TABEL 2: UPLOAD CSV & DATA OBSERVASI -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
      <div class="flex items-start gap-4 mb-4">
        <div class="bg-emerald-100 p-3 rounded-full text-emerald-600">
          <i class="fa-solid fa-file-csv text-2xl"></i>
        </div>
        <div class="flex-grow">
          <h3 class="font-bold text-lg text-slate-800 mb-1">Upload Update Hasil Observasi (CSV)</h3>
          <p class="text-sm text-slate-500 mb-2">Urutan Kolom CSV: <strong>Nama | Asal TK | Alamat | No WA | Username | Password | Status</strong></p>
          <div class="mb-3">
            <a href="download_template.php" class="text-xs bg-slate-200 hover:bg-slate-300 text-slate-700 px-3 py-1.5 rounded transition inline-flex items-center gap-1 font-semibold border border-slate-300">
              <i class="fa-solid fa-download"></i> Unduh Template CSV Baru
            </a>
          </div>
          <form action="upload_csv.php" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-3">
            <input type="file" name="file_csv" accept=".csv" required class="flex-grow text-sm border border-slate-300 p-2 rounded cursor-pointer bg-slate-50">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm py-2 px-6 rounded transition shadow flex items-center justify-center gap-2">
              <i class="fa-solid fa-cloud-arrow-up"></i> Import CSV
            </button>
          </form>
        </div>
      </div>

      <div class="flex justify-between items-center my-4 border-t pt-4">
        <h3 class="font-bold text-lg text-slate-800">Data Pendaftar & Status Observasi</h3>
        <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">Total: <?= $result_table ? $result_table->num_rows : 0 ?> Siswa</span>
      </div>
      
      <div class="overflow-x-auto">
        <table class="w-full text-xs text-left border-collapse">
          <thead>
            <tr class="bg-slate-100 border-b border-slate-200">
              <th class="p-3 text-slate-600">Nama Siswa</th>
              <th class="p-3 text-slate-600">Asal TK</th>
              <th class="p-3 text-slate-600">Hasil Observasi</th>
              <th class="p-3 text-slate-600">No. WA</th>
              <th class="p-3 text-slate-600">Username</th>
              <th class="p-3 text-slate-600">Password</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            if ($result_table && $result_table->num_rows > 0): 
                while ($row = $result_table->fetch_assoc()): 
                    $st = strtoupper(trim($row['status']));
            ?>
            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
              <td class="p-3 font-semibold text-slate-800"><?= htmlspecialchars($row['nama_siswa']) ?></td>
              <td class="p-3 text-slate-600"><?= htmlspecialchars($row['asal_tk']) ?></td>
              <td class="p-3">
                <?php 
                  if ($st === 'DAPAT MELANJUTKAN' || $st === 'MELANJUTKAN') {
                      echo '<span class="bg-blue-100 text-blue-800 px-2.5 py-1 rounded text-xs font-bold border border-blue-200">MELANJUTKAN</span>';
                  } elseif ($st === 'BELUM DAPAT MELANJUTKAN' || $st === 'BELUM MELANJUTKAN') {
                      echo '<span class="bg-slate-100 text-slate-800 px-2.5 py-1 rounded text-xs font-bold border border-slate-200">BELUM MELANJUTKAN</span>';
                  } else {
                      echo '<span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded text-xs font-bold border border-slate-200">'.htmlspecialchars($st).'</span>';
                  }
                ?>
              </td>
              <td class="p-3 font-mono text-slate-600 text-xs"><?= (isset($row['no_wa']) && !empty($row['no_wa'])) ? htmlspecialchars($row['no_wa']) : '-' ?></td>
              <td class="p-3 font-mono text-slate-600 text-xs"><?= htmlspecialchars($row['username']) ?></td>
              <td class="p-3 font-mono text-slate-600 text-xs"><?= (isset($row['password']) && !empty($row['password'])) ? htmlspecialchars($row['password']) : '-' ?></td>
            </tr>
            <?php 
                endwhile; 
            else: 
            ?>
            <tr>
              <td colspan="6" class="p-8 text-center text-slate-500 italic">Belum ada data observasi.</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>

</body>
</html>