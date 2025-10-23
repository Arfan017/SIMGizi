<?php
header('Content-Type: application/json');
include '../config.php'; // Sesuaikan path

$tanggal_hari_ini = date("Y-m-d");
$sisa_stok = 0;

$stmt = $conn->prepare("SELECT jumlah_sisa FROM tb_stok_harian WHERE tanggal = ?");
$stmt->bind_param("s", $tanggal_hari_ini);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data_stok = $result->fetch_assoc();
    $sisa_stok = (int)$data_stok['jumlah_sisa'];
    $status = 'success';
} else {
    $status = 'not_found';
}

$stmt->close();
$conn->close();

echo json_encode([
    'status' => $status,
    'sisa_stok' => $sisa_stok,
    'tanggal' => $tanggal_hari_ini
]);
