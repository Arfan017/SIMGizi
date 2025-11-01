<?php
// 1. Bersihkan output buffer untuk mencegah error JSON
ob_clean();
include '../config.php';

// Fungsi helper untuk respons
function send_json_response($status, $message, $data = null)
{
    // Tentukan kode HTTP berdasarkan status
    if ($status === 'error') {
        http_response_code(401); // 401 Unauthorized (Gagal login)
    }

    header('Content-Type: application/json');
    $response = ['status' => $status, 'message' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}

// Ambil data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    send_json_response('error', 'Username dan password tidak boleh kosong.');
}

try {
    // Ambil SEMUA data user, termasuk id_asal_sekolah
    $stmt = $conn->prepare("SELECT id_users, nama, role, password, id_asal_sekolah FROM tb_users WHERE username = ?");
    if ($stmt === false) {
        throw new Exception("Prepare statement gagal: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();

    // --- PERBAIKAN UTAMA (MENGGANTI GET_RESULT) ---

    // 1. Simpan hasil query ke memori
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // 2. Bind hasil kolom ke variabel PHP
        $stmt->bind_result($id_users, $nama, $role, $hashed_password, $id_asal_sekolah);

        // 3. Fetch data ke dalam variabel-variabel tersebut
        $stmt->fetch();

        // 4. Verifikasi password dari variabel
        if (password_verify($password, $hashed_password)) {

            // --- LOGIKA SESSION_NAME (PENTING!) ---
            // Tentukan nama sesi berdasarkan role
            $session_name_to_use = '';
            if ($role === 'admin_kantor') {
                $session_name_to_use = 'SIMGiziKantor';
            } elseif ($role === 'admin_sekolah') {
                $session_name_to_use = 'SIMGiziSekolah';
            } elseif ($role === 'admin_distribusi') {
                $session_name_to_use = 'SIMGiziDistribusi';
            }

            if (empty($session_name_to_use)) {
                throw new Exception("Role pengguna tidak dikenal.");
            }

            // Atur nama sesi SEBELUM session_start()
            session_name($session_name_to_use);
            session_start();
            // --- AKHIR LOGIKA SESSION_NAME ---

            // Simpan data ke sesi
            $_SESSION["user_id"] = $id_users;
            $_SESSION["role"] = $role;
            $_SESSION["nama"] = $nama;
            $_SESSION["id_asal_sekolah"] = $id_asal_sekolah; // Sekarang id_asal_sekolah juga tersimpan

            // Kirim respons sukses
            send_json_response('success', 'Login berhasil!', [
                'user_id' => $id_users,
                'nama' => $nama,
                'role' => $role
            ]);
        } else {
            send_json_response('error', 'Password salah.');
        }
    } else {
        send_json_response('error', 'Username tidak ditemukan.');
    }
    // --- AKHIR PERBAIKAN ---

} catch (Exception $e) {
    http_response_code(500); // 500 untuk error server (database, dll)
    send_json_response('error', 'Terjadi kesalahan server: ' . $e->getMessage());
} finally {
    // Selalu tutup statement dan koneksi
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
