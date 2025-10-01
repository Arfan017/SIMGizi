<?php
$host = "localhost";
$dbname = "db_sim_gizi";
$user = "root";
$pass = "";
date_default_timezone_set("Asia/Jayapura");

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (mysqli_connect_errno()) {
    die("Koneksi gagal: " . mysqli_connect_error());
}