<?php
include '../config.php';

$raw_tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
$raw_tanggal_akhir = $_POST['tanggal_akhir'] ?? '';
$raw_sekolah = $_POST['sekolah'] ?? '';

// Validate dates (expecting YYYY-MM-DD) and school id
$tanggal_mulai = null;
$tanggal_akhir = null;
$sekolah = null;
if (!empty($raw_tanggal_mulai)) {
    $d = DateTime::createFromFormat('Y-m-d', $raw_tanggal_mulai);
    if ($d && $d->format('Y-m-d') === $raw_tanggal_mulai) {
        $tanggal_mulai = $raw_tanggal_mulai;
    }
}
if (!empty($raw_tanggal_akhir)) {
    $d = DateTime::createFromFormat('Y-m-d', $raw_tanggal_akhir);
    if ($d && $d->format('Y-m-d') === $raw_tanggal_akhir) {
        $tanggal_akhir = $raw_tanggal_akhir;
    }
}
if ($raw_sekolah !== '' && filter_var($raw_sekolah, FILTER_VALIDATE_INT) !== false) {
    $sekolah = (int) $raw_sekolah;
}

$where = "WHERE status_konfirmasi = '1'";
$types = '';
$params = [];

// Build dynamic WHERE with parameters
if ($tanggal_mulai && $tanggal_akhir) {
    $where .= " AND tanggal BETWEEN ? AND ?";
    $types .= 'ss';
    $params[] = $tanggal_mulai;
    $params[] = $tanggal_akhir;
} elseif ($tanggal_mulai) {
    $where .= " AND tanggal >= ?";
    $types .= 's';
    $params[] = $tanggal_mulai;
} elseif ($tanggal_akhir) {
    $where .= " AND tanggal <= ?";
    $types .= 's';
    $params[] = $tanggal_akhir;
}
if ($sekolah !== null) {
    $where .= " AND id_sekolah_tujuan = ?";
    $types .= 'i';
    $params[] = $sekolah;
}

$sql = "SELECT tb_distribusi.*, tb_sekolah.nama_sekolah as sekolah_tujuan FROM tb_distribusi \
JOIN tb_sekolah ON tb_distribusi.id_sekolah_tujuan = tb_sekolah.id_sekolah $where ORDER BY tanggal DESC";

$data = [];
if ($stmt = mysqli_prepare($conn, $sql)) {
    if (!empty($params)) {
        // bind params dynamically
        $bind_names = [];
        $bind_names[] = $types;
        for ($i = 0; $i < count($params); $i++) {
            $bind_names[] = $params[$i];
        }
        // mysqli_stmt::bind_param requires references
        $refs = [];
        foreach ($bind_names as $key => $val) {
            $refs[$key] = &$bind_names[$key];
        }
        call_user_func_array(array($stmt, 'bind_param'), $refs);
    }

    if (mysqli_stmt_execute($stmt)) {
        $res = mysqli_stmt_get_result($stmt);
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $data[] = $row;
            }
            mysqli_free_result($res);
        }
    }
    mysqli_stmt_close($stmt);
} else {
    // fallback: attempt direct query (shouldn't normally happen)
    $res = mysqli_query($conn, $sql);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($data);