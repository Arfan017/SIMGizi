<?php
header('Content-Type: application/json');
include '../config.php';

$query = "SELECT id_sekolah, nama_sekolah, lokasi_gps FROM tb_sekolah ORDER BY nama_sekolah ASC";
$result = mysqli_query($conn, $query);
$sekolah = [];

while ($row = mysqli_fetch_assoc($result)) {
    $sekolah[] = $row;
}

echo json_encode($sekolah);
mysqli_close($conn);
