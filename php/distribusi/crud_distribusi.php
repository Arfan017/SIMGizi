<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// BARU
include '../config.php';
header('Content-Type: application/json');

// Ambil data dari form
$id_petugas = $_POST['id_petugas_distribusi'];
$nama_barang = $_POST['nama_barang'];
$id_sekolah = $_POST['sekolah'];
$jumlah = (int)$_POST['jumlah'];
$tanggal = $_POST['tanggal'];
$jam = $_POST['jam'];
$lokasi = $_POST['lokasi'];

// Mulai Transaksi Database
mysqli_begin_transaction($conn);

try {
    // 1. Cek ketersediaan stok
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

    // 2. Kurangi stok
    $stmt_update = $conn->prepare("UPDATE tb_stok_harian SET jumlah_sisa = jumlah_sisa - ? WHERE tanggal = ?");
    $stmt_update->bind_param("is", $jumlah, $tanggal);
    if (!$stmt_update->execute()) {
        throw new Exception("Gagal mengupdate stok harian.");
    }
    $stmt_update->close();

    // 3. Simpan data distribusi (termasuk upload foto)
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
    $stmt_insert->bind_param("isssisss", $id_petugas, $tanggal, $jam, $nama_barang, $jumlah, $id_sekolah, $nama_foto, $lokasi);
    if (!$stmt_insert->execute()) {
        throw new Exception("Gagal menyimpan data distribusi.");
    }
    $stmt_insert->close();

    // Jika semua berhasil, commit transaksi
    mysqli_commit($conn);
    echo json_encode(['status' => 'success', 'message' => 'Data distribusi berhasil disimpan.']);

} catch (Exception $e) {
    // Jika ada error, rollback semua perubahan
    mysqli_rollback($conn);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();

// LAMA
// // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// $sekolah = $_POST['sekolah'] ?? '';
// $jumlah = $_POST['jumlah'] ?? '';
// $tanggal = $_POST['tanggal'] ?? '';
// $jam = $_POST['jam'] ?? '';
// $lokasi = $_POST['lokasi'] ?? '';
// $catatan = $_POST['catatan'] ?? 'catatan';
// $nama_barang = $_POST['nama_barang'] ?? '';
// $id_petugas_distribusi = $_POST['id_petugas_distribusi'] ?? '';

// if (!$sekolah || !$jumlah || !$tanggal || !$jam || !$lokasi || !$catatan || !$nama_barang || !$id_petugas_distribusi) {
//     echo json_encode([
//         'status' => 'error',
//         'message' => 'Semua field wajib diisi!'
//     ]);
//     exit;
// }

// // Proses upload foto
// $foto_name = '';
// if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
//     $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
//     $foto_name = 'distribusi_' . time() . '.' . $ext;
//     $target = '../../uploads/' . $foto_name;
//     if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
//         echo json_encode([
//             'status' => 'error',
//             'message' => 'Gagal upload foto!'
//         ]);
//         exit;
//     }
// } else {
//     echo json_encode([
//         'status' => 'error',
//         'message' => 'Foto wajib diupload!'
//     ]);
//     exit;
// }

// // Simpan ke database
// $query = mysqli_query($conn, "INSERT INTO tb_distribusi (id_petugas_distribusi, tanggal, jam,  nama_barang, jumlah, id_sekolah_tujuan, foto, lokasi_gps) VALUES
//                                                         ('$id_petugas_distribusi', '$tanggal', '$jam', '$nama_barang', '$jumlah', '$sekolah',  '$foto_name', '$lokasi')");
// if ($query) {
//     // Ambil data distribusi terakhir yang baru diinput
//     $id_distribusi = mysqli_insert_id($conn);
//     $get = mysqli_query($conn, "SELECT d.*, s.nama_sekolah 
//         FROM tb_distribusi d 
//         JOIN tb_sekolah s ON d.id_sekolah_tujuan = s.id_sekolah 
//         WHERE d.id_distribusi = '$id_distribusi' LIMIT 1");
//     $data = mysqli_fetch_assoc($get);

//     echo json_encode([
//         'status' => 'success',
//         'data' => [
//             'id' => $data['id_distribusi'],
//             'sekolah' => $data['nama_sekolah'],
//             'jumlah' => $data['jumlah'],
//             'tanggal' => $data['tanggal'],
//             'jam' => $data['jam'],
//             'lokasi' => $data['lokasi_gps'],
//             'foto' => $data['foto'],
//             'nama_barang' => $data['nama_barang']
//         ]
//     ]);
// } else {
//     echo json_encode([
//         'status' => 'error',
//         'message' => 'Gagal menyimpan data: ' . mysqli_error($conn)
//     ]);
// }