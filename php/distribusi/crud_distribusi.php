<?php
include '../config.php';

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$sekolah = $_POST['sekolah'] ?? '';
$jumlah = $_POST['jumlah'] ?? '';
$tanggal = $_POST['tanggal'] ?? '';
$jam = $_POST['jam'] ?? '';
$lokasi = $_POST['lokasi'] ?? '';
$catatan = $_POST['catatan'] ?? 'catatan';
$nama_barang = $_POST['nama_barang'] ?? '';
$id_petugas_distribusi = $_POST['id_petugas_distribusi'] ?? '';



if (!$sekolah || !$jumlah || !$tanggal || !$jam || !$lokasi || !$catatan || !$nama_barang || !$id_petugas_distribusi) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Semua field wajib diisi!'
    ]);
    exit;
}

// Proses upload foto
$foto_name = '';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto_name = 'distribusi_' . time() . '.' . $ext;
    $target = '../../uploads/' . $foto_name;
    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal upload foto!'
        ]);
        exit;
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Foto wajib diupload!'
    ]);
    exit;
}

file_put_contents('debug_distribusi.txt', print_r($_POST, true) . PHP_EOL, FILE_APPEND);
// Simpan ke database
$query = mysqli_query($conn, "INSERT INTO tb_distribusi (id_petugas_distribusi, tanggal, jam,  nama_barang, jumlah, tujuan, foto, lokasi_gps) VALUES
                                                        ('$id_petugas_distribusi', '$tanggal', '$jam', '$nama_barang', '$jumlah', '$sekolah',  '$foto_name', '$lokasi')");
if ($query) {
    echo json_encode([
        'status' => 'success'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menyimpan data: ' . mysqli_error($conn)
    ]);
}
// }