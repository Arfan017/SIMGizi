<?php
ob_clean();
include '../config.php';
header('Content-Type: application/json');

$tanggal = $_POST['tanggal_stok'] ?? null;
$id_kh = $_POST['menu_kh'] ?? null;
$id_p1 = $_POST['menu_protein1'] ?? null;
$id_p2 = $_POST['menu_protein2'] ?? null; 
$id_sayur = $_POST['menu_sayur'] ?? null;
$id_buah = $_POST['menu_buah'] ?? null;  
$tambahan = $_POST['menu_tambahan'] ?? null; 
$jumlah_total = isset($_POST['jumlah_total']) ? (int)$_POST['jumlah_total'] : 0;


if (empty($tanggal) || empty($id_kh) || empty($id_p1) || empty($id_sayur) || $jumlah_total <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Tanggal, KH, Protein 1, Sayur, dan Jumlah Porsi wajib diisi.']);
    exit;
}

mysqli_begin_transaction($conn);

try {
    $stmt_cek_menu = $conn->prepare("SELECT id_menu FROM tb_menu_harian WHERE tanggal = ?");
    $stmt_cek_menu->bind_param("s", $tanggal);
    $stmt_cek_menu->execute();
    $stmt_cek_menu->store_result();
    $menu_exists = $stmt_cek_menu->num_rows > 0;
    $stmt_cek_menu->close();

    if ($menu_exists) {
        $sql_menu = "UPDATE tb_menu_harian SET id_bahan_kh=?, id_bahan_protein1=?, id_bahan_protein2=?, id_bahan_sayur=?, id_bahan_buah=?, tambahan=? WHERE tanggal=?";
        $stmt_menu = $conn->prepare($sql_menu);
        $stmt_menu->bind_param("iiiiiss", $id_kh, $id_p1, $id_p2, $id_sayur, $id_buah, $tambahan, $tanggal);
    } else {
        $sql_menu = "INSERT INTO tb_menu_harian (tanggal, id_bahan_kh, id_bahan_protein1, id_bahan_protein2, id_bahan_sayur, id_bahan_buah, tambahan) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_menu = $conn->prepare($sql_menu);
        $stmt_menu->bind_param("siiiiis", $tanggal, $id_kh, $id_p1, $id_p2, $id_sayur, $id_buah, $tambahan);
    }

    if (!$stmt_menu->execute()) {
        throw new Exception("Gagal menyimpan data menu harian: " . $stmt_menu->error);
    }
    $stmt_menu->close();

    $stmt_cek_stok = $conn->prepare("SELECT id_stok FROM tb_stok_harian WHERE tanggal = ?");
    $stmt_cek_stok->bind_param("s", $tanggal);
    $stmt_cek_stok->execute();
    $stmt_cek_stok->store_result();
    $stok_exists = $stmt_cek_stok->num_rows > 0;
    $stmt_cek_stok->close();

    if ($stok_exists) {
        $sql_stok = "UPDATE tb_stok_harian SET jumlah_total = ?, jumlah_sisa = ? WHERE tanggal = ?";
        $stmt_stok = $conn->prepare($sql_stok);
        $stmt_stok->bind_param("iis", $jumlah_total, $jumlah_total, $tanggal); 
    } else {
        $sql_stok = "INSERT INTO tb_stok_harian (tanggal, jumlah_total, jumlah_sisa) VALUES (?, ?, ?)";
        $stmt_stok = $conn->prepare($sql_stok);
        $stmt_stok->bind_param("sii", $tanggal, $jumlah_total, $jumlah_total); // Set sisa = total saat insert
    }

    if (!$stmt_stok->execute()) {
        throw new Exception("Gagal menyimpan data stok harian: " . $stmt_stok->error);
    }
    $stmt_stok->close();

    mysqli_commit($conn);
    echo json_encode(['status' => 'success', 'message' => 'Menu dan Stok Harian berhasil disimpan.']);
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    if (isset($conn)) $conn->close();
}
exit;
