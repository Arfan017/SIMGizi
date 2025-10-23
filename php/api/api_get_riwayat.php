<?php
header('Content-Type: application/json');
include '../config.php';

$query = "SELECT 
            tb_distribusi.id_distribusi,
            tb_distribusi.tanggal,
            tb_distribusi.jam,
            tb_distribusi.jumlah,
            tb_distribusi.status_pengiriman,
            tb_distribusi.lokasi_gps,
            tb_distribusi.foto,
            tb_users.nama AS nama_petugas,
            tb_sekolah.nama_sekolah AS sekolah_tujuan
          FROM tb_distribusi 
          JOIN tb_users ON tb_distribusi.id_petugas_distribusi = tb_users.id_users 
          JOIN tb_sekolah ON tb_distribusi.id_sekolah_tujuan = tb_sekolah.id_sekolah 
          WHERE tb_distribusi.status_pengiriman = '2'
          ORDER BY tb_distribusi.tanggal DESC";

$result = mysqli_query($conn, $query);
$data_riwayat = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data_riwayat[] = $row;
    }
}

echo json_encode($data_riwayat);
mysqli_close($conn);
