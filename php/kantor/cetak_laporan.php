<?php
require '../../dompdf/vendor/autoload.php'; // include autoloader
require '../config.php'; // koneksi database

use Dompdf\Dompdf;

$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';
$sekolah = $_GET['sekolah'] ?? '';
$status_pengiriman = $_GET['status_pengiriman'] ?? '';

$where = "WHERE 1=1";
if ($tanggal_mulai && $tanggal_akhir) {
    $where .= " AND tb_distribusi.tanggal BETWEEN '$tanggal_mulai' AND '$tanggal_akhir'";
} elseif ($tanggal_mulai) {
    $where .= " AND tb_distribusi.tanggal >= '$tanggal_mulai'";
} elseif ($tanggal_akhir) {
    $where .= " AND tb_distribusi.tanggal <= '$tanggal_akhir'";
}
if ($sekolah) {
    $where .= " AND tb_distribusi.tujuan = '$sekolah'";
}
if ($status_pengiriman !== '') {
    $where .= " AND tb_distribusi.status_pengiriman = '$status_pengiriman'";
}

$sql = "SELECT tb_distribusi.*, tb_users.nama 
          FROM tb_distribusi 
          JOIN tb_users ON tb_distribusi.id_petugas_distribusi = tb_users.id_users 
          $where";

// Ambil data distribusi
$query = $conn->query($sql);
$petugas = $conn->query("SELECT * FROM tb_users WHERE id_users=1")->fetch_assoc();
$nama_petugas = $petugas['nama'];


// Buat HTML laporan
$html = '
<h2 style="text-align:center; margin-bottom:5px;">Rekapan Data Distribusi Barang</h2>
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
    <tr>
        <td style="padding:3px 10px;">Sekolah Tujuan</td>
        <td>:  ' . ($sekolah ?: 'semua sekolah') . '</td>
    </tr>
</table>

<br>
<table border="1" cellspacing="0" cellpadding="6" width="100%">
    <thead style="background-color:#f2f2f2; font-weight:bold; text-align:center;">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Sekolah</th>
            <th>Nama Petugas</th>
            <th>Status Pengiriman</th>
        </tr>
    </thead>
    <tbody>';


$no = 1;
while ($row = $query->fetch_assoc()) {

    $status_badge = '';
    if ($row['status_pengiriman'] === '0') {
        $status = 'Belum Dikirim';
    } elseif ($row['status_pengiriman'] === '1') {
        $status = 'Dikirim';
    } elseif ($row['status_pengiriman'] === '2') {
        $status = 'Diterima';
    }

    $html .= '
    <tr>
        <td align="center">' . $no++ . '</td>
        <td align="center">' . date("d-m-Y", strtotime($row['tanggal'])) . '</td>
        <td>' . $row['tujuan'] . '</td>
        <td>' . $row['nama'] . '</td>
        <td align="center">' . $status . '</td>
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
      <p><u>' . $nama_petugas . '</u></p>
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
