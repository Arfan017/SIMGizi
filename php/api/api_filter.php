<?php
include '../config.php';

$tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
$tanggal_akhir = $_POST['tanggal_akhir'] ?? '';
$sekolah = $_POST['sekolah'] ?? '';

// $nama_sekolah = '';
$where = "WHERE status_konfirmasi = '1'";
if ($tanggal_mulai && $tanggal_akhir) {
    $where .= " AND tanggal BETWEEN '$tanggal_mulai' AND '$tanggal_akhir'";
} elseif ($tanggal_mulai) {
    $where .= " AND tanggal >= '$tanggal_mulai'";
} elseif ($tanggal_akhir) {
    $where .= " AND tanggal <= '$tanggal_akhir'";   
}
if ($sekolah) {
    $where .= " AND id_sekolah_tujuan = '$sekolah'";
    // $nama_sekolah = getNamaSekolah($conn, $sekolah);
}

$query = "SELECT tb_distribusi.*, tb_sekolah.nama_sekolah as sekolah_tujuan FROM tb_distribusi 
JOIN tb_sekolah ON tb_distribusi.id_sekolah_tujuan = tb_sekolah.id_sekolah $where ORDER BY tanggal DESC";

$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);

// function getNamaSekolah($conn, $id_sekolah) {
//     $query = "SELECT nama_sekolah FROM tb_sekolah WHERE id_sekolah = '$id_sekolah'";
//     $result = mysqli_query($conn, $query);
//     if ($row = mysqli_fetch_assoc($result)) {
//         return $row['nama_sekolah'];
//     }
//     return '';
// }