<?php
ob_clean();
include '../config.php';
header('Content-Type: application/json');

$kategori_list = ['KH', 'Protein', 'Sayur', 'Buah']; // Kategori yang dibutuhkan
$options = [];

try {
    foreach ($kategori_list as $kategori) {
        $stmt = $conn->prepare("SELECT id_bahan, nama_bahan FROM tb_bahan_makanan WHERE kategori = ? ORDER BY nama_bahan ASC");
        $stmt->bind_param("s", $kategori);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id_bahan, $nama_bahan);

        $bahan_per_kategori = [];
        while ($stmt->fetch()) {
            $bahan_per_kategori[] = ['id' => $id_bahan, 'nama' => $nama_bahan];
        }
        $options[$kategori] = $bahan_per_kategori; // Simpan hasil per kategori
        $stmt->close();
    }
    $conn->close();
    echo json_encode(['status' => 'success', 'options' => $options]);
    exit;
} catch (Exception $e) {
    header('Content-Type: application/json', true, 500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    if (isset($stmt) && $stmt instanceof mysqli_stmt) $stmt->close();
    if (isset($conn) && $conn instanceof mysqli) $conn->close();
    exit;
}
