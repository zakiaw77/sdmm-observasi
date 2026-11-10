<?php
$filename = "Template_Data_Observasi_SDMM.csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '";');

$output = fopen('php://output', 'w');

// Header Kolom CSV Terbaru
$header = ['Nama', 'Asal TK', 'Alamat', 'No WA', 'Username', 'Password', 'Status'];
fputcsv($output, $header);

// Contoh Isian Data
$contoh_data1 = ['Ahmad Zaki Pratama', 'TK ABA 1 Manyar', 'Jl. Amuntai No 1 GKB', '081234567890', 'OBS2026001', 'sdm123', 'MELANJUTKAN'];
$contoh_data2 = ['Siti Nur Aisyah', 'TK Muslimat NU', 'Jl. Kalimantan No 10', '089876543210', 'OBS2026002', 'sdm124', 'BELUM MELANJUTKAN'];

fputcsv($output, $contoh_data1);
fputcsv($output, $contoh_data2);

fclose($output);
exit();
?>