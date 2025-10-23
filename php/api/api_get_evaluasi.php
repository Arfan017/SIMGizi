<?php
// 1. Bersihkan output buffer
ob_clean();

session_start();
include '../config.php';

// 2. Logika utama dalam try-catch
try {
    // Anda bisa menambahkan filter role jika perlu
    // if (!isset($_SESSION['role'])) { ... }

    // Query BARU: JOIN dengan tb_konfirmasi_distribusi untuk ambil gambar
    $sql = "SELECT 
                ev.id_evaluasi, 
                ev.id_distribusi, 
                ev.catatan, 
                ev.status_distribusi, 
                dist.tanggal, 
                sek.nama_sekolah AS sekolah_tujuan,
                conf.gambar -- Ambil kolom gambar
            FROM tb_evaluasi ev
            JOIN tb_distribusi dist ON ev.id_distribusi = dist.id_distribusi 
            JOIN tb_sekolah sek ON dist.id_sekolah_tujuan = sek.id_sekolah
            LEFT JOIN tb_konfirmasi_distribusi conf ON ev.id_distribusi = conf.id_distribusi -- LEFT JOIN agar data evaluasi tetap muncul meski konfirmasi belum ada
            ORDER BY ev.id_evaluasi DESC";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Gagal menyiapkan query: " . $conn->error);
    }

    $stmt->execute();

    // Pola kompatibel tanpa get_result()
    $stmt->store_result();

    // Sesuaikan bind_result dengan kolom yang di SELECT (tambahkan $gambar)
    $stmt->bind_result(
        $id_evaluasi,
        $id_distribusi,
        $catatan,
        $status_distribusi,
        $tanggal,
        $nama_sekolah,
        $gambar // Ikat hasil gambar
    );

    $evaluasi_data = [];
    while ($stmt->fetch()) {
        $evaluasi_data[] = [
            'nama_sekolah'      => $nama_sekolah,
            'tanggal'           => $tanggal,
            'status_distribusi' => $status_distribusi, // Masih angka (1, 2, atau 3)
            'catatan'           => $catatan,
            'gambar'            => $gambar ?? null // Kirim nama file gambar, atau null jika tidak ada
        ];
    }

    $stmt->close();
    $conn->close();

    // 3. Set header TEPAT SEBELUM echo
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $evaluasi_data]);
    exit;
} catch (Exception $e) {
    // Jika ada error, kirim sebagai JSON
    header('Content-Type: application/json', true, 500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
