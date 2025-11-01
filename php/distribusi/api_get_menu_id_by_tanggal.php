<?php
ob_clean();
session_name('SIMGiziDistribusi');
session_start();
include '../config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_distribusi') {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit;
}

$tanggal = $_GET['tanggal'] ?? null;
if (empty($tanggal)) {
    echo json_encode(['status' => 'error', 'message' => 'Tanggal tidak boleh kosong.']);
    exit;
}

$stmt = $conn->prepare("SELECT id_menu FROM tb_menu_harian WHERE tanggal = ?");
$stmt->bind_param("s", $tanggal);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id_menu);
    $stmt->fetch();
    echo json_encode(['status' => 'success', 'id_menu' => $id_menu]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Menu harian untuk tanggal ini belum diinput.']);
}
$stmt->close();
$conn->close();
