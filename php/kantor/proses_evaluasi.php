<?php
include '../config.php';
header('Content-Type: application/json');

$id_distribusi = $_POST['id_distribusi'] ?? '';
$status_distribusi = $_POST['status_distribusi'] ?? '';
$catatan = $_POST['catatan'] ?? '';


if (!$id_distribusi || !$status_distribusi) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Data tidak lengkap!'
    ]);
    exit;
}

$query = mysqli_query($conn, "INSERT INTO tb_evaluasi (id_distribusi, status_distribusi, catatan) VALUES ('$id_distribusi', '$status_distribusi', '$catatan')");
if ($query) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal simpan evaluasi: ' . mysqli_error($conn)
    ]);
}
