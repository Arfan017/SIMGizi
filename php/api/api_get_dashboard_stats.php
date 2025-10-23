<?php
header('Content-Type: application/json');
include '../config.php';

// Jumlah seluruh data distribusi
$q_total = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tb_distribusi");
$total_distribusi = mysqli_fetch_assoc($q_total)['total'];

// Jumlah terkonfirmasi (status = 1)
$q_terkonfirmasi = mysqli_query($conn, "SELECT COUNT(*) AS terkonfirmasi FROM tb_distribusi WHERE status_konfirmasi = '1'");
$terkonfirmasi = mysqli_fetch_assoc($q_terkonfirmasi)['terkonfirmasi'];

// Jumlah belum terkonfirmasi (status = 0)
$q_belum = mysqli_query($conn, "SELECT COUNT(*) AS belum FROM tb_distribusi WHERE status_konfirmasi = '0'");
$belum_terkonfirmasi = mysqli_fetch_assoc($q_belum)['belum'];

$response = [
    'total_distribusi' => (int)$total_distribusi,
    'terkonfirmasi' => (int)$terkonfirmasi,
    'belum_terkonfirmasi' => (int)$belum_terkonfirmasi
];

echo json_encode($response);

mysqli_close($conn);
