<?php
ob_clean();
include '../config.php'; // Sesuaikan path
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        // --- AKSI: Mengambil semua bahan makanan ---
        $kategori_filter = $_GET['kategori'] ?? null;
        $bahan = [];
        $sql = "SELECT id_bahan, nama_bahan, kategori FROM tb_bahan_makanan";
        if ($kategori_filter) {
            $sql .= " WHERE kategori = ?";
        }
        $sql .= " ORDER BY kategori, nama_bahan ASC";

        $stmt = $conn->prepare($sql);
        if ($kategori_filter) {
            $stmt->bind_param("s", $kategori_filter);
        }
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $nama, $kat);
        while ($stmt->fetch()) {
            $bahan[] = ['id_bahan' => $id, 'nama_bahan' => $nama, 'kategori' => $kat];
        }
        $stmt->close();
        echo json_encode(['status' => 'success', 'data' => $bahan]);
    } elseif ($method === 'POST') {
        // --- AKSI: Menambah bahan makanan baru ---
        $nama_bahan = $_POST['nama_bahan'] ?? null;
        $kategori = $_POST['kategori'] ?? null;

        if (empty($nama_bahan) || empty($kategori)) {
            throw new Exception("Nama bahan dan kategori wajib diisi.");
        }

        // Cek duplikasi (opsional tapi bagus)
        $stmt_cek = $conn->prepare("SELECT id_bahan FROM tb_bahan_makanan WHERE nama_bahan = ? AND kategori = ?");
        $stmt_cek->bind_param("ss", $nama_bahan, $kategori);
        $stmt_cek->execute();
        $stmt_cek->store_result();
        if ($stmt_cek->num_rows > 0) {
            throw new Exception("Bahan makanan '$nama_bahan' dengan kategori '$kategori' sudah ada.");
        }
        $stmt_cek->close();

        // Insert data baru
        $stmt_insert = $conn->prepare("INSERT INTO tb_bahan_makanan (nama_bahan, kategori) VALUES (?, ?)");
        $stmt_insert->bind_param("ss", $nama_bahan, $kategori);

        if ($stmt_insert->execute()) {
            $new_id = $stmt_insert->insert_id; // Ambil ID bahan baru
            echo json_encode([
                'status' => 'success',
                'message' => 'Bahan makanan berhasil ditambahkan.',
                'new_data' => ['id_bahan' => $new_id, 'nama_bahan' => $nama_bahan, 'kategori' => $kategori] // Kirim data baru
            ]);
        } else {
            throw new Exception("Gagal menyimpan bahan makanan: " . $stmt_insert->error);
        }
        $stmt_insert->close();
    } else {
        throw new Exception("Metode request tidak didukung.");
    }
} catch (Exception $e) {
    header('Content-Type: application/json', true, 400); // Bad Request atau 500 Internal Server Error
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    if (isset($conn)) $conn->close();
}
exit;
