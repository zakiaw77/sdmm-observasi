<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil & Sanitasi Data
    $nama_siswa     = trim($_POST['nama_siswa']);
    $jenis_kelamin  = $_POST['jenis_kelamin'];
    $asal_sekolah   = trim($_POST['asal_sekolah']);
    $jenjang        = $_POST['jenjang'];
    $tanggal_lahir  = $_POST['tanggal_lahir'];
    $tahun_ajaran   = $_POST['tahun_ajaran'];
    $nama_ayah      = trim($_POST['nama_ayah']);
    $wa_ayah        = trim($_POST['wa_ayah']);
    $nama_ibu       = trim($_POST['nama_ibu']);
    $wa_ibu        = trim($_POST['wa_ibu']);
    $alamat         = trim($_POST['alamat']);
    $ada_saudara    = $_POST['ada_saudara'];
    $detail_saudara = ($ada_saudara === 'Ada') ? trim($_POST['detail_saudara']) : '-';
    $catatan_khusus = trim($_POST['catatan_khusus']);

    // Proses Upload Bukti Pembayaran
    if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] === 0) {
        $fileTmp   = $_FILES['bukti_pembayaran']['tmp_name'];
        $fileName  = $_FILES['bukti_pembayaran']['name'];
        $ext       = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed   = ['jpg', 'jpeg', 'png', 'pdf'];

        if (!in_array($ext, $allowed)) {
            echo "<script>alert('Format file tidak didukung! Gunakan JPG, PNG, atau PDF.'); window.history.back();</script>";
            exit();
        }

        // Folder Upload Bukti
        $uploadDir = 'uploads/bukti_inden/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $newFileName = 'INDEN_' . time() . '_' . rand(100, 999) . '.' . $ext;
        $destPath    = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmp, $destPath)) {
            
            // Generasi Kode Token Inden Unik (Contoh: INDEN-2930-8A7B)
            $ta_code = str_replace('/', '', substr($tahun_ajaran, 2, 7)); // '2029/2030' -> '2930'
            $random  = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
            $token_inden = "INDEN-" . $ta_code . "-" . $random;

            // Simpan Ke Database
            $query = "INSERT INTO tb_inden 
                      (token_inden, nama_siswa, jenis_kelamin, asal_sekolah, jenjang, tanggal_lahir, tahun_ajaran, nama_ayah, wa_ayah, nama_ibu, wa_ibu, alamat, ada_saudara, detail_saudara, catatan_khusus, bukti_pembayaran) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssssssssssss", $token_inden, $nama_siswa, $jenis_kelamin, $asal_sekolah, $jenjang, $tanggal_lahir, $tahun_ajaran, $nama_ayah, $wa_ayah, $nama_ibu, $wa_ibu, $alamat, $ada_saudara, $detail_saudara, $catatan_khusus, $newFileName);

            if ($stmt->execute()) {
                // Tampilan Sukses + Tampilkan Token
                ?>
                <!DOCTYPE html>
                <html lang="id">
                <head>
                  <meta charset="UTF-8">
                  <title>Bukti Token Inden - SDMM</title>
                  <script src="https://cdn.tailwindcss.com"></script>
                </head>
                <body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
                  <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 border border-slate-200 text-center">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-3xl mb-4">
                      ✓
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Pendaftaran Inden Berhasil!</h2>
                    <p class="text-xs text-slate-500 mt-1">Terima kasih, data titip nama ananda telah tersimpan.</p>
                    
                    <div class="my-6 p-4 bg-yellow-50 border border-yellow-300 rounded-xl">
                      <span class="block text-xs text-yellow-800 uppercase font-bold tracking-wider">Nomor Token Inden Anda</span>
                      <span class="block text-2xl font-black text-blue-900 tracking-widest my-2 select-all"><?= $token_inden ?></span>
                      <p class="text-[11px] text-yellow-700 leading-tight">
                        <strong>Simpan Token Ini!</strong> Token ini digunakan saat pendaftaran SPMB/Observasi untuk mendapatkan potongan <strong>Rp 500.000</strong> (Biaya observasi dari Rp 5.500.000 menjadi Rp 5.000.000).
                      </p>
                    </div>

                    <a href="dashboard.php" class="inline-block w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 rounded-lg text-sm transition">
                      Kembali ke Portal Utama
                    </a>
                  </div>
                </body>
                </html>
                <?php
                exit();
            } else {
                echo "<script>alert('Gagal menyimpan data ke database.'); window.history.back();</script>";
            }
        }
    }
}
?>