<?php
// 1. Bersihkan output buffer untuk menghapus spasi/error PHP
ob_clean();
session_name('SIMGiziSekolah');
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_sekolah') {
    header('Location: ../../../index.php');
    exit();
}
include '../config.php';

// 2. Logika utama dimasukkan ke dalam try-catch untuk menangani error
try {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_sekolah') {
        throw new Exception('Akses ditolak');
    }

    $id_sekolah = $_SESSION['id_asal_sekolah']; 
    $view_type = $_GET['view'] ?? 'monthly';

    $labels = [];
    $habis_data = [];
    $kembali_data = [];
    $map = [];

    if ($view_type === 'monthly') {
        // --- LOGIKA BULANAN ---
        $start = new DateTime('first day of January this year');
        $startDate = $start->format('Y-m-d');
        $sql = "SELECT DATE_FORMAT(tanggal, '%Y-%m') AS ym, SUM(jumlah_habis) AS total_habis, SUM(jumlah_kembali) AS total_kembali 
                FROM tb_distribusi WHERE status_konfirmasi = '1' AND id_sekolah_tujuan = ? AND tanggal >= ? GROUP BY ym";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $id_sekolah, $startDate);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($ym, $total_habis, $total_kembali);
        while ($stmt->fetch()) {
            $map[$ym] = ['habis' => (int)$total_habis, 'kembali' => (int)$total_kembali];
        }

        $current = new DateTime('first day of January this year');
        $end_of_loop = new DateTime('today');

        while ($current <= $end_of_loop) {
            $ym = $current->format('Y-m');
            $labels[] = $current->format('M Y');
            $habis_data[] = $map[$ym]['habis'] ?? 0;
            $kembali_data[] = $map[$ym]['kembali'] ?? 0;

            if ($current->format('Y-m') == $end_of_loop->format('Y-m')) {
                break;
            }
            $current->modify('+1 month');
        }
    } else {
        // --- LOGIKA HARIAN ---
        $start = new DateTime('monday this week');
        $end = new DateTime('sunday this week');
        $startDate = $start->format('Y-m-d');
        $endDate = $end->format('Y-m-d');
        $sql = "SELECT tanggal, SUM(jumlah_habis) AS total_habis, SUM(jumlah_kembali) AS total_kembali 
                FROM tb_distribusi WHERE status_konfirmasi = '1' AND id_sekolah_tujuan = ? AND tanggal BETWEEN ? AND ? GROUP BY tanggal";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $id_sekolah, $startDate, $endDate);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($tanggal, $total_habis, $total_kembali);
        while ($stmt->fetch()) {
            $map[$tanggal] = ['habis' => (int)$total_habis, 'kembali' => (int)$total_kembali];
        }

        $current = new DateTime('monday this week');
        for ($i = 0; $i < 7; $i++) {
            $ymd = $current->format('Y-m-d');
            $labels[] = $current->format('D, d M');
            $habis_data[] = $map[$ymd]['habis'] ?? 0;
            $kembali_data[] = $map[$ymd]['kembali'] ?? 0;
            $current->modify('+1 day');
        }
    }

    $stmt->close();
    $conn->close();

    // 3. Set header TEPAT SEBELUM echo
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success', // Tambahkan status sukses
        'labels' => $labels,
        'series' => [
            ['name' => 'Jumlah Habis', 'data' => $habis_data],
            ['name' => 'Jumlah Kembali', 'data' => $kembali_data]
        ]
    ]);
    exit; // Pastikan tidak ada output lain

} catch (Exception $e) {
    // Jika ada error, kirim sebagai JSON
    header('Content-Type: application/json', true, 500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
