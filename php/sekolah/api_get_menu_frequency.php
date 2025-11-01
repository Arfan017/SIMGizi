<?php
ob_clean();
session_name('SIMGiziSekolah'); 
session_start();
include '../config.php';

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
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_sekolah') { // Sesuaikan dengan nama role Anda
        throw new Exception('Akses ditolak.');
    }
    $id_sekolah = $_SESSION['id_asal_sekolah'];

    $view_type = $_GET['view'] ?? 'monthly';
    if ($view_type === 'daily') {
        $start_date = date('Y-m-d', strtotime('monday this week'));
        $end_date = date('Y-m-d', strtotime('sunday this week'));
    } else {
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');
    }

    $sql = "
        SELECT bm.nama_bahan, bm.kategori, COUNT(bm.id_bahan) AS frekuensi
        FROM (
            SELECT mh.id_bahan_kh AS id_bahan 
            FROM tb_menu_harian mh
            JOIN tb_distribusi d ON mh.id_menu = d.id_menu
            WHERE mh.tanggal BETWEEN ? AND ? 
              AND d.id_sekolah_tujuan = ? 
              AND d.status_konfirmasi = '1' 
              AND mh.id_bahan_kh IS NOT NULL

            UNION ALL

            SELECT mh.id_bahan_protein1 AS id_bahan 
            FROM tb_menu_harian mh
            JOIN tb_distribusi d ON mh.id_menu = d.id_menu
            WHERE mh.tanggal BETWEEN ? AND ? 
              AND d.id_sekolah_tujuan = ? 
              AND d.status_konfirmasi = '1' 
              AND mh.id_bahan_protein1 IS NOT NULL

            UNION ALL

            SELECT mh.id_bahan_protein2 AS id_bahan 
            FROM tb_menu_harian mh
            JOIN tb_distribusi d ON mh.id_menu = d.id_menu
            WHERE mh.tanggal BETWEEN ? AND ? 
              AND d.id_sekolah_tujuan = ? 
              AND d.status_konfirmasi = '1' 
              AND mh.id_bahan_protein2 IS NOT NULL

            UNION ALL

            SELECT mh.id_bahan_sayur AS id_bahan 
            FROM tb_menu_harian mh
            JOIN tb_distribusi d ON mh.id_menu = d.id_menu
            WHERE mh.tanggal BETWEEN ? AND ? 
              AND d.id_sekolah_tujuan = ? 
              AND d.status_konfirmasi = '1' 
              AND mh.id_bahan_sayur IS NOT NULL

            UNION ALL

            SELECT mh.id_bahan_buah AS id_bahan 
            FROM tb_menu_harian mh
            JOIN tb_distribusi d ON mh.id_menu = d.id_menu
            WHERE mh.tanggal BETWEEN ? AND ? 
              AND d.id_sekolah_tujuan = ? 
              AND d.status_konfirmasi = '1' 
              AND mh.id_bahan_buah IS NOT NULL
        ) AS menu_items
        JOIN tb_bahan_makanan bm ON menu_items.id_bahan = bm.id_bahan
        GROUP BY bm.nama_bahan, bm.kategori
        ORDER BY frekuensi DESC, bm.nama_bahan ASC
        LIMIT 20
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) throw new Exception("Prepare failed: " . $conn->error);

    $stmt->bind_param(
        "ssississississi",
        $start_date,
        $end_date,
        $id_sekolah,
        $start_date,
        $end_date,
        $id_sekolah,
        $start_date,
        $end_date,
        $id_sekolah,
        $start_date,
        $end_date,
        $id_sekolah,
        $start_date,
        $end_date,
        $id_sekolah
    );

    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($nama_bahan, $kategori, $frekuensi);

    $chart_series_data = [];
    while ($stmt->fetch()) {
        $chart_series_data[] = [
            'x' => $nama_bahan, 
            'y' => $frekuensi,
            'kategori' => $kategori 
        ]; 
    }
    $stmt->close();
    $conn->close();

    $chart_data = [
        'series' => [['name' => 'Jumlah Penyajian', 'data' => $chart_series_data]]
    ];

    send_json_response('success', $chart_data);
} catch (Exception $e) {
    send_json_response('error', $e->getMessage());
    if (isset($stmt) && $stmt instanceof mysqli_stmt) $stmt->close();
    if (isset($conn) && $conn instanceof mysqli) $conn->close();
}
