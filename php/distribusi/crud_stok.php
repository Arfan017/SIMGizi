<?php
include '../config.php';
header('Content-Type: application/json');

$tanggal_input = $_POST['tanggal_stok'] ?? '';
$jumlah_total_input = $_POST['jumlah_total'] ?? 0;

if (empty($tanggal_input) || $jumlah_total_input <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap atau tidak valid.']);
    exit;
}

// 1. Sanitasi Input (WAJIB DILAKUKAN!)
// Membersihkan input string untuk mencegah SQL injection
$tanggal = mysqli_real_escape_string($conn, $tanggal_input);
// Memastikan input jumlah adalah angka (integer)
$jumlah_total = (int)$jumlah_total_input;

// 2. Buat String SQL secara manual
// Variabel digabungkan langsung ke dalam string query
$sql = "INSERT INTO tb_stok_harian (tanggal, jumlah_total, jumlah_sisa) 
        VALUES ('$tanggal', $jumlah_total, $jumlah_total) 
        ON DUPLICATE KEY UPDATE jumlah_total = $jumlah_total, jumlah_sisa = $jumlah_total";

// 3. Eksekusi query menggunakan mysqli_query
$query = mysqli_query($conn, $sql);

if ($query) {
    echo json_encode(['status' => 'success']);
} else {
    // Menampilkan pesan error jika query gagal
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . mysqli_error($conn)]);
}

$conn->close();
