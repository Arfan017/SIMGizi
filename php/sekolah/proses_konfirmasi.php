<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_distribusi = $_POST['id_distribusi'];
    $tanggal = date('Y-m-d');
    $jam = date('H:i:s');
    $status = $_POST['status'] === '1' ? 'Terima' : 'Tolak';
    $jumlah = $_POST['jumlah'];
    $catatan = $_POST['catatan'];

    $query = "INSERT INTO tb_konfirmasi_distribusi (id_distribusi, tanggal, jam, jumlah_diterima, status, catatan) VALUES ('$id_distribusi', '$tanggal', '$jam', '$jumlah', '$status', '$catatan')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal konfirmasi: ' . mysqli_error($conn)
        ]);
    }
}
