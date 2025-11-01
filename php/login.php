<?php

include 'config.php';

function send_json_response($status, $message, $redirect = null)
{
    ob_clean();
    header('Content-Type: application/json');
    $response = ['status' => $status, 'message' => $message];
    if ($redirect !== null) {
        $response['redirect'] = $redirect;
    }
    echo json_encode($response);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        send_json_response("error", "Username dan password tidak boleh kosong.");
    }

    try {
        $stmt = $conn->prepare("SELECT id_users, role, nama, id_asal_sekolah, password FROM tb_users WHERE username = ?");
        if ($stmt === false) {
            throw new Exception("Gagal menyiapkan query: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id_users, $role, $nama, $id_asal_sekolah, $hashed_password);
            $stmt->fetch();
            $stmt->close(); 

            $session_name_to_use = '';
           
            if ($role === 'admin_kantor') {
                $session_name_to_use = 'SIMGiziKantor';
            } elseif ($role === 'admin_sekolah') {
                $session_name_to_use = 'SIMGiziSekolah';
            } elseif ($role === 'admin_distribusi') {
                $session_name_to_use = 'SIMGiziDistribusi';
            } else {
                send_json_response("error", "Role pengguna tidak valid.");
            }

            if (!empty($session_name_to_use)) {
                session_name($session_name_to_use);
            } else {
                send_json_response("error", "Gagal menentukan nama sesi.");
            }

            session_start();

            if (password_verify($password, $hashed_password)) {
                $_SESSION["user_id"] = $id_users;
                $_SESSION["role"] = $role;
                $_SESSION["nama"] = $nama;
                $_SESSION["id_asal_sekolah"] = $id_asal_sekolah; 

                $redirect = '';
                if ($role === 'admin_kantor') {
                    $redirect = "html/admin/kantor/index.php";
                } elseif ($role === 'admin_sekolah') {
                    $redirect = "html/admin/sekolah/index.php";
                } elseif ($role === 'admin_distribusi') {
                    $redirect = "html/admin/distribusi/index.php";
                } else {
                    $redirect = "index.php"; 
                }

                send_json_response("success", "Login berhasil!", $redirect);
            } else {
                send_json_response("error", "Username atau password salah.");
            }
        } else {
            $stmt->close();
            send_json_response("error", "Username atau password salah.");
        }
    } catch (Exception $e) {
        send_json_response("error", "Terjadi kesalahan: " . $e->getMessage());
    } finally {
        if (isset($conn)) $conn->close();
    }
} else {
    send_json_response("error", "Metode request tidak valid.");
}
