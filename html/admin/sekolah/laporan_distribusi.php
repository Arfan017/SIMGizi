<!doctype html>

<?php
session_name('SIMGiziSekolah');
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_sekolah') {
    header('Location: ../../../index.php');
    exit();
}
include '../../../php/config.php';

$id_sekolah = $_SESSION['id_asal_sekolah'];

// $query = "SELECT tb_distribusi.* , tb_users.nama, tb_sekolah.nama_sekolah AS sekolah_tujuan FROM tb_distribusi 
//             JOIN tb_users ON tb_distribusi.id_petugas_distribusi = tb_users.id_users 
//             JOIN tb_sekolah ON tb_distribusi.id_sekolah_tujuan = tb_sekolah.id_sekolah
//             WHERE tb_distribusi.status_konfirmasi = '1' AND tb_distribusi.id_sekolah_tujuan = '$id_sekolah' 
//             ORDER BY tb_distribusi.tanggal DESC";

// --- Query BARU yang lebih lengkap ---
$query = "SELECT 
            d.id_distribusi, d.tanggal, d.jam, d.jumlah, d.lokasi_gps,
            d.status_konfirmasi, d.status_pengiriman, d.jam_tiba,
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
          WHERE d.status_konfirmasi = '1' AND d.id_sekolah_tujuan = '$id_sekolah' 
          ORDER BY d.tanggal DESC";
// --- Akhir Query BARU ---

$result = mysqli_query($conn, $query);

?>

<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="../../../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title>ADMIN SEKOLAH | SISTEM successRMASI MONITORING PEMANTAUAN MAKANAN BERGIZI</title>

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
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu">
                <div class="app-brand demo">
                    <a href="index.php" class="app-brand-link">
                        <i class="icon-menu icon-base ri ri-school-line icon-32px bg-success"></i>
                        <span class="app-brand-text demo menu-text fw-semibold ms-2">ADMIN SEKOLAH</span>
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
                        <a href="laporan_distribusi.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-git-repository-line"></i>
                            <div data-i18n="Basic">Laporan Distribusi</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="konfirmasi_distribusi.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-file-check-line"></i>
                            <div data-i18n="Icons">Konfirmasi Penerimaan</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="analisis_gabungan.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-bar-chart-line"></i>
                            <div data-i18n="Icons">Data Grafik</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
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
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y d-flex flex-column h-100">
                        <div class="mb-4">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Laporan Distribusi</li>
                                </ol>
                            </nav>
                        </div>

                        <div class="row gy-6 h-100 flex-grow-1">
                            <!-- Data Tables -->
                            <div class="col-12 h-100">
                                <div class="card overflow-hidden h-100">
                                    <div class="card-header bg-transparent border-0 pt-4 pb-0 sticky-top bg-white">
                                        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap">
                                            <h4 class="card-title text-success mb-0 fw-bold">Laporan Distribusi</h4>
                                            <div class="d-flex align-items-center justify-content-end flex-nowrap gap-2">
                                                <button class="btn btn-outline-success w-100" type="button" onclick="cetakLaporan()">
                                                    <span class="icon-base ri ri-printer-line icon-16px me-1_5"></span>Cetak
                                                </button>
                                                <!-- <button class="btn btn-outline-success w-100" type="button" data-bs-toggle="modal" data-bs-target="#ModalFilterData">
                                                    <span class="icon-base ri ri-filter-line icon-16px me-1_5"></span>Filter
                                                </button> -->
                                            </div>
                                        </div>
                                        <hr class="mt-3 mb-3">
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <th class="text-truncate">Tanggal</th>
                                                    <th class="text-truncate">Petugas Distribusi</th>
                                                    <th class="text-truncate">Jumlah Distribusi</th>
                                                    <th class="text-truncate">Status</th>
                                                    <th class="text-truncate">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($row = mysqli_fetch_assoc($result)) { ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div>
                                                                            <h6 class="mb-0 text-truncate"><?php echo $row['tanggal']; ?></h6>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="text-truncate"><?php echo $row['nama_petugas']; ?></td>
                                                                <td class="text-truncate">
                                                                    <span><?php echo $row['jumlah']; ?></span>
                                                                </td>
                                                                <td><span class="badge bg-label-success rounded-pill">
                                                                        Terkonfirmasi</span></td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-success btnDetail"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#ModalDetail"
                                                                            data-id_distribusi="<?= $row['id_distribusi'] ?>"
                                                                            data-petugas="<?= $row['nama_petugas'] ?>"
                                                                            data-tanggal="<?= $row['tanggal'] ?>"
                                                                            data-jam_tiba="<?= $row['jam_tiba'] ?? $row['jam'] ?>"
                                                                            data-jumlah="<?= $row['jumlah'] ?>"
                                                                            data-tujuan="<?= $row['sekolah_tujuan'] ?>"
                                                                            data-lokasi_gps="<?= $row['lokasi_gps'] ?>"
                                                                            data-status_pengiriman="<?= $row['status_pengiriman'] ?>"
                                                                            data-status_konfirmasi="<?= $row['status_konfirmasi'] ?>"
                                                                            data-menu_kh="<?= $row['menu_kh'] ?? '-' ?>"
                                                                            data-menu_p1="<?= $row['menu_protein1'] ?? '-' ?>"
                                                                            data-menu_p2="<?= $row['menu_protein2'] ?? '-' ?>"
                                                                            data-menu_sayur="<?= $row['menu_sayur'] ?? '-' ?>"
                                                                            data-menu_buah="<?= $row['menu_buah'] ?? '-' ?>"
                                                                            data-menu_tambahan="<?= $row['menu_tambahan'] ?? '-' ?>">
                                                                            Detail
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            <?php }
                                                    } else { ?>
                                                            <td colspan="5" class="text-center">Tidak ada data</td>
                                                        <?php } ?>
                                                            </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Data Tables -->
                        </div>
                    </div>

                    <!-- Modal Detail -->
                    <!-- <div class="modal fade" id="ModalDetail" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <img src="../../../assets/img/avatars/1.png" alt="Petugas" class="rounded"
                                        style="width:48px; height:48px; object-fit:cover;">
                                    <div class="ms-2">
                                        <div class="fw-semibold"><span id="detailTujuan"></span></div>
                                        <small class="text-muted">Petugas: <span id="detailIdDistribusi"></span></small>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-2">
                                        <span class="me-2"><b>Dikirim:</b> <span id="detailJumlah"></span></span>
                                        <span class="me-2"><b>Tgl:</b> <span id="detailTanggal"></span></span>
                                        <span>
                                            <br>
                                            <b>Lokasi:</b>
                                            <a href="#" class="text-primary text-decoration-underline"><i class="ri-map-pin-2-fill">
                                                    <span id="detailLokasi"></span>
                                                </i></a>
                                            <button type="button" class="btn btn-outline-info btn-sm ms-2" data-bs-toggle="modal"
                                                data-bs-target="#modalMap">
                                                Preview Map
                                            </button>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="modal fade" id="ModalDetail" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalDetailTitle">Detail Laporan Distribusi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Informasi lengkap pengiriman makanan bergizi yang telah diterima.</p>
                                    <hr>

                                    <h6 class="fw-bold text-success">Data Umum Pengiriman</h6>
                                    <dl class="row mb-3">
                                        <dt class="col-sm-5">Nama Sekolah:</dt>
                                        <dd class="col-sm-7" id="detailTujuan">-</dd>

                                        <dt class="col-sm-5">Petugas Pengantar:</dt>
                                        <dd class="col-sm-7" id="detailPetugas">-</dd>

                                        <dt class="col-sm-5">Tanggal Distribusi:</dt>
                                        <dd class="col-sm-7" id="detailTanggal">-</dd>

                                        <dt class="col-sm-5">Waktu Penerimaan:</dt>
                                        <dd class="col-sm-7" id="detailJamTiba">-</dd>

                                        <dt class="col-sm-5">Jumlah Porsi:</dt>
                                        <dd class="col-sm-7" id="detailJumlah">- Porsi</dd>

                                        <dt class="col-sm-5">Status Pengiriman:</dt>
                                        <dd class="col-sm-7"><span id="detailStatusPengiriman" class="badge"></span></dd>

                                        <dt class="col-sm-5">Status Konfirmasi:</dt>
                                        <dd class="col-sm-7"><span id="detailStatusKonfirmasi" class="badge"></span></dd>
                                    </dl>

                                    <h6 class="fw-bold text-success">Rincian Menu Makanan</h6>
                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item d-flex justify-content-between"><strong>KH:</strong> <span id="detailMenuKh">-</span></li>
                                        <li class="list-group-item d-flex justify-content-between"><strong>Protein 1:</strong> <span id="detailMenuP1">-</span></li>
                                        <li class="list-group-item d-flex justify-content-between"><strong>Protein 2:</strong> <span id="detailMenuP2">-</span></li>
                                        <li class="list-group-item d-flex justify-content-between"><strong>Sayur:</strong> <span id="detailMenuSayur">-</span></li>
                                        <li class="list-group-item d-flex justify-content-between"><strong>Buah:</strong> <span id="detailMenuBuah">-</span></li>
                                        <li class="list-group-item d-flex justify-content-between"><strong>Tambahan:</strong> <span id="detailMenuTambahan">-</span></li>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="button" class="btn btn-success" id="btnCetakDetail">
                                        <i class="ri-printer-line me-1"></i> Cetak Laporan Ini
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal Filter Data -->
                    <div class="modal fade" id="ModalFilterData" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel1">Filter Data</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>

                                </div>
                                <div class="modal-body">
                                    <p class="text-muted">Silakan isi form di bawah ini dengan benar untuk
                                        memfilter data.</p>
                                    <hr>
                                    <div class="row g-4">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="date" id="filterTanggalMulai" class="form-control" placeholder="Tanggal Mulai" />
                                                <label for="filterTanggalMulai">Tanggal Mulai</label>
                                            </div>
                                        </div>
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="date" id="filterTanggalAkhir" class="form-control" placeholder="Tanggal Akhir" />
                                                <label for="filterTanggalAkhir">Tanggal Akhir</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal">
                                        <span class="icon-base ri ri-close-line icon-16px me-1_5"></span>Tutup
                                    </button>
                                    <button type="button" class="btn btn-outline-success" id="btnFilterData">
                                        <span class="icon-base ri ri-filter-line icon-16px me-1_5"></span>Filter
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                <div class="mb-2 mb-md-0">
                                    &#169;
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>, SISTEM successRMASI MONITORING PEMANTAUAN MAKANAN BERGIZI
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="../../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../../assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="../../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../../../assets/vendor/js/menu.js"></script>

    <script>
        var currentDetailId = null;

        $(document).ready(function() {
            $('#btnFilterData').on('click', function() {
                var tanggalMulai = $('#filterTanggalMulai').val();
                var tanggalAkhir = $('#filterTanggalAkhir').val();

                $.ajax({
                    url: '../../../php/sekolah/filter_laporan_distribusi.php',
                    type: 'POST',
                    data: {
                        tanggal_mulai: tanggalMulai,
                        tanggal_akhir: tanggalAkhir
                    },
                    dataType: 'json',
                    success: function(response) {
                        var tbody = '';
                        if (response.length > 0) {
                            $.each(response, function(i, row) {
                                tbody += `<tr>
                            <td>${row.tanggal}</td>
                            <td>${row.nama}</td>
                            <td>${row.jumlah}</td>
                            <td><span class="badge bg-label-success rounded-pill">Terkonfirmasi</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-success">Detail</button>
                                </div>
                            </td>
                        </tr>`;
                            });
                        } else {
                            tbody = `<tr><td colspan="5" class="text-center">Data tidak ditemukan</td></tr>`;
                        }
                        $('.table tbody').html(tbody);
                        $('#ModalFilterData').modal('hide');
                    },
                    error: function() {
                        alert('Gagal memfilter data!');
                    }
                });
            });
        });


        $(document).on("click", ".btnDetail", function() {
            currentDetailId = $(this).data("id_distribusi");

            let petugas = $(this).data("petugas");
            let tanggal = $(this).data("tanggal");
            let jam_tiba = $(this).data("jam_tiba");
            let jumlah = $(this).data("jumlah");
            let tujuan = $(this).data("tujuan");
            let lokasi = $(this).data("lokasi_gps");
            let status_pengiriman = $(this).data("status_pengiriman").toString();
            let status_konfirmasi = $(this).data("status_konfirmasi").toString();

            let menu_kh = $(this).data("menu_kh");
            let menu_p1 = $(this).data("menu_p1");
            let menu_p2 = $(this).data("menu_p2");
            let menu_sayur = $(this).data("menu_sayur");
            let menu_buah = $(this).data("menu_buah");
            let menu_tambahan = $(this).data("menu_tambahan");

            $("#detailTujuan").text(tujuan || '-');
            $("#detailPetugas").text(petugas || '-');
            $("#detailTanggal").text(tanggal || '-');
            $("#detailJamTiba").text(jam_tiba || '-');
            $("#detailJumlah").text(jumlah || '0');

            let statusKirimSpan = $("#detailStatusPengiriman");
            if (status_pengiriman === '2') {
                statusKirimSpan.text('Diterima').removeClass('bg-warning').addClass('bg-success');
            } else if (status_pengiriman === '1') {
                statusKirimSpan.text('Dalam Perjalanan').removeClass('bg-success').addClass('bg-info');
            } else {
                statusKirimSpan.text('Belum Dikirim').removeClass('bg-success').addClass('bg-warning');
            }

            let statusKonfirmSpan = $("#detailStatusKonfirmasi");
            if (status_konfirmasi === '1') {
                statusKonfirmSpan.text('Terkonfirmasi').removeClass('bg-warning').addClass('bg-success');
            } else {
                statusKonfirmSpan.text('Belum Dikonfirmasi').removeClass('bg-success').addClass('bg-warning');
            }

            $("#detailMenuKh").text(menu_kh || '-');
            $("#detailMenuP1").text(menu_p1 || '-');
            $("#detailMenuP2").text(menu_p2 || '-');
            $("#detailMenuSayur").text(menu_sayur || '-');
            $("#detailMenuBuah").text(menu_buah || '-');
            $("#detailMenuTambahan").text(menu_tambahan || '-');

            // 6. Isi Modal - Lokasi (jika diaktifkan)
            // $("#detailLokasi").text(lokasi || 'Tidak ada');
            // if (lokasi) {
            //     $("#detailLokasiLink").attr("href", `https://maps.google.com/maps?q={lokasi}`).attr("target", "_blank");
            //     $("#mapFrame").attr("src", `https://maps.google.com/maps?q=${lokasi}&z=15&output=embed`);
            // } else {
            //     $("#detailLokasiLink").attr("href", "#").removeAttr("target");
            //     $("#mapFrame").attr("src", "");
            // }
        });

        $('#btnCetakDetail').on('click', function() {
            if (currentDetailId) {
                // Buat URL ke skrip cetak detail baru (yang perlu Anda buat)
                let url = `../../../php/sekolah/cetak_laporan_detail.php?id_distribusi=${currentDetailId}`;
                window.open(url, "_blank");
            } else {
                alert('Tidak ada ID distribusi yang dipilih.');
            }
        });

        function cetakLaporan() {
            var tanggalMulai = $('#filterTanggalMulai').val();
            var tanggalAkhir = $('#filterTanggalAkhir').val();

            let url = "../../../php/sekolah/cetak_laporan.php?tanggal_mulai=" + tanggalMulai + "&tanggal_akhir=" + tanggalAkhir;
            window.open(url, "_blank");
        }
    </script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../../../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../../../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../../../assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async="async" defer="defer" src="https://buttons.github.io/buttons.js"></script>
</body>

</html>