<?php
header('Content-Type: application/json');
include '../config.php';

$id_distribusi = $_POST['id_distribusi'] ?? 0;
$lokasi_terkini = $_POST['lokasi'] ?? '';

if (empty($id_distribusi) || empty($lokasi_terkini)) {
    echo json_encode(['status' => 'error', 'message' => 'Parameter tidak lengkap.']);
    exit;
}

$stmt = $conn->prepare("UPDATE tb_distribusi SET lokasi_terkini = ? WHERE id_distribusi = ?");
$stmt->bind_param("si", $lokasi_terkini, $id_distribusi);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Lokasi diperbarui.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui lokasi.']);
}

$stmt->close();
$conn->close();
