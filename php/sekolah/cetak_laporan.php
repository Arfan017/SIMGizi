<?php
session_start();
require '../../dompdf/vendor/autoload.php'; // include autoloader
require '../config.php'; // koneksi database

use Dompdf\Dompdf;

$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';
// $sekolah       = $_GET['sekolah'] ?? '';

$where = "WHERE status_konfirmasi = '1'";

if (!empty($tanggal_mulai) && !empty($tanggal_akhir)) {
    $where .= " AND tanggal BETWEEN '" . date('Y-m-d', strtotime($tanggal_mulai)) . "' 
                               AND '" . date('Y-m-d', strtotime($tanggal_akhir)) . "'";
} elseif (!empty($tanggal_mulai)) {
    $where .= " AND tanggal >= '" . date('Y-m-d', strtotime($tanggal_mulai)) . "'";
} elseif (!empty($tanggal_akhir)) {
    $where .= " AND tanggal <= '" . date('Y-m-d', strtotime($tanggal_akhir)) . "'";
}

// if (!empty($sekolah)) {
//     $where .= " AND tujuan = '" . $conn->real_escape_string($sekolah) . "'";
// }

$sql = "SELECT tb_distribusi.*, tb_users.nama 
          FROM tb_distribusi 
          JOIN tb_users ON tb_distribusi.id_petugas_distribusi = tb_users.id_users 
          $where ORDER BY tb_distribusi.tanggal ASC";

// Ambil data distribusi
$query = $conn->query($sql);
$petugas = $conn->query("SELECT * FROM tb_users WHERE id_users=2")->fetch_assoc();
// $nama_petugas = $petugas['nama'];


// Buat HTML laporan
$html = '
<h2 style="text-align:center; margin-bottom:5px;">Laporan Konfirmasi Penerimaan Barang</h2>
<br>

<table style="margin: 0; text-align:left; font-size:12px; border-collapse:collapse;">
    <tr>
        <td style="padding:3px 10px;">Dicetak pada</td>
        <td>: ' . date("d-m-Y H:i") . '</td>
    </tr>
    <tr>
        <td style="padding:3px 10px;">Tanggal Mulai</td>
        <td>: ' . ($tanggal_mulai ?: '-') . '</td>
    </tr>
    <tr>
        <td style="padding:3px 10px;">Tanggal Akhir</td>
        <td>: ' . ($tanggal_akhir ?: '-') . '</td>
    </tr>
</table>

<br>
<table border="1" cellspacing="0" cellpadding="6" width="100%">
    <thead style="background-color:#f2f2f2; font-weight:bold; text-align:center;">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Petugas Distribusi</th>
            <th>Jumlah</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>';


$no = 1;
while ($row = $query->fetch_assoc()) {
    $html .= '
    <tr>
        <td align="center">' . $no++ . '</td>
        <td align="center">' . date("d-m-Y", strtotime($row['tanggal'])) . '</td>
        <td align="center">' . $row['nama'] . '</td>
        <td>' . $row['jumlah'] . '</td>
        <td align="center">' . ($row['status_konfirmasi'] == 1 ? 'Terkirim' : 'Belum Terkirim') . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Tambahkan tanda tangan / footer laporan
$html .= '
<br><br>
<table width="100%">
  <tr>
    <td style="text-align:right;">
      <p>Mengetahui,<br>Petugas Sekolah</p>
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
$dompdf->setPaper('A4', 'portrait'); // bisa "portrait" juga

// Render & output
$dompdf->render();
$dompdf->stream("laporan_distribusi.pdf", array("Attachment" => false));
// Attachment=false supaya langsung tampil di browser
