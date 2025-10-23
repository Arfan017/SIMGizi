<?php
// 1. Bersihkan output & mulai sesi
ob_clean();
session_name('SIMGiziSekolah'); // Pastikan nama sesi benar
session_start();
include '../config.php';

// 2. Fungsi respons & validasi
function send_json_response($status, $dataOrMessage)
{
    header('Content-Type: application/json');
    if ($status === 'error') {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => $dataOrMessage]);
    } else {
        echo json_encode(['status' => 'success', 'data' => $dataOrMessage]);
    }
    exit;
}

try {
    // Validasi login
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_sekolah') {
        throw new Exception('Akses ditolak.');
    }
    $id_sekolah = $_SESSION['id_asal_sekolah'];

    // Tentukan Rentang Tanggal
    $view_type = $_GET['view'] ?? 'monthly';
    if ($view_type === 'daily') {
        $start_date = date('Y-m-d', strtotime('monday this week'));
        $end_date = date('Y-m-d', strtotime('sunday this week'));
    } else {
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');
    }

    // 3. Query: Tambahkan bm.kategori
    $sql = "
        SELECT bm.nama_bahan, bm.kategori, COUNT(bm.id_bahan) AS frekuensi -- Tambah kategori
        FROM (
            SELECT id_bahan_kh AS id_bahan FROM tb_menu_harian WHERE tanggal BETWEEN ? AND ? AND id_bahan_kh IS NOT NULL
            UNION ALL
            SELECT id_bahan_protein1 AS id_bahan FROM tb_menu_harian WHERE tanggal BETWEEN ? AND ? AND id_bahan_protein1 IS NOT NULL
            UNION ALL
            SELECT id_bahan_protein2 AS id_bahan FROM tb_menu_harian WHERE tanggal BETWEEN ? AND ? AND id_bahan_protein2 IS NOT NULL
            UNION ALL
            SELECT id_bahan_sayur AS id_bahan FROM tb_menu_harian WHERE tanggal BETWEEN ? AND ? AND id_bahan_sayur IS NOT NULL
            UNION ALL
            SELECT id_bahan_buah AS id_bahan FROM tb_menu_harian WHERE tanggal BETWEEN ? AND ? AND id_bahan_buah IS NOT NULL
        ) AS menu_items
        JOIN tb_bahan_makanan bm ON menu_items.id_bahan = bm.id_bahan
        GROUP BY bm.nama_bahan, bm.kategori -- Group juga berdasarkan kategori
        ORDER BY frekuensi DESC, bm.nama_bahan ASC
        LIMIT 20
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) throw new Exception("Prepare failed: " . $conn->error);

    $stmt->bind_param(
        "ssssssssss",
        $start_date,
        $end_date,
        $start_date,
        $end_date,
        $start_date,
        $end_date,
        $start_date,
        $end_date,
        $start_date,
        $end_date
    );

    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($nama_bahan, $kategori, $frekuensi); // Tambah $kategori

    // Ubah format data agar menyertakan kategori per item
    $chart_series_data = [];
    while ($stmt->fetch()) {
        $chart_series_data[] = [
            'x' => $nama_bahan,   // Label untuk sumbu X (nama item)
            'y' => $frekuensi,    // Nilai untuk sumbu Y (jumlah)
            'kategori' => $kategori // Informasi tambahan
        ];
    }
    $stmt->close();
    $conn->close();

    // Format data untuk ApexCharts (hanya satu series dengan data objek)
    $chart_data = [
        // labels tidak dikirim lagi, karena ada di dalam data series
        'series' => [['name' => 'Jumlah Penyajian', 'data' => $chart_series_data]]
    ];

    send_json_response('success', $chart_data);
} catch (Exception $e) {
    send_json_response('error', $e->getMessage());
    // ... (penutupan koneksi jika perlu)
}
