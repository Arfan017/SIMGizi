<?php
include '../config.php';

$sekolah = $_POST['sekolah'] ?? '';
$tglAwal = $_POST['tanggal_awal'] ?? '';
$tglAkhir = $_POST['tanggal_akhir'] ?? '';

$where = "WHERE status_evaluasi = '0' AND status_konfirmasi = '1'";

if ($tglAwal && $tglAkhir) {
    $where .= " AND tb_distribusi.tanggal BETWEEN '$tglAwal' AND '$tglAkhir'";
} elseif ($tglAwal) {
    $where .= " AND tb_distribusi.tanggal >= '$tglAwal'";
} elseif ($tglAkhir) {
    $where .= " AND tb_distribusi.tanggal <= '$tglAkhir'";
}

if ($sekolah) {
    $where .= " AND tb_distribusi.id_sekolah_tujuan = '$sekolah'";
}

$query = "SELECT tb_distribusi.*, tb_sekolah.nama_sekolah AS sekolah_tujuan, tb_konfirmasi_distribusi.gambar FROM tb_distribusi 
          JOIN tb_sekolah ON tb_distribusi.id_sekolah_tujuan = tb_sekolah.id_sekolah
          JOIN tb_konfirmasi_distribusi ON tb_distribusi.id_distribusi = tb_konfirmasi_distribusi.id_distribusi
          $where
          ORDER BY tanggal DESC";

$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) $data[] = $row;

header('Content-Type: application/json');
echo json_encode($data);