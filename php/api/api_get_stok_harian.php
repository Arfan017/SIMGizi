<?php
// 1. Bersihkan output buffer untuk mencegah error JSON
ob_clean();
header('Content-Type: application/json');
include '../config.php'; // Sesuaikan path

$tanggal_hari_ini = date("Y-m-d");
$sisa_stok = 0; // Nilai default
$status = 'not_found'; // Status default

try {
    $stmt = $conn->prepare("SELECT jumlah_sisa FROM tb_stok_harian WHERE tanggal = ?");
    if ($stmt === false) {
        throw new Exception("Prepare statement gagal: " . $conn->error);
    }

    $stmt->bind_param("s", $tanggal_hari_ini);
    $stmt->execute();

    // --- PERBAIKAN get_result() DIMULAI DI SINI ---

    // 1. Simpan hasil query ke memori
    $stmt->store_result();

    // 2. Cek apakah ada baris data yang ditemukan
    if ($stmt->num_rows > 0) {
        // 3. Ikat (bind) hasil dari kolom 'jumlah_sisa' ke sebuah variabel PHP
        $stmt->bind_result($jumlah_sisa_dari_db);

        // 4. Ambil (fetch) data dari baris tersebut
        $stmt->fetch();

        // 5. Sekarang, variabel $jumlah_sisa_dari_db sudah berisi nilainya
        $sisa_stok = (int)$jumlah_sisa_dari_db;
        $status = 'success';
    }
    // --- PERBAIKAN SELESAI ---

    $stmt->close();
    $conn->close();

    // Kirim respons sukses
    echo json_encode([
        'status' => $status,
        'sisa_stok' => $sisa_stok,
        'tanggal' => $tanggal_hari_ini
    ]);
    exit;
} catch (Exception $e) {
    // Tangani jika ada error (misal query gagal)
    http_response_code(500); // Kirim status error server
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    // Pastikan koneksi ditutup jika terjadi error
    if (isset($stmt) && $stmt instanceof mysqli_stmt) $stmt->close();
    if (isset($conn) && $conn instanceof mysqli) $conn->close();
    exit;
}
