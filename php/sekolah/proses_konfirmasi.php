<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_distribusi = $_POST['id_distribusi'];
    $tanggal = date('Y-m-d');
    $jam = date('H:i:s');
    $status = $_POST['status'] === '1' ? 'Terima' : 'Tolak';
    $jumlah_diterima = $_POST['jumlah_diterima'];
    $jumlah_habis = $_POST['jumlah_habis'];
    $jumlah_kembali = $_POST['jumlah_kembali'];
    $catatan = $_POST['catatan'];

    // --- Upload Foto ---
    $foto_nama = null;
    if (isset($_FILES['foto_konfirmasi']) && $_FILES['foto_konfirmasi']['error'] === 0) {
        $target_dir = "../../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $ext = pathinfo($_FILES['foto_konfirmasi']['name'], PATHINFO_EXTENSION);
        $foto_nama = "konfirmasi_" . time() . "." . $ext;
        $target_file = $target_dir . $foto_nama;

        if (!move_uploaded_file($_FILES['foto_konfirmasi']['tmp_name'], $target_file)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal upload foto!'
            ]);
            exit;
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Foto konfirmasi wajib diupload!'
        ]);
        exit;
    }

    $query = "INSERT INTO tb_konfirmasi_distribusi (id_distribusi, tanggal, jam, jumlah_diterima, jumlah_habis, jumlah_kembali, status, catatan, gambar) VALUES ('$id_distribusi', '$tanggal', '$jam', '$jumlah_diterima', '$jumlah_habis', '$jumlah_kembali', '$status', '$catatan', '$foto_nama')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal konfirmasi: ' . mysqli_error($conn)
        ]);
    }
}

function sendNotification($conn, $id_distribusi, $tanggal, $jam, $jumlah_diterima, $catatan)
{
    include '../notif_wa.php';

    $q_data = "SELECT tb_sekolah.nama_sekolah AS sekolah_tujuan, tb_users.nama AS nama_petugas_distribusi, tb_users.no_hp AS no_wa FROM tb_distribusi 
            JOIN tb_sekolah ON tb_sekolah.id_sekolah = tb_distribusi.id_sekolah_tujuan 
            JOIN tb_users ON tb_users.id_users = tb_distribusi.id_petugas_distribusi 
            WHERE tb_distribusi.id_distribusi = '$id_distribusi'";

    $r_data = mysqli_query($conn, $q_data);

    if (mysqli_num_rows($r_data) > 0) {
        $row = mysqli_fetch_assoc($r_data);
        $sekolah = $row['sekolah_tujuan'];
        $nama_petugas = $row['nama_petugas_distribusi'];
        $target = $row['no_wa'];

        kirimNotifikasiKonfirmasi($id_distribusi, $nama_petugas, $tanggal, $jam, $sekolah, $jumlah_diterima, $catatan, $target);
    } else {
        return null;
    }
}
