<?php
include '../config.php';

$query = "SELECT tb_distribusi.*, tb_sekolah.nama_sekolah AS sekolah_tujuan, tb_users.nama AS petugas_distribusi FROM tb_distribusi 
    JOIN tb_sekolah ON tb_sekolah.id_sekolah = tb_distribusi.id_sekolah_tujuan
    JOIN tb_users ON tb_users.id_users = tb_distribusi.id_petugas_distribusi
    WHERE tb_distribusi.status_konfirmasi = '0' AND tb_distribusi.status_pengiriman = '1' ORDER BY tb_distribusi.tanggal DESC";

$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
