<?php
include '../config.php';

$tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
$tanggal_akhir = $_POST['tanggal_akhir'] ?? '';
$sekolah = $_POST['sekolah'] ?? '';

$where = "WHERE status_konfirmasi = '1'";
if ($tanggal_mulai && $tanggal_akhir) {
    $where .= " AND tanggal BETWEEN '$tanggal_mulai' AND '$tanggal_akhir'";
} elseif ($tanggal_mulai) {
    $where .= " AND tanggal >= '$tanggal_mulai'";
} elseif ($tanggal_akhir) {
    $where .= " AND tanggal <= '$tanggal_akhir'";
}
if ($sekolah) {
    $where .= " AND tujuan = '$sekolah'";
}

$query = "SELECT * FROM tb_distribusi $where";
$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
