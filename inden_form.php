<?php
require_once 'config.php';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Titip Nama (Inden) - SDMM</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="shortcut icon" href="assets/OBS.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen py-8 font-sans">

  <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200">
    
    <!-- Header Form -->
    <div class="bg-blue-900 text-white p-6 text-center">
      <div class="flex justify-center mb-2">
        <div class="bg-yellow-400 text-blue-950 px-3 py-1 rounded-full font-bold text-xs">SDMM INDEN</div>
      </div>
      <h2 class="text-2xl font-bold">Formulir Titip Nama (Inden) SPMB</h2>
      <p class="text-xs text-blue-200 mt-1">SD Muhammadiyah Manyar Gresik</p>
    </div>

    <form action="process_inden.php" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
      
      <!-- DATA SISWA -->
      <div>
        <h3 class="text-lg font-bold text-slate-800 border-b pb-2 mb-4 flex items-center gap-2">
          <i class="fa-solid fa-child text-blue-700"></i> Data Calon Siswa
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          
          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap Calon Siswa *</label>
            <input type="text" name="nama_siswa" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kelamin *</label>
            <select name="jenis_kelamin" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
              <option value="">-- Pilih Jenis Kelamin --</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Asal Sekolah *</label>
            <input type="text" name="asal_sekolah" placeholder="Contoh: TK ABA 36 PPI" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Jenjang Pendidikan *</label>
            <select name="jenjang" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
              <option value="">-- Pilih Jenjang --</option>
              <option value="TK A">TK A</option>
              <option value="KB (Kelompok Bermain)/Play Group">KB (Kelompok Bermain)/Play Group</option>
              <option value="Belum sekolah">Belum sekolah</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Lahir *</label>
            <input type="date" id="tgl_lahir" name="tanggal_lahir" required onchange="filterTahunAjaran()" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-1">Titip Nama untuk SPMB Tahun Ajaran *</label>
            <select id="tahun_ajaran" name="tahun_ajaran" required disabled class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50">
              <option value="">-- Silakan Pilih Tanggal Lahir Dahulu --</option>
            </select>
            <p id="age_warning" class="text-xs text-red-600 mt-1 hidden font-semibold">
              <i class="fa-solid fa-triangle-exclamation"></i> Usia calon siswa tidak memenuhi rentang masuk kelas 1 SD (5 thn 8 bln s.d. 8 thn per 1 Juli).
            </p>
          </div>

        </div>
      </div>

      <!-- DATA ORANG TUA -->
      <div>
        <h3 class="text-lg font-bold text-slate-800 border-b pb-2 mb-4 flex items-center gap-2">
          <i class="fa-solid fa-users text-blue-700"></i> Data Orang Tua
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap Ayah *</label>
            <input type="text" name="nama_ayah" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Nomor Whatsapp Ayah *</label>
            <input type="tel" name="wa_ayah" placeholder="08xxxxxxxxxx" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap Ibu *</label>
            <input type="text" name="nama_ibu" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Nomor Whatsapp Ibu *</label>
            <input type="tel" name="wa_ibu" placeholder="08xxxxxxxxxx" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Lengkap Domisili *</label>
            <textarea name="alamat" rows="2" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
          </div>

        </div>
      </div>

      <!-- DATA KONFIRMASI -->
      <div>
        <h3 class="text-lg font-bold text-slate-800 border-b pb-2 mb-4 flex items-center gap-2">
          <i class="fa-solid fa-clipboard-check text-blue-700"></i> Data Konfirmasi & Kebutuhan Khusus
        </h3>
        <div class="space-y-4">
          
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Apakah ada saudara kandung yang bersekolah di SDMM? *</label>
            <div class="flex gap-6 mt-1">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="ada_saudara" value="Ada" onchange="toggleSaudara(true)" required class="text-blue-600 focus:ring-blue-500">
                <span class="text-sm">Ada</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="ada_saudara" value="Tidak ada" onchange="toggleSaudara(false)" required class="text-blue-600 focus:ring-blue-500">
                <span class="text-sm">Tidak ada</span>
              </label>
            </div>
          </div>

          <div id="box_saudara" class="hidden">
            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap & Tahun Angkatan Saudara Kandung *</label>
            <input type="text" id="detail_saudara" name="detail_saudara" placeholder="Contoh: Muhammad Ali (Angkatan 2024)" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Catatan Kondisi / Kebutuhan Khusus (PDPD)</label>
            <textarea name="catatan_khusus" rows="2" placeholder="Isi apabila calon siswa merupakan Peserta Didik Penyandang Disabilitas (PDPD) atau kebutuhan khusus. Kosongkan jika tidak ada." class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
          </div>

          <!-- UPLOAD BUKTI PEMBAYARAN INDEN -->
          <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl">
            <label class="block text-sm font-bold text-amber-900 mb-1">Upload Bukti Pembayaran Titip Nama / Inden (Rp 500.000) *</label>
            <p class="text-xs text-amber-700 mb-2">Format: JPG, PNG, atau PDF (Maksimal 2MB).</p>
            <input type="file" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.pdf" required class="w-full text-sm border border-amber-300 rounded-lg p-2 bg-white">
          </div>

        </div>
      </div>

      <!-- WARNING & PERNYATAAN KEBENARAN DATA -->
      <div class="bg-slate-50 border border-slate-300 p-4 rounded-xl space-y-3">
        <div class="text-xs text-red-600 space-y-1">
          <p class="font-bold flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation"></i> MOHON DIPERHATIKAN:</p>
          <p>Kesalahan atau ketidaksesuaian data akibat pengisian formulir menjadi tanggung jawab pengisi dan bukan tanggung jawab pihak sekolah. Mohon periksa kembali seluruh data sebelum dikirim.</p>
        </div>

        <div class="pt-2 border-t border-slate-200">
          <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox" required class="mt-1 text-blue-600 focus:ring-blue-500 rounded">
            <span class="text-xs font-semibold text-slate-700 leading-snug">
              Saya menyatakan bahwa seluruh data yang saya isikan dalam formulir ini telah diperiksa dan dipastikan benar serta sesuai. Saya bertanggung jawab atas kebenaran data yang saya kirimkan.
            </span>
          </label>
        </div>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3.5 rounded-xl shadow-lg transition duration-200 text-base">
        Kirim Form Inden & Dapatkan Token
      </button>

    </form>
  </div>

  <!-- SCRIPT FILTER TANGGAL LAHIR & TOGGLE SAUDARA -->
  <script>
    // Tabel Rentang Usia Sesuai Aturan SDMM
    const rentangTA = [
      { ta: "2029/2030", min: "2021-07-01", max: "2023-11-01" },
      { ta: "2030/2031", min: "2022-07-01", max: "2024-11-01" },
      { ta: "2031/2032", min: "2023-07-01", max: "2025-11-01" },
      { ta: "2032/2033", min: "2024-07-01", max: "2026-11-01" },
      { ta: "2033/2034", min: "2025-07-01", max: "2027-11-01" }
    ];

    function filterTahunAjaran() {
      const tglInput = document.getElementById('tgl_lahir').value;
      const selectTA = document.getElementById('tahun_ajaran');
      const warning  = document.getElementById('age_warning');

      selectTA.innerHTML = '';
      warning.classList.add('hidden');

      if (!tglInput) {
        selectTA.disabled = true;
        selectTA.innerHTML = '<option value="">-- Silakan Pilih Tanggal Lahir Dahulu --</option>';
        return;
      }

      let count = 0;
      rentangTA.forEach(item => {
        if (tglInput >= item.min && tglInput <= item.max) {
          const opt = document.createElement('option');
          opt.value = item.ta;
          opt.textContent = `Tahun Ajaran ${item.ta} (Memenuhi Syarat Usia)`;
          selectTA.appendChild(opt);
          count++;
        }
      });

      if (count > 0) {
        selectTA.disabled = false;
        selectTA.classList.remove('bg-slate-50');
      } else {
        selectTA.disabled = true;
        selectTA.classList.add('bg-slate-50');
        selectTA.innerHTML = '<option value="">-- Tidak Memenuhi Rentang Usia --</option>';
        warning.classList.remove('hidden');
      }
    }

    function toggleSaudara(show) {
      const box = document.getElementById('box_saudara');
      const input = document.getElementById('detail_saudara');
      if (show) {
        box.classList.remove('hidden');
        input.required = true;
      } else {
        box.classList.add('hidden');
        input.required = false;
        input.value = '';
      }
    }
  </script>

</body>
</html>