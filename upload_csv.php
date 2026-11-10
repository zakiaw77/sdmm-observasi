<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file_csv']) && $_FILES['file_csv']['error'] == 0) {
        $fileTmpPath = $_FILES['file_csv']['tmp_name'];
        $fileName = $_FILES['file_csv']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        if ($fileExtension === 'csv') {
            $file = fopen($fileTmpPath, 'r');
            fgetcsv($file); // Lewati baris pertama (Header)
            
            $sukses = 0; $gagal = 0;

            // Query UPDATE jika username sudah ada (ON DUPLICATE KEY UPDATE)
            $query = "INSERT INTO pengumuman (nama_siswa, asal_tk, alamat, no_wa, username, password, status) 
                      VALUES (?, ?, ?, ?, ?, ?, ?) 
                      ON DUPLICATE KEY UPDATE 
                      nama_siswa=VALUES(nama_siswa), 
                      asal_tk=VALUES(asal_tk), 
                      alamat=VALUES(alamat), 
                      no_wa=VALUES(no_wa), 
                      password=VALUES(password), 
                      status=VALUES(status)";
            $stmt = $conn->prepare($query);

            while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
                if (!empty($row[0])) {
                    $nama_siswa = $row[0];
                    $asal_tk    = $row[1];
                    $alamat     = isset($row[2]) ? $row[2] : '-';
                    $no_wa      = isset($row[3]) ? $row[3] : '-';
                    $username   = $row[4];
                    $password   = $row[5];
                    $status     = isset($row[6]) ? strtoupper(trim($row[6])) : 'MELANJUTKAN';

                    $stmt->bind_param("sssssss", $nama_siswa, $asal_tk, $alamat, $no_wa, $username, $password, $status);
                    if ($stmt->execute()) { $sukses++; } else { $gagal++; }
                }
            }
            fclose($file);
            $stmt->close();
            
            echo "<script>alert('Import CSV Selesai! $sukses data berhasil diperbarui.'); window.location.href='admin.php';</script>";
        } else {
            echo "<script>alert('Gagal! Harap pilih file berformat .csv'); window.location.href='admin.php';</script>";
        }
    }
}
?>