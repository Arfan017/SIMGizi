<?php
// Jangan mulai sesi di sini dulu!
// session_start(); 
include 'config.php';

// Fungsi bantuan untuk mengirim respons JSON dan keluar
function send_json_response($status, $message, $redirect = null)
{
    ob_clean(); // Bersihkan output buffer sebelum mengirim JSON
    header('Content-Type: application/json');
    $response = ['status' => $status, 'message' => $message];
    if ($redirect !== null) {
        $response['redirect'] = $redirect;
    }
    echo json_encode($response);
    exit;
}

// Hanya proses jika metodenya POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi input dasar
    if (empty($username) || empty($password)) {
        send_json_response("error", "Username dan password tidak boleh kosong.");
    }

    try {
        // --- Gunakan Prepared Statement ---
        $stmt = $conn->prepare("SELECT id_users, role, nama, id_asal_sekolah, password FROM tb_users WHERE username = ?");
        if ($stmt === false) {
            throw new Exception("Gagal menyiapkan query: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            // Ikat hasil ke variabel
            $stmt->bind_result($id_users, $role, $nama, $id_asal_sekolah, $hashed_password);
            $stmt->fetch();
            $stmt->close(); // Tutup statement setelah data diambil

            // --- Tentukan Nama Sesi Berdasarkan Role ---
            $session_name_to_use = '';
            // Ganti 'admin_kantor', 'admin_sekolah', 'admin_distribusi' dengan nama role yang TEPAT di database Anda
            if ($role === 'admin_kantor') {
                $session_name_to_use = 'SIMGiziKantor';
            } elseif ($role === 'admin_sekolah') {
                $session_name_to_use = 'SIMGiziSekolah';
            } elseif ($role === 'admin_distribusi') {
                $session_name_to_use = 'SIMGiziDistribusi';
            } else {
                send_json_response("error", "Role pengguna tidak valid.");
            }

            // Atur nama sesi SEBELUM session_start()
            if (!empty($session_name_to_use)) {
                session_name($session_name_to_use);
            } else {
                send_json_response("error", "Gagal menentukan nama sesi.");
            }

            // --- Baru Mulai Sesi Sekarang ---
            session_start();

            // --- Verifikasi Password ---
            if (password_verify($password, $hashed_password)) {
                // Login Berhasil - Simpan data ke session
                $_SESSION["user_id"] = $id_users;
                $_SESSION["role"] = $role;
                $_SESSION["nama"] = $nama;
                $_SESSION["id_asal_sekolah"] = $id_asal_sekolah; // Pastikan ini hanya diisi untuk admin sekolah

                // Tentukan halaman redirect berdasarkan role
                $redirect = '';
                // Ganti lagi dengan nama role yang TEPAT
                if ($role === 'admin_kantor') {
                    $redirect = "html/admin/kantor/index.php";
                } elseif ($role === 'admin_sekolah') {
                    $redirect = "html/admin/sekolah/index.php";
                } elseif ($role === 'admin_distribusi') {
                    $redirect = "html/admin/distribusi/index.php";
                } else {
                    $redirect = "index.php"; // Default fallback
                }

                send_json_response("success", "Login berhasil!", $redirect);
            } else {
                // Password salah (setelah session dimulai agar bisa simpan pesan error jika perlu)
                // Jika Anda menampilkan pesan error di form login, Anda bisa set session error di sini
                // $_SESSION['login_error'] = "Username atau password salah.";
                send_json_response("error", "Username atau password salah.");
            }
        } else {
            // Username tidak ditemukan
            $stmt->close();
            send_json_response("error", "Username atau password salah.");
        }
    } catch (Exception $e) {
        // Tangkap error tak terduga
        send_json_response("error", "Terjadi kesalahan: " . $e->getMessage());
    } finally {
        // Pastikan koneksi ditutup
        if (isset($conn)) $conn->close();
    }
} else {
    // Jika bukan metode POST
    send_json_response("error", "Metode request tidak valid.");
}
