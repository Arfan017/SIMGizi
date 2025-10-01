<?php
include '../config.php';

// CREATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    header('Content-Type: application/json');

    $username     = mysqli_real_escape_string($conn, $_POST['username']);
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role         = mysqli_real_escape_string($conn, $_POST['role']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $asal_sekolah = mysqli_real_escape_string($conn, $_POST['id_asal_sekolah'] ?? "-");
    $no_hp        = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $status       = 'Aktif';

    // Cek apakah username sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM tb_users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo json_encode([
            "status" => "exists",
            "message" => "Username sudah terdaftar!"
        ]);
        exit;
    }

    // Insert data
    $query = mysqli_query($conn, "INSERT INTO tb_users (username, password, role, nama, id_asal_sekolah, no_hp, status) 
                                                VALUES ('$username', '$password', '$role', '$nama_lengkap', '$asal_sekolah', '$no_hp', '$status')");
    if ($query) {
        echo json_encode([
            "status" => "success",
            "message" => "Data akun berhasil ditambahkan."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Terjadi kesalahan: " . mysqli_error($conn)
        ]);
    }
    exit;
}

// DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    header('Content-Type: application/json');

    $id_user = intval($_POST['id_users']);
    $query = mysqli_query($conn, "DELETE FROM tb_users WHERE id_users = $id_user");

    if ($query) {
        echo json_encode([
            "status" => "success",
            "message" => "Data akun berhasil dihapus."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Gagal menghapus data: " . mysqli_error($conn)
        ]);
    }
    exit;
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    header('Content-Type: application/json');
    $id_users = intval($_POST['id_users']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $asal_sekolah = mysqli_real_escape_string($conn, $_POST['id_asal_sekolah'] ?? "-");
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $password = $_POST['password'];

    // Cek username unik (kecuali milik sendiri)
    $cek = mysqli_query($conn, "SELECT * FROM tb_users WHERE username='$username' AND id_users != $id_users");
    if (mysqli_num_rows($cek) > 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Username sudah digunakan akun lain!"
        ]);
        exit;
    }

    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $query = mysqli_query($conn, "UPDATE tb_users SET username='$username', password='$password_hash', role='$role', nama='$nama_lengkap', id_asal_sekolah='$asal_sekolah', no_hp='$no_hp', status='$status' WHERE id_users=$id_users");
    } else {
        $query = mysqli_query($conn, "UPDATE tb_users SET username='$username', role='$role', nama='$nama_lengkap', id_asal_sekolah='$asal_sekolah', no_hp='$no_hp', status='$status' WHERE id_users=$id_users");
    }

    if ($query) {
        echo json_encode([
            "status" => "success",
            "message" => "Data akun berhasil diupdate."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Gagal update: " . mysqli_error($conn)
        ]);
    }
    exit;
}
