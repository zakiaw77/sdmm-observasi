<?php
require_once 'config.php';

// Header Anti-Cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Cek Proteksi Login
if (
    !isset($_SESSION['login_siswa']) ||
    $_SESSION['login_siswa'] !== true ||
    !isset($_SESSION['username']) ||
    trim($_SESSION['username']) === ''
) {
    header("Location: index.php");
    exit();
}

$session_user = trim($_SESSION['username']);

// Proteksi Tambahan: Jika ada parameter 'u' di URL, pastikan HARUS SAMA dengan Username di Session
if (isset($_GET['u']) && trim($_GET['u']) !== $session_user) {
    // Jika tidak cocok (berarti tab terbuka menggunakan cache user lain), paksa panggil ulang session user aktif
    header("Location: surat_pengumuman.php?u=" . urlencode($session_user) . "&v=" . time());
    exit();
}

// Query Murni berdasarkan Session Username yang Sah
$query = "SELECT id, username, nama_siswa, asal_tk, status FROM pengumuman WHERE username = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $session_user);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

$status = strtoupper(trim($data['status']));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <title>Surat Pengumuman Hasil Observasi - <?= htmlspecialchars($data['nama_siswa']) ?></title>
    <link rel="shortcut icon" href="assets/OBS.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Pengaturan ukuran kertas F4 / Folio untuk Cetak & PDF */
        @page { 
            size: 215.9mm 330mm; 
            margin: 10mm 10mm; 
        }
        @media print {
            .no-print { display: none !important; }
            body { background-color: white; padding: 0; }
            .kertas-f4 { 
                box-shadow: none; 
                border: none; 
                margin: 0; 
                padding: 0; 
                width: 100%; 
            }
        }
        .kertas-f4 { 
            width: 215.9mm; 
            min-height: 330mm; 
            font-family: 'Times New Roman', Times, serif; 
        }

        .body-text {
            margin-top: 8px;
            text-align: justify;
            line-height: 1.6;
            font-size: 15px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body class="bg-gray-200 text-gray-900 flex justify-center py-6 no-print-bg">

    <!-- Tombol Melayang (Hanya muncul di layar, hilang saat diprint/save PDF) -->
    <div class="fixed top-5 right-5 no-print flex flex-col gap-2 z-50 items-end">
        <div class="flex gap-2">
            <a href="dashboard.php" class="bg-gray-600 text-white px-4 py-2 rounded shadow hover:bg-gray-700 font-sans text-sm flex items-center gap-1 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="bg-blue-800 text-white px-5 py-2 rounded shadow hover:bg-blue-900 font-bold font-sans text-sm flex items-center gap-2 transition">
                <i class="fa-solid fa-print"></i> Simpan sebagai PDF / Cetak
            </button>
        </div>

        <!-- Tombol Merah ke Waiting List (Hanya tampil jika status BELUM/TIDAK DAPAT MELANJUTKAN) -->
        <?php if ($status !== 'DAPAT MELANJUTKAN' && $status !== 'MELANJUTKAN'): ?>
            <a href="https://bit.ly/WaitingList_CalonSiswa_SDMM" target="_blank" class="w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold px-5 py-2.5 rounded shadow-lg transition duration-200 font-sans text-sm flex items-center justify-center gap-2 border border-red-800 animate-pulse">
                <i class="fa-solid fa-pen-to-square"></i> Isi Form Waiting List
            </a>
        <?php endif; ?>
    </div>

    <!-- Lembar Kertas F4 / Folio -->
    <div class="kertas-f4 bg-white p-10 shadow-2xl border border-gray-300 text-[15px] leading-relaxed relative">
        
        <!-- KOP SURAT (GAMBAR) -->
        <div class="mb-4">
            <img src="assets/kop_surat.png" alt="Kop Surat SDMM" class="w-full h-auto object-contain">
        </div>

        <!-- Nomor Surat, Perihal, dan Tanggal -->
        <div class="flex justify-between items-start mb-4 text-sm">
            <table>
                <tr>
                    <td class="pr-3 vertical-top">Nomor</td>
                    <td class="vertical-top">: 229/IV.4/F/2026</td>
                </tr>
                <tr>
                    <td class="pr-3 vertical-top">Lampiran</td>
                    <td class="vertical-top">: -</td>
                </tr>
                <tr>
                    <td class="pr-3 vertical-top">Perihal</td>
                    <td class="vertical-top">: <strong>Pengumuman Keputusan Hasil Observasi Calon<br>Murid SPMB TA 2028/2029</strong></td>
                </tr>
            </table>
            <div class="text-right">
                <p>Gresik, &nbsp;<u>12 Rabiul Awal 1448 H</u></p>
                <p class="pr-7">23 September 2026 M</p>
            </div>
        </div>

        <!-- Teks Basmalah (Tengah) -->
        <div class="text-center text-xl my-4 font-serif">
            بِسْمِ اللهِ الرَّحْمَنِ الرَّحِيمِ
        </div>

        <!-- Tujuan Surat -->
        <div class="mb-5 text-sm">
            <p>Kepada Yth.</p>
            <p><strong>Bapak/Ibu Peserta SPMB TA 2028/2029</strong></p>
            <p>SD Muhammadiyah Manyar</p>
            <p>di tempat</p>
        </div>

        <!-- Isi Surat Dinamis -->
        <div>
            <p class="body-text pl-6">
                <em>Assalamu'alaikum warahmatullahi wabarakatuh</em>
            </p>
            
            <p class="body-text pl-6">
                Puji syukur kehadirat Allah SWT semoga dalam menjalankan aktivitas sehari-hari kita selalu dalam lindungan-Nya. Amin
            </p>
            
            <p class="body-text pl-6">
                Sehubungan dengan telah dilaksanakan Observasi Calon Murid dalam rangka Sistem Penerimaan Murid Baru (SPMB) SD Muhammadiyah Manyar Tahun Ajaran 2028/2029, dengan ini kami menyampaikan keputusan observasi putra/putri Bapak dan Ibu sebagai berikut:
            </p>
            
            <!-- Detail Calon Siswa -->
            <div class="my-3 pl-12 space-y-1">
                <table class="w-full">
                    <tr>
                        <td class="w-44 py-0.5">Nama Calon Murid</td>
                        <td class="py-0.5">: <strong><?= htmlspecialchars($data['nama_siswa']) ?></strong></td>
                    </tr>
                    <tr>
                        <td class="py-0.5">Asal TK</td>
                        <td class="py-0.5">: <strong><?= htmlspecialchars($data['asal_tk']) ?></strong></td>
                    </tr>
                    <tr>
                        <td class="py-0.5">Keputusan Hasil Observasi</td>
                        <td class="py-0.5">: 
                            <strong class="uppercase font-bold <?= ($status === 'DAPAT MELANJUTKAN' || $status === 'MELANJUTKAN') ? 'text-green-800' : 'text-red-800' ?>">
                                <?= htmlspecialchars($status) ?>
                            </strong> Tahap SPMB TA 2028/2029
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Keterangan Lanjutan BERDASARKAN STATUS (IF-ELSE DITERAPKAN DENGAN BENAR) -->
            <?php if ($status === 'DAPAT MELANJUTKAN' || $status === 'MELANJUTKAN'): ?>
                <p class="body-text pl-6">
                    Bagi calon Murid yang dinyatakan <strong>DAPAT MELANJUTKAN Tahap SPMB TA 2028/2029</strong> disarankan mengikuti tahap pendaftaran selanjutnya pada <u>Kamis-Jum'at, 24 - 25 September 2026 pukul 08.00 - 14.00 WIB</u> di Ruang Hachi Library SD Muhammadiyah Manyar dengan menyertakan persyaratan berikut :
                </p>

                <!-- Rincian Biaya Persyaratan (Sejajar dan Rapi) -->
                <table class="ml-16 my-2 text-sm">
                    <tr>
                        <td class="pr-2 vertical-top">1.</td>
                        <td class="pr-4 vertical-top">Biaya Psikotest</td>
                        <td class="vertical-top"><u>Rp. 350.000,-</u></td>
                    </tr>
                    <tr>
                        <td class="pr-2 vertical-top">2.</td>
                        <td class="pr-4 vertical-top">Biaya DP DPP Termin 1</td>
                        <td class="vertical-top"><u>Rp. 5.000.000,-</u></td>
                    </tr>
                </table>
            <?php else: ?>
                <p class="body-text pl-6">
                    Bagi calon Murid yang dinyatakan <strong>BELUM DAPAT MELANJUTKAN Tahap SPMB TA 2028/2029</strong> diperkenankan mengisi link <em>waiting list</em> melalui tautan berikut: <a href="https://bit.ly/WaitingList_CalonSiswa_SDMM" target="_blank" class="text-blue-700 underline font-semibold">bit.ly/WaitingList_CalonSiswa_SDMM</a>
                </p>
            <?php endif; ?>

            <p class="body-text pl-6">
                Untuk informasi dan konfirmasi lebih lanjut, Bapak/Ibu dapat menghubungi Panitia SPMB melalui nomor berikut <strong>085179720239 & 082141324602</strong>.
            </p>

            <p class="body-text pl-6">
                Demikian yang dapat kami sampaikan, atas perhatian dan kerja samanya disampaikan terima kasih.
            </p>
            
            <p class="body-text pl-6">
                <em>Wassalamu'alaikum warahmatullahi wabarakatuh</em>
            </p>
        </div>

        <!-- Tanda Tangan & Stempel -->
        <div class="mt-8 flex justify-between items-end">
            <!-- Tembusan -->
            <div class="text-xs text-gray-800">
                <p>Tembusan:</p>
                <ol class="list-decimal pl-4">
                    <li>Majelis Dikdasmen dan PNF PRM PPI</li>
                    <li>Arsip</li>
                </ol>
            </div>

            <!-- Gambar Stempel + TTD -->
            
                <div class="my-1 flex justify-center relative">
                    <img src="assets/stempel_ttd.png" alt="Stempel dan TTD Kepala Sekolah" class="h-35 object-contain">
                </div>

        </div>

    </div>
</body>
</html>