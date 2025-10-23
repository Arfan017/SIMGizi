<?php
include '../config.php';
header('Content-Type: application/json');

$id = $_POST['id_distribusi'] ?? '';
$status = $_POST['status'] ?? '';
$gps = $_POST['gps'] ?? '';

if (!$id || $status === '') {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap!']);
    exit;
}

$query = null;

if ($status == '1') {
    if ($gps === '') {
        echo json_encode(['status' => 'error', 'message' => 'Lokasi GPS awal tidak ditemukan!']);
        exit;
    }
    $query = mysqli_query($conn, "UPDATE tb_distribusi SET 
                                    status_pengiriman = '1', 
                                    gps_awal = '$gps',
                                    jam_berangkat = NOW() 
                                  WHERE id_distribusi='$id'");
} elseif ($status == '2') {
    if ($gps === '') {
        echo json_encode(['status' => 'error', 'message' => 'Lokasi GPS tiba tidak ditemukan!']);
        exit;
    }
    $query = mysqli_query($conn, "UPDATE tb_distribusi SET 
                                    status_pengiriman = '2',
                                    jam_tiba = NOW() 
                                  WHERE id_distribusi='$id'");
} elseif ($status == '0') {
    $query = mysqli_query($conn, "UPDATE tb_distribusi SET 
                                    status_pengiriman = '0', 
                                    gps_awal = NULL,
                                    jam_berangkat = NULL 
                                  WHERE id_distribusi='$id'");
}

if ($query) {
    echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui.']);
} else {
    if(mysqli_error($conn)) {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid!']);
    }
}