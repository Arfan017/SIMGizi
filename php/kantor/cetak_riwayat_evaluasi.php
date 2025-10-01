<?php
session_start();
require '../../dompdf/vendor/autoload.php'; // include autoloader
require '../config.php'; // koneksi database

use Dompdf\Dompdf;

$sekolah = $_GET['sekolah'] ?? '';
$tglAwal = $_GET['tanggal_awal'] ?? '';
$tglAkhir = $_GET['tanggal_akhir'] ?? '';


$sekolah_nama = "";

$where = "WHERE 1=1";
if ($sekolah) {
    $where .= " AND tb_distribusi.id_sekolah_tujuan = '$sekolah'";
    $sekolah_nama = getNamaSekolah($conn, $sekolah);
}

if ($tglAwal && $tglAkhir) {
    $where .= " AND tb_distribusi.tanggal BETWEEN '$tglAwal' AND '$tglAkhir'";
} elseif ($tglAwal) {
    $where .= " AND tb_distribusi.tanggal >= '$tglAwal'";
} elseif ($tglAkhir) {
    $where .= " AND tb_distribusi.tanggal <= '$tglAkhir'";
}

$query = "SELECT tb_evaluasi.*, tb_distribusi.id_sekolah_tujuan, tb_distribusi.tanggal, tb_sekolah.nama_sekolah AS sekolah_tujuan 
          FROM tb_evaluasi JOIN tb_distribusi ON tb_evaluasi.id_distribusi = tb_distribusi.id_distribusi
          JOIN tb_sekolah ON tb_distribusi.id_sekolah_tujuan = tb_sekolah.id_sekolah
          $where
          ORDER BY tb_evaluasi.id_evaluasi DESC";
          
$result = $conn->query($query);

$petugas = $conn->query("SELECT * FROM tb_users WHERE id_users=1")->fetch_assoc();
$nama_petugas = $petugas['nama'];

// Buat HTML laporan
$html = '
<h2 style="text-align:center; margin-bottom:5px;">Riwayat Laporan Evaluasi Distribusi</h2>
<br>
<table style="margin: 0; text-align:left; font-size:12px; border-collapse:collapse;">
    <tr>
        <td style="padding:3px 10px;">Dicetak pada</td>
        <td>: ' . date("d-m-Y H:i") . '</td>
    </tr>
    <tr>
        <td style="padding:3px 10px;">Tanggal Mulai</td>
        <td>: ' . ($tglAwal ?: '-') . '</td>
    </tr>
    <tr>
        <td style="padding:3px 10px;">Tanggal Akhir</td>
        <td>: ' . ($tglAkhir ?: '-') . '</td>
    </tr>
    <tr>
        <td style="padding:3px 10px;">Sekolah Tujuan</td>
        <td>:  ' . ($sekolah_nama ?: 'semua sekolah') . '</td>
    </tr>
</table>

<br>
<table border="1" cellspacing="0" cellpadding="6" width="100%">
    <thead style="background-color:#f2f2f2; font-weight:bold; text-align:center;">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Sekolah</th>
            <th>Status Distribusi</th>
            <th>Catatan Evaluasi</th>
        </tr>
    </thead>
    <tbody>';

$no = 1;
while ($data = $result->fetch_assoc()) {
    $status = "-";
    if ($data["status_distribusi"] == 1) $status = "Baik";
    elseif ($data["status_distribusi"] == 2) $status = "Kurang Baik";
    elseif ($data["status_distribusi"] == 3) $status = "Tidak Baik";
    $html .= '
    <tr>
        <td align="center">' . $no++ . '</td>
        <td align="center">' . date("d-m-Y", strtotime($data['tanggal'])) . '</td>
        <td>' . $data['sekolah_tujuan'] . '</td>
        <td align="center">' . $status . '</td>
        <td>' . $data['catatan'] . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Tambahkan tanda tangan / footer laporan
$html .= '
<br><br>
<table width="100%">
  <tr>
    <td style="text-align:right;">
      <p>Mengetahui,<br>Petugas Kantor</p>
      <br><br><br>
      <p><u>' . $_SESSION['nama'] . '</u></p>
    </td>
  </tr>
</table>
';

// Konversi ke PDF dengan Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Setting ukuran & orientasi kertas
$dompdf->setPaper('A4', 'portrait');

// Render & output
$dompdf->render();
$dompdf->stream("riwayat_laporan_evaluasi.pdf", array("Attachment" => false));

function getNamaSekolah($conn, $id_sekolah) {
    $result = $conn->query("SELECT nama_sekolah FROM tb_sekolah WHERE id_sekolah='$id_sekolah' LIMIT 1");
    if ($result && $row = $result->fetch_assoc()) {
        return $row['nama_sekolah'];
    }
    return '';
}