<?php
session_start();
include 'config.php';

// header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM tb_users WHERE username = '$username'");
    $data = mysqli_fetch_array($query);

    if ($data) {
        if (password_verify($password, $data['password'])) {
            $_SESSION["user_id"] = $data["id_users"];
            $_SESSION["role"] = $data["role"];
            $_SESSION["nama"] = $data["nama"];

            if ($data["role"] == 'admin_kantor') {
                $redirect = "html/admin/kantor/index.php";
            } elseif ($data["role"] == 'admin_sekolah') {
                $redirect = "html/admin/sekolah/index.php";
            } elseif ($data["role"] == 'admin_distribusi') {
                $redirect = "html/admin/distribusi/index.php";
            } else {
                $redirect = "index.php";
            }

            echo json_encode([
                "status" => "success",
                "redirect" => $redirect
            ]);
            exit;
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Username atau password salah."
            ]);
            exit;
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Username atau password salah."
        ]);
        exit;
    }
}
