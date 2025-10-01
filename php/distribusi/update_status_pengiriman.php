<?php
include '../config.php';
header('Content-Type: application/json');

// Ambil data dari POST request
$id = $_POST['id_distribusi'] ?? '';
$status = $_POST['status'] ?? '';
$gps = $_POST['gps'] ?? ''; // GPS akan dikirim dari JavaScript

// Validasi data dasar
if (!$id || $status === '') {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap!']);
    exit;
}

$query = null;

// Logika berdasarkan status yang dikirim
if ($status == '1') {
    // Aksi untuk "KIRIM"
    // Membutuhkan GPS awal
    if ($gps === '') {
        echo json_encode(['status' => 'error', 'message' => 'Lokasi GPS awal tidak ditemukan!']);
        exit;
    }
    // Waktu berangkat diambil dari waktu server saat ini (lebih akurat)
    $query = mysqli_query($conn, "UPDATE tb_distribusi SET 
                                    status_pengiriman = '1', 
                                    gps_awal = '$gps',
                                    jam_berangkat = NOW() 
                                  WHERE id_distribusi='$id'");
} elseif ($status == '2') {
    // Aksi untuk "SAMPAI"
    // Membutuhkan GPS tiba
    if ($gps === '') {
        echo json_encode(['status' => 'error', 'message' => 'Lokasi GPS tiba tidak ditemukan!']);
        exit;
    }
    // Waktu tiba diambil dari waktu server saat ini
    $query = mysqli_query($conn, "UPDATE tb_distribusi SET 
                                    status_pengiriman = '2',
                                    jam_tiba = NOW() 
                                  WHERE id_distribusi='$id'");
} elseif ($status == '0') {
    // Aksi untuk "BATAL KIRIM"
    // Mengosongkan kembali data keberangkatan
    $query = mysqli_query($conn, "UPDATE tb_distribusi SET 
                                    status_pengiriman = '0', 
                                    gps_awal = NULL,
                                    jam_berangkat = NULL 
                                  WHERE id_distribusi='$id'");
}

// Eksekusi query dan kirim response
if ($query) {
    echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui.']);
} else {
    // Jika $query null (status tidak valid) atau query gagal
    if(mysqli_error($conn)) {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid!']);
    }
}

// LAMA
// include '../config.php';
// header('Content-Type: application/json');

// $id = $_POST['id_distribusi'] ?? '';
// $status = $_POST['status'] ?? '';

// if (!$id || $status === '') {
//     echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap!']);
//     exit;
// }




// $query = mysqli_query($conn, "UPDATE tb_distribusi SET 
//                                 status_pengiriman = '$status', 
//                                 gps_awal = '$ ',
//                                 jam_berangkat = '$ ' WHERE id_distribusi='$id'");
// if ($query) {
//     echo json_encode(['status' => 'success']);
// } else {
//     echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
// }
