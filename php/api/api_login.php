<?php
header('Content-Type: application/json');
include '../config.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Username dan password tidak boleh kosong.']);
    exit;
}

$stmt = $conn->prepare("SELECT id_users, nama, role, password FROM tb_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Login berhasil!',
            'data' => [
                'user_id' => $user['id_users'],
                'nama' => $user['nama'],
                'role' => $user['role']
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Password salah.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Username tidak ditemukan.']);
}

$stmt->close();
$conn->close();
