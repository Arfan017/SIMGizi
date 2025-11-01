<?php
session_name('SIMGiziKantor'); // Pastikan nama sesi benar
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_kantor') { // Sesuaikan nama role
    header('Location: ../../../index.php');
    exit();
}
include '../../../php/config.php';

// --- 1. Query SQL Lengkap ---
// Mengambil semua data dari distribusi, user, sekolah, dan menu
$query_sql = "SELECT 
            d.id_distribusi, d.tanggal, d.jam, d.jumlah, d.lokasi_gps,
            d.status_konfirmasi, d.status_pengiriman, d.status_evaluasi,
            d.jam_berangkat, d.jam_tiba, d.gps_awal, d.lokasi_terkini, d.foto,
            d.jumlah_habis, d.jumlah_kembali,
            u.nama AS nama_petugas, 
            s.nama_sekolah AS sekolah_tujuan,
            kh.nama_bahan AS menu_kh,
            p1.nama_bahan AS menu_protein1,
            p2.nama_bahan AS menu_protein2,
            syr.nama_bahan AS menu_sayur,
            bh.nama_bahan AS menu_buah,
            mh.tambahan AS menu_tambahan
          FROM tb_distribusi d
          LEFT JOIN tb_users u ON d.id_petugas_distribusi = u.id_users 
          LEFT JOIN tb_sekolah s ON d.id_sekolah_tujuan = s.id_sekolah 
          LEFT JOIN tb_menu_harian mh ON d.id_menu = mh.id_menu
          LEFT JOIN tb_bahan_makanan kh ON mh.id_bahan_kh = kh.id_bahan
          LEFT JOIN tb_bahan_makanan p1 ON mh.id_bahan_protein1 = p1.id_bahan
          LEFT JOIN tb_bahan_makanan p2 ON mh.id_bahan_protein2 = p2.id_bahan
          LEFT JOIN tb_bahan_makanan syr ON mh.id_bahan_sayur = syr.id_bahan
          LEFT JOIN tb_bahan_makanan bh ON mh.id_bahan_buah = bh.id_bahan
          ORDER BY d.tanggal DESC, d.id_distribusi DESC";

$result = mysqli_query($conn, $query_sql);

// --- 2. Fungsi Helper untuk Status (Badge) ---
function getPengirimanBadge($status)
{
    if ($status == '2') return '<span class="badge bg-label-success">Diterima</span>';
    if ($status == '1') return '<span class="badge bg-label-info">Dalam Perjalanan</span>';
    return '<span class="badge bg-label-warning">Belum Dikirim</span>';
}
function getKonfirmasiBadge($status)
{
    if ($status == '1') return '<span class="badge bg-label-success">Terkonfirmasi</span>';
    return '<span class="badge bg-label-warning">Belum Dikonfirmasi</span>';
}
function getEvaluasiBadge($status)
{
    if ($status == '1') return '<span class="badge bg-label-info">Sudah Dievaluasi</span>';
    return '<span class="badge bg-label-secondary">Belum Dievaluasi</span>';
}
?>

<!doctype html>
<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="../../../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title>ADMIN KANTOR | SISTEM INFORMASI MONITORING PEMANTAUAN MAKANAN BERGIZI</title>

    <meta name="description" content="" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />

    <link rel="stylesheet" href="../../../assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css -->
    <link rel="stylesheet" href="../../../assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="../../../assets/vendor/css/core.css" />
    <link rel="stylesheet" href="../../../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- endbuild -->
    <link rel="stylesheet" href="../../../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../../../assets/vendor/js/helpers.js"></script>
    <script src="../../../assets/js/config.js"></script>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu">
                <div class="app-brand demo">
                    <a href="index.php" class="app-brand-link">
                        <i class="icon-menu icon-base ri ri-home-office-line icon-32px bg-info"></i>
                        <span class="app-brand-text demo menu-text fw-semibold ms-2">ADMIN KANTOR</span>
                    </a>
                </div>

                <ul class="menu-inner py-1">
                    <!-- Components -->
                    <li class="menu-header mt-7"><span class="menu-header-text">Menu</span></li>
                    <!-- Cards -->

                    <li class="menu-item">
                        <a href="index.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-dashboard-line"></i>
                            <div data-i18n="Basic">Dashboard</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="rekap_laporan.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-git-repository-line"></i>
                            <div data-i18n="Basic">Rekapan Data</div>
                        </a>
                    </li>

                    <!-- Icons -->
                    <li class="menu-item">
                        <a href="monitoring.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-bar-chart-box-line"></i>
                            <div data-i18n="Icons">Monitoring</div>
                        </a>
                    </li>

                    <!-- Icons -->
                    <li class="menu-item">
                        <a href="monitoring_distribusi.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-bar-chart-box-line"></i>
                            <div data-i18n="Icons">Monitoring Distribusi</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="Tracking_pengiriman.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-pin-distance-line"></i>
                            <div data-i18n="Icons">Tracking Pengiriman</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="laporan_evaluasi.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-list-check-3"></i>
                            <div data-i18n="Icons">Laporan & Evaluasi</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="kelola_akun.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-group-2-line"></i>
                            <div data-i18n="Icons">Kelola Akun</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                            <i class="icon-base ri ri-menu-line icon-md"></i>
                        </a>
                    </div>
                    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="../../../assets/img/avatars/1.png" alt="alt" class="rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="../../../assets/img/avatars/1.png" alt="alt"
                                                            class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0"><?php echo $_SESSION['nama']; ?></h6>
                                                    <small class="text-body-secondary"><?php echo $_SESSION['role']; ?></small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="d-grid px-4 pt-2 pb-1">
                                            <a class="btn btn-danger d-flex" href="../../../php/logout.php">
                                                <small class="align-middle">Logout</small>
                                                <i class="ri ri-logout-box-r-line ms-2 ri-xs"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper d-flex flex-column h-100">
                    <div class="container-xxl flex-grow-1 container-p-y d-flex flex-column h-100">
                        <div class="mb-4">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Monitoring Distribusi</li>
                                </ol>
                            </nav>
                        </div>

                        <div class="row gy-6 h-100 flex-grow-1">
                            <div class="col-12 h-100">
                                <div class="card overflow-hidden h-100">
                                    <div class="card-header bg-transparent border-0 pt-4 pb-0 sticky-top bg-white">
                                        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap">
                                            <h4 class="card-title text-info mb-0 fw-bold">Monitoring Seluruh Data Distribus</h4>
                                            <!-- <div class="d-flex align-items-center justify-content-end flex-nowrap gap-2">
                                        <button class="btn btn-outline-info w-100" type="button" data-bs-toggle="modal" data-bs-target="#ModalTambahAkun">
                                            <span class="icon-base ri ri-user-add-line icon-16px me-1_5"></span>Tambah Akun
                                        </button>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="icon-base ri ri-search-line text-muted"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0" placeholder="Cari laporan evaluasi...">
                                        </div>
                                    </div> -->
                                        </div>
                                        <div id="alertContainer"></div>
                                        <hr class="mt-3 mb-3">
                                    </div>
                                    <div class="table-responsive overflow-auto flex-grow-1" style="height: calc(570px - 90px);">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Foto</th>
                                                    <th>Tanggal</th>
                                                    <th>Tujuan Sekolah</th>
                                                    <th>Petugas</th>
                                                    <th>Jumlah</th>
                                                    <th>Jam Berangkat</th>
                                                    <th>Jam Tiba</th>
                                                    <th>Status Pengiriman</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                <?php
                                                if ($result && mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                                        <tr>
                                                            <td>
                                                                <img src="../../../uploads/<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto"
                                                                    style="width:50px; height:50px; object-fit:cover;" class="rounded">
                                                            </td>
                                                            <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['sekolah_tujuan']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['nama_petugas'] ?? 'N/A'); ?></td>
                                                            <td><?php echo htmlspecialchars($row['jumlah']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['jam_berangkat'] ?? '-'); ?></td>
                                                            <td><?php echo htmlspecialchars($row['jam_tiba'] ?? '-'); ?></td>
                                                            <td><?php echo getPengirimanBadge($row['status_pengiriman']); ?></td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-outline-info btnDetailLengkap"
                                                                    data-bs-toggle="modal" data-bs-target="#ModalDetailLengkap"
                                                                    data-id_distribusi="<?= htmlspecialchars($row['id_distribusi']) ?>"
                                                                    data-petugas="<?= htmlspecialchars($row['nama_petugas'] ?? 'N/A') ?>"
                                                                    data-tujuan="<?= htmlspecialchars($row['sekolah_tujuan']) ?>"
                                                                    data-tanggal="<?= htmlspecialchars($row['tanggal']) ?>"
                                                                    data-jam_berangkat="<?= htmlspecialchars($row['jam_berangkat'] ?? '-') ?>"
                                                                    data-jam_tiba="<?= htmlspecialchars($row['jam_tiba'] ?? '-') ?>"
                                                                    data-jumlah="<?= htmlspecialchars($row['jumlah']) ?>"
                                                                    data-jumlah_habis="<?= htmlspecialchars($row['jumlah_habis']) ?>"
                                                                    data-jumlah_kembali="<?= htmlspecialchars($row['jumlah_kembali']) ?>"
                                                                    data-status_pengiriman="<?= htmlspecialchars($row['status_pengiriman']) ?>"
                                                                    data-status_konfirmasi="<?= htmlspecialchars($row['status_konfirmasi']) ?>"
                                                                    data-status_evaluasi="<?= htmlspecialchars($row['status_evaluasi']) ?>"
                                                                    data-foto="<?= htmlspecialchars($row['foto']) ?>"
                                                                    data-gps_awal="<?= htmlspecialchars($row['gps_awal'] ?? '-') ?>"
                                                                    data-lokasi_terkini="<?= htmlspecialchars($row['lokasi_terkini'] ?? '-') ?>"
                                                                    data-lokasi_tujuan="<?= htmlspecialchars($row['lokasi_gps'] ?? '-') ?>"
                                                                    data-menu_kh="<?= htmlspecialchars($row['menu_kh'] ?? '-') ?>"
                                                                    data-menu_p1="<?= htmlspecialchars($row['menu_protein1'] ?? '-') ?>"
                                                                    data-menu_p2="<?= htmlspecialchars($row['menu_protein2'] ?? '-') ?>"
                                                                    data-menu_sayur="<?= htmlspecialchars($row['menu_sayur'] ?? '-') ?>"
                                                                    data-menu_buah="<?= htmlspecialchars($row['menu_buah'] ?? '-') ?>"
                                                                    data-menu_tambahan="<?= htmlspecialchars($row['menu_tambahan'] ?? '-') ?>">
                                                                    Detail
                                                                </button>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="9" class="text-center">Tidak ada data distribusi ditemukan.</td></tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="ModalDetailLengkap" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalDetailTitle">Detail Lengkap Distribusi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-5">
                                                <h6 class="fw-bold text-info">Foto Distribusi</h6>
                                                <img id="detailLengkapFoto" src="" alt="Foto Distribusi" class="img-fluid rounded mb-3" style="width: 100%; height: 200px; object-fit: cover;">

                                                <h6 class="fw-bold text-info mt-3">Status</h6>
                                                <dl class="row mb-0">
                                                    <dt class="col-sm-6">Pengiriman:</dt>
                                                    <dd class="col-sm-6"><span id="detailLengkapStatusKirim" class="badge"></span></dd>
                                                    <dt class="col-sm-6">Konfirmasi:</dt>
                                                    <dd class="col-sm-6"><span id="detailLengkapStatusKonfirm" class="badge"></span></dd>
                                                    <dt class="col-sm-6">Evaluasi:</dt>
                                                    <dd class="col-sm-6"><span id="detailLengkapStatusEval" class="badge"></span></dd>
                                                </dl>
                                            </div>

                                            <div class="col-md-7">
                                                <h6 class="fw-bold text-info">Data Pengiriman</h6>
                                                <dl class="row mb-3">
                                                    <dt class="col-sm-5">ID Distribusi:</dt>
                                                    <dd class="col-sm-7" id="detailLengkapId">-</dd>
                                                    <dt class="col-sm-5">Tujuan Sekolah:</dt>
                                                    <dd class="col-sm-7" id="detailLengkapTujuan">-</dd>
                                                    <dt class="col-sm-5">Petugas:</dt>
                                                    <dd class="col-sm-7" id="detailLengkapPetugas">-</dd>
                                                    <dt class="col-sm-5">Tanggal:</dt>
                                                    <dd class="col-sm-7" id="detailLengkapTanggal">-</dd>
                                                    <dt class="col-sm-5">Jam Berangkat:</dt>
                                                    <dd class="col-sm-7" id="detailLengkapJamBerangkat">-</dd>
                                                    <dt class="col-sm-5">Jam Tiba:</dt>
                                                    <dd class="col-sm-7" id="detailLengkapJamTiba">-</dd>
                                                </dl>

                                                <h6 class="fw-bold text-info">Data Porsi</h6>
                                                <dl class="row mb-3">
                                                    <dt class="col-sm-5">Jumlah Kirim:</dt>
                                                    <dd class="col-sm-7" id="detailLengkapJumlah">-</dd>
                                                    <dt class="col-sm-5">Jumlah Habis:</dt>
                                                    <dd class="col-sm-7" id="detailLengkapJumlahHabis">-</dd>
                                                    <dt class="col-sm-5">Jumlah Kembali:</dt>
                                                    <dd class="col-sm-7" id="detailLengkapJumlahKembali">-</dd>
                                                </dl>

                                                <h6 class="fw-bold text-info">Detail Menu</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm">
                                                        <tbody>
                                                            <tr>
                                                                <td style="width: 120px;">KH</td>
                                                                <td><span id="detailLengkapMenuKh"></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Protein 1</td>
                                                                <td><span id="detailLengkapMenuP1"></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Protein 2</td>
                                                                <td><span id="detailLengkapMenuP2"></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Sayur</td>
                                                                <td><span id="detailLengkapMenuSayur"></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Buah</td>
                                                                <td><span id="detailLengkapMenuBuah"></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Tambahan</td>
                                                                <td><span id="detailLengkapMenuTambahan"></span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                              
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                <div class="mb-2 mb-md-0">
                                    &#169;
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>, SISTEM INFORMASI MONITORING PEMANTAUAN MAKANAN BERGIZI
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- / Content wrapper -->
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <script src="../../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../../../assets/vendor/js/menu.js"></script>
    <script src="../../../assets/js/main.js"></script>

    <script>
        $(document).ready(function() {
            $(document).on("click", ".btnDetailLengkap", function() {
                var data = $(this).data();

                var fotoUrl = data.foto ? '../../../uploads/' + data.foto : '../../../assets/img/avatars/1.png'; 
                $('#detailLengkapFoto').attr('src', fotoUrl);

                $('#detailLengkapId').text('#' + data.id_distribusi);
                $('#detailLengkapTujuan').text(data.tujuan || '-');
                $('#detailLengkapPetugas').text(data.petugas || '-');
                $('#detailLengkapTanggal').text(data.tanggal || '-');
                $('#detailLengkapJamBerangkat').text(data.jam_berangkat || '-');
                $('#detailLengkapJamTiba').text(data.jam_tiba || '-');

                $('#detailLengkapJumlah').text(data.jumlah + ' Porsi');
                $('#detailLengkapJumlahHabis').text((data.jumlah_habis || 0) + ' Porsi');
                $('#detailLengkapJumlahKembali').text((data.jumlah_kembali || 0) + ' Porsi');

                var statusKirim = data.status_pengiriman.toString();
                var $spanKirim = $('#detailLengkapStatusKirim').removeClass('bg-label-success bg-label-info bg-label-warning');
                if (statusKirim === '2') $spanKirim.text('Diterima').addClass('bg-label-success');
                else if (statusKirim === '1') $spanKirim.text('Dalam Perjalanan').addClass('bg-label-info');
                else $spanKirim.text('Belum Dikirim').addClass('bg-label-warning');

                var statusKonfirm = data.status_konfirmasi.toString();
                var $spanKonfirm = $('#detailLengkapStatusKonfirm').removeClass('bg-label-success bg-label-warning');
                if (statusKonfirm === '1') $spanKonfirm.text('Terkonfirmasi').addClass('bg-label-success');
                else $spanKonfirm.text('Belum Dikonfirmasi').addClass('bg-label-warning');

                var statusEval = data.status_evaluasi.toString();
                var $spanEval = $('#detailLengkapStatusEval').removeClass('bg-label-info bg-label-secondary');
                if (statusEval === '1') $spanEval.text('Sudah Dievaluasi').addClass('bg-label-info');
                else $spanEval.text('Belum Dievaluasi').addClass('bg-label-secondary');

                $('#detailLengkapMenuKh').text(data.menu_kh || '-');
                $('#detailLengkapMenuP1').text(data.menu_p1 || '-');
                $('#detailLengkapMenuP2').text(data.menu_p2 || '-');
                $('#detailLengkapMenuSayur').text(data.menu_sayur || '-');
                $('#detailLengkapMenuBuah').text(data.menu_buah || '-');
                $('#detailLengkapMenuTambahan').text(data.menu_tambahan || '-');

                $('#detailLengkapGpsAwal').text(data.gps_awal || '-');
                $('#detailLengkapLokasiTerkini').text(data.lokasi_terkini || '-');
                $('#detailLengkapLokasiTujuan').text(data.lokasi_tujuan || '-');
            });
        });
    </script>
</body>

</html>