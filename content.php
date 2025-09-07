<?php

if (!defined('INDEX')) die("");

$halaman = array(
    "index", "kelola_akun", "monitoring", "laporan_evaluasi", "rekap_laporan"
);

if (isset($_GET['hal'])) {
    $hal = $_GET['hal'];
} else {
    $hal = "index";
}

foreach ($halaman as $h) {
    if ($hal == $h) {
        include "$h.php";
        break;
    }
}
