<?php
header('Content-Type: application/json');
include '../config.php';

$query = "SELECT id_distribusi, lokasi_terkini 
          FROM tb_distribusi 
          WHERE tanggal = CURDATE() 
          AND status_pengiriman = '1'
          AND lokasi_terkini IS NOT NULL";

$result = mysqli_query($conn, $query);
$lokasi_kurir = [];

while ($row = mysqli_fetch_assoc($result)) {
    $lokasi_kurir[] = $row;
}

echo json_encode($lokasi_kurir);
mysqli_close($conn);
