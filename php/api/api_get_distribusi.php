<?php
// Atur header agar outputnya berupa JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Izinkan akses dari mana saja (untuk development)

// Sertakan file konfigurasi database Anda
include '../config.php'; // Sesuaikan path jika perlu

// Query utama dari file data_distribusi.php
$query = "SELECT 
            tb_distribusi.id_distribusi,
            tb_distribusi.tanggal,
            tb_distribusi.jumlah,
            tb_distribusi.jam,
            tb_distribusi.status_pengiriman,
            tb_distribusi.lokasi_gps,
            tb_distribusi.foto,
            tb_users.nama AS nama_petugas,
            tb_sekolah.nama_sekolah AS sekolah_tujuan
          FROM tb_distribusi 
          JOIN tb_users ON tb_distribusi.id_petugas_distribusi = tb_users.id_users 
          JOIN tb_sekolah ON tb_distribusi.id_sekolah_tujuan = tb_sekolah.id_sekolah 
          ORDER BY tb_distribusi.tanggal DESC";

$result = mysqli_query($conn, $query);

$data_distribusi = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data_distribusi[] = $row;
    }
}

echo json_encode($data_distribusi);

mysqli_close($conn);
