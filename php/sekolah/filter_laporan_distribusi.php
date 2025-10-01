<?php
include '../config.php';

$tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
$tanggal_akhir = $_POST['tanggal_akhir'] ?? '';

$where = "WHERE tb_distribusi.status_konfirmasi = '1'";
if ($tanggal_mulai && $tanggal_akhir) {
    $where .= " AND tb_distribusi.tanggal BETWEEN '$tanggal_mulai' AND '$tanggal_akhir'";
} elseif ($tanggal_mulai) {
    $where .= " AND tb_distribusi.tanggal >= '$tanggal_mulai'";
} elseif ($tanggal_akhir) {
    $where .= " AND tb_distribusi.tanggal <= '$tanggal_akhir'";
}

$query = "SELECT tb_distribusi.*, tb_users.nama, tb_sekolah.nama_sekolah AS sekolah_tujuan
          FROM tb_distribusi 
          JOIN tb_users ON tb_distribusi.id_petugas_distribusi = tb_users.id_users 
          JOIN tb_sekolah ON tb_distribusi.id_sekolah_tujuan = tb_sekolah.id_sekolah
          $where";
$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
