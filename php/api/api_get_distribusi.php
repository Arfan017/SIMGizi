<?php
ob_clean(); // Bersihkan output buffer
include '../config.php'; // Sesuaikan path

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$data_distribusi = [];

try {
    // Query utama diubah:
    // - Hapus nama_barang
    // - JOIN ke tb_menu_harian
    // - JOIN berkali-kali ke tb_bahan_makanan untuk detail menu
    $sql = "SELECT 
                d.id_distribusi,
                d.tanggal,
                d.jumlah,
                d.jam,
                d.status_pengiriman,
                d.lokasi_gps,
                d.foto,
                d.lokasi_terkini, -- Ambil juga lokasi terkini
                d.jam_berangkat, -- Ambil juga jam berangkat
                d.jam_tiba,      -- Ambil juga jam tiba
                d.gps_awal,      -- Ambil juga gps awal
                u.nama AS nama_petugas,
                s.nama_sekolah AS sekolah_tujuan,
                kh.nama_bahan AS menu_kh,
                p1.nama_bahan AS menu_protein1,
                p2.nama_bahan AS menu_protein2,
                syr.nama_bahan AS menu_sayur,
                bh.nama_bahan AS menu_buah,
                mh.tambahan AS menu_tambahan
            FROM tb_distribusi d
            JOIN tb_users u ON d.id_petugas_distribusi = u.id_users 
            JOIN tb_sekolah s ON d.id_sekolah_tujuan = s.id_sekolah 
            -- Join baru untuk mengambil detail menu
            LEFT JOIN tb_menu_harian mh ON d.id_menu = mh.id_menu
            LEFT JOIN tb_bahan_makanan kh ON mh.id_bahan_kh = kh.id_bahan
            LEFT JOIN tb_bahan_makanan p1 ON mh.id_bahan_protein1 = p1.id_bahan
            LEFT JOIN tb_bahan_makanan p2 ON mh.id_bahan_protein2 = p2.id_bahan
            LEFT JOIN tb_bahan_makanan syr ON mh.id_bahan_sayur = syr.id_bahan
            LEFT JOIN tb_bahan_makanan bh ON mh.id_bahan_buah = bh.id_bahan
            ORDER BY d.tanggal DESC, d.id_distribusi DESC";

    // Gunakan prepared statement agar lebih aman dan kompatibel
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Prepare statement gagal: " . $conn->error);
    }

    $stmt->execute();

    // Dapatkan hasil (bisa pakai get_result jika sudah aktif, atau bind_result jika belum)
    // Kita gunakan get_result karena lebih mudah untuk banyak kolom
    $result = $stmt->get_result();

    if ($result === false) {
        throw new Exception("Gagal mendapatkan hasil: " . $stmt->error);
    }

    while ($row = $result->fetch_assoc()) {
        $data_distribusi[] = $row;
    }

    $stmt->close();
    $conn->close();

    echo json_encode($data_distribusi);
    exit;
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    if (isset($stmt) && $stmt instanceof mysqli_stmt) $stmt->close();
    if (isset($conn) && $conn instanceof mysqli) $conn->close();
    exit;
}
