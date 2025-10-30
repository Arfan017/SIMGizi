<?php
// 1. Bersihkan output buffer
ob_clean();

// 2. Load Dompdf
require '../../dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// 3. Validasi Sesi
session_name('SIMGiziSekolah');
session_start();
include '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_sekolah') { // Sesuaikan nama role
    die("Error: Akses ditolak. Silakan login kembali.");
}

// 4. Ambil Data
$id_distribusi = $_GET['id_distribusi'] ?? 0;
$id_sekolah_session = $_SESSION['id_asal_sekolah'];

if (!ctype_digit((string)$id_distribusi) || $id_distribusi <= 0) {
    die("Error: ID Distribusi tidak valid.");
}

// 5. Query (DIUBAH: Tambah jumlah_habis, jumlah_kembali)
$sql = "SELECT 
            d.id_distribusi, d.tanggal, d.jam, d.jumlah, d.lokasi_gps,
            d.status_konfirmasi, d.status_pengiriman, d.jam_tiba,
            d.jumlah_habis, d.jumlah_kembali, -- <== PERUBAHAN DI SINI
            u.nama AS nama_petugas, 
            s.nama_sekolah AS sekolah_tujuan,
            kh.nama_bahan AS menu_kh,
            p1.nama_bahan AS menu_protein1,
            p2.nama_bahan AS menu_protein2,
            syr.nama_bahan AS menu_sayur,
            bh.nama_bahan AS menu_buah,
            mh.tambahan AS menu_tambahan
        FROM tb_distribusi d
        JOIN tb_users u ON d.id_petugas_distribusi = u.id_users 
        JOIN tb_sekolah s ON d.id_sekolah_tujuan = s.id_sekolah 
        LEFT JOIN tb_menu_harian mh ON d.id_menu = mh.id_menu
        LEFT JOIN tb_bahan_makanan kh ON mh.id_bahan_kh = kh.id_bahan
        LEFT JOIN tb_bahan_makanan p1 ON mh.id_bahan_protein1 = p1.id_bahan
        LEFT JOIN tb_bahan_makanan p2 ON mh.id_bahan_protein2 = p2.id_bahan
        LEFT JOIN tb_bahan_makanan syr ON mh.id_bahan_sayur = syr.id_bahan
        LEFT JOIN tb_bahan_makanan bh ON mh.id_bahan_buah = bh.id_bahan
        WHERE d.id_distribusi = ? AND d.id_sekolah_tujuan = ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing query: " . $conn->error);
}

$stmt->bind_param("ii", $id_distribusi, $id_sekolah_session);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    // 6. Bind Result (DIUBAH: Tambah $jumlah_habis, $jumlah_kembali)
    $stmt->bind_result(
        $id_distribusi_db,
        $tanggal,
        $jam_distribusi,
        $jumlah,
        $lokasi_gps,
        $status_konfirmasi,
        $status_pengiriman,
        $jam_tiba,
        $jumlah_habis,
        $jumlah_kembali,
        $nama_petugas,
        $sekolah_tujuan,
        $menu_kh,
        $menu_protein1,
        $menu_protein2,
        $menu_sayur,
        $menu_buah,
        $menu_tambahan
    );
    $stmt->fetch();
} else {
    die("Error: Laporan tidak ditemukan atau Anda tidak memiliki hak akses.");
}
$stmt->close();
$conn->close();

// 7. Fungsi Helper (tetap sama)
function translateStatusPengiriman($status)
{
    if ($status == '2') return ['text' => 'Diterima', 'class' => 'text-success'];
    if ($status == '1') return ['text' => 'Dalam Perjalanan', 'class' => 'text-info'];
    return ['text' => 'Belum Dikirim', 'class' => 'text-warning'];
}
function translateStatusKonfirmasi($status)
{
    if ($status == '1') return ['text' => 'Terkonfirmasi', 'class' => 'text-success'];
    return ['text' => 'Belum Dikonfirmasi', 'class' => 'text-warning'];
}
$waktu_penerimaan = $jam_tiba ?? $jam_distribusi;

// 8. Mulai Output Buffering
ob_start();
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>Laporan Distribusi #<?php echo htmlspecialchars($id_distribusi_db); ?></title>
    <style>
        /* ... (CSS Anda dari sebelumnya, tidak perlu diubah) ... */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }

        .container {
            width: 95%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 25px;
            text-decoration: underline;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-data {
            border: none;
        }

        .table-data td {
            border: none;
            padding: 5px 0;
        }

        .table-data .label {
            width: 35%;
            font-weight: bold;
        }

        .table-menu {
            border: 1px solid #999;
        }

        .table-menu th,
        .table-menu td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }

        .table-menu th {
            background-color: #f0f0f0;
        }

        .section-title {
            font-size: 15px;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 10px;
            color: #28a745;
        }

        .text-success {
            color: #28a745 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .text-info {
            color: #0dcaf0 !important;
        }

        .signature-block {
            margin-top: 60px;
            width: 100%;
        }

        .signature {
            width: 45%;
            text-align: center;
            float: left;
        }

        .signature.right {
            float: right;
        }

        .signature p {
            margin-bottom: 60px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>SISTEM INFORMASI MONITORING</h2>
            <p>PEMANTAUAN MAKANAN BERGIZI</p>
            <p><i>Kencana, Jln. Yos Sudarso, wagom utara, distrik Fakfak, kabupaten fakfak</i></p>
        </div>

        <div class="title">
            LAPORAN DETAIL PENERIMAAN
        </div>

        <div class="section-title">Data Umum Pengiriman</div>
        <table class="table-data">
            <tr>
                <td class="label">Nama Sekolah</td>
                <td>: <?php echo htmlspecialchars($sekolah_tujuan); ?></td>
            </tr>
            <tr>
                <td class="label">ID Distribusi</td>
                <td>: #<?php echo htmlspecialchars($id_distribusi_db); ?></td>
            </tr>
            <tr>
                <td class="label">Petugas Pengantar</td>
                <td>: <?php echo htmlspecialchars($nama_petugas); ?></td>
            </tr>
            <tr>
                <td class="label">Tanggal Distribusi</td>
                <td>: <?php echo htmlspecialchars(date("d F Y", strtotime($tanggal))); ?></td>
            </tr>
            <tr>
                <td class="label">Waktu Penerimaan</td>
                <td>: <?php echo htmlspecialchars($waktu_penerimaan); ?></td>
            </tr>
            <tr>
                <td class="label">Jumlah Porsi Dikirim</td>
                <td>: <?php echo htmlspecialchars($jumlah); ?> Porsi</td>
            </tr>
            <tr>
                <td class="label">Jumlah Porsi Habis</td>
                <td>: <?php echo htmlspecialchars($jumlah_habis ?? 0); ?> Porsi</td>
            </tr>
            <tr>
                <td class="label">Jumlah Porsi Kembali</td>
                <td>: <?php echo htmlspecialchars($jumlah_kembali ?? 0); ?> Porsi</td>
            </tr>
            <tr>
                <td class="label">Status Pengiriman</td>
                <td>: <span class="<?php echo translateStatusPengiriman($status_pengiriman)['class']; ?>"><?php echo translateStatusPengiriman($status_pengiriman)['text']; ?></span></td>
            </tr>
            <tr>
                <td class="label">Status Konfirmasi</td>
                <td>: <span class="<?php echo translateStatusKonfirmasi($status_konfirmasi)['class']; ?>"><?php echo translateStatusKonfirmasi($status_konfirmasi)['text']; ?></span></td>
            </tr>
        </table>

        <div class="section-title">Rincian Menu Makanan</div>
        <table class="table-menu">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Item Menu</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Karbohidrat (KH)</td>
                    <td><?php echo htmlspecialchars($menu_kh ?? '-'); ?></td>
                </tr>
                <tr>
                    <td>Protein 1</td>
                    <td><?php echo htmlspecialchars($menu_protein1 ?? '-'); ?></td>
                </tr>
                <tr>
                    <td>Protein 2</td>
                    <td><?php echo htmlspecialchars($menu_protein2 ?? '-'); ?></td>
                </tr>
                <tr>
                    <td>Sayur</td>
                    <td><?php echo htmlspecialchars($menu_sayur ?? '-'); ?></td>
                </tr>
                <tr>
                    <td>Buah</td>
                    <td><?php echo htmlspecialchars($menu_buah ?? '-'); ?></td>
                </tr>
                <tr>
                    <td>Tambahan</td>
                    <td><?php echo htmlspecialchars($menu_tambahan ?? '-'); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="signature-block">
        </div>
    </div>
</body>

</html>
<?php
// 9. Ambil HTML yang sudah di-buffer
$html = ob_get_clean();

// 10. Konfigurasi dan Generate PDF (Tidak Berubah)
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 11. Kirim PDF ke Browser (Tidak Berubah)
$file_name = "Laporan-Distribusi-#" . $id_distribusi_db . ".pdf";
$dompdf->stream($file_name, ["Attachment" => true]);
exit;
?>