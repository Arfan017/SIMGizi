<?php
include '../config.php';
header('Content-Type: application/json');

$id_petugas = $_POST['id_petugas_distribusi'];
$nama_barang = $_POST['nama_barang'];
$id_sekolah = $_POST['sekolah'];
$jumlah = (int)$_POST['jumlah'];
$tanggal = $_POST['tanggal'];
$jam = $_POST['jam'];
$lokasi = $_POST['lokasi'];

mysqli_begin_transaction($conn);

try {
    $stmt_cek = $conn->prepare("SELECT jumlah_sisa FROM tb_stok_harian WHERE tanggal = ? FOR UPDATE");
    $stmt_cek->bind_param("s", $tanggal);
    $stmt_cek->execute();
    $result_stok = $stmt_cek->get_result();

    if ($result_stok->num_rows === 0) {
        throw new Exception("Stok untuk tanggal $tanggal belum diinput.");
    }

    $stok = $result_stok->fetch_assoc();
    $stok_sisa = (int)$stok['jumlah_sisa'];

    if ($jumlah > $stok_sisa) {
        throw new Exception("Jumlah distribusi ($jumlah) melebihi sisa stok ($stok_sisa).");
    }

    $stmt_update = $conn->prepare("UPDATE tb_stok_harian SET jumlah_sisa = jumlah_sisa - ? WHERE tanggal = ?");
    $stmt_update->bind_param("is", $jumlah, $tanggal);
    if (!$stmt_update->execute()) {
        throw new Exception("Gagal mengupdate stok harian.");
    }
    $stmt_update->close();

    $foto_name = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_name = 'distribusi_' . time() . '.' . $ext;
        $target = '../../uploads/' . $foto_name;
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal upload foto!'
            ]);
            exit;
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Foto wajib diupload!'
        ]);
        exit;
    }

    $stmt_insert = $conn->prepare("INSERT INTO tb_distribusi (id_petugas_distribusi, tanggal, jam, nama_barang, jumlah, id_sekolah_tujuan, foto, lokasi_gps) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_insert->bind_param("isssisss", $id_petugas, $tanggal, $jam, $nama_barang, $jumlah, $id_sekolah, $foto_name, $lokasi);
    if (!$stmt_insert->execute()) {
        throw new Exception("Gagal menyimpan data distribusi.");
    }
    $stmt_insert->close();

    mysqli_commit($conn);
    echo json_encode(['status' => 'success', 'message' => 'Data distribusi berhasil disimpan.']);

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();