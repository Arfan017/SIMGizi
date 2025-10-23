<!doctype html>

<?php
session_name('SIMGiziKantor');
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_kantor') {
    header('Location: ../../../index.php');
    exit();
}
include '../../../php/config.php';

// Query to get account data
$query = "SELECT tb_distribusi.id_distribusi, tb_distribusi.jumlah, tb_distribusi.jumlah_habis, tb_distribusi.jumlah_kembali, tb_sekolah.nama_sekolah AS nama_sekolah FROM tb_distribusi 
            JOIN tb_sekolah ON tb_distribusi.id_sekolah_tujuan = tb_sekolah.id_sekolah WHERE tb_distribusi.tanggal = CURDATE();";

$result = mysqli_query($conn, $query);

// if (mysqli_num_rows($result1) == 0) {
//     $query = "SELECT tb_distribusi.id_distribusi, tb_distribusi.jumlah ,tb_sekolah.nama_sekolah AS nama_sekolah FROM tb_distribusi
//             JOIN tb_sekolah ON tb_distribusi.id_sekolah_tujuan = tb_sekolah.id_sekolah WHERE tb_distribusi.tanggal = CURDATE()";

//     $result2 = mysqli_query($conn, $query);
// }
?>

<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="../../../assets/"
    data-template="vertical-menu-template-free">

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu">
                <div class="app-brand demo">
                    <a href="index.html" class="app-brand-link">
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

                    <li class="menu-item">
                        <a href="Tracking_pengiriman.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri ri-pin-distance-line"></i>
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
                    <div class="container-xxl flex-grow-1 container-p-y d-flex flex-column h-100 pb-0">
                        <div class="mb-4">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Monitoring</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row gy-6 flex-grow-1">
                            <div class="col-12 d-flex flex-column h-100">
                                <div class="card overflow-hidden flex-grow-1 d-flex flex-column">
                                    <div class="card-header bg-transparent border-0 pt-4 pb-0 sticky-top bg-white">
                                        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap">

                                            <h4 class="card-title text-info mb-0 fw-bold">Monitoring</h4>
                                            <div class="d-flex align-items-center justify-content-end flex-nowrap gap-2">
                                            </div>
                                        </div>
                                        <hr class="mt-3 mb-3">
                                    </div>
                                    <div class="container-xxl my-4">
                                        <div class="row g-4">
                                            <?php
                                            $i = 0;
                                            $makanan_stats = [];
                                            if (mysqli_num_rows($result) > 0) {
                                                mysqli_data_seek($result, 0);
                                                while ($data = mysqli_fetch_assoc($result)) {
                                                    $makanan_stats[] = [
                                                        'nama_sekolah' => $data['nama_sekolah'],
                                                        'total' => $data['jumlah'] ?? 0,
                                                        'habis' => $data['jumlah_habis'] ?? 0,
                                                        'tidak_habis' => $data['jumlah_kembali'] ?? 0
                                                    ];
                                            ?>
                                                    <div class="col-12 col-md-6 col-lg-4">
                                                        <div class="card shadow-sm rounded-4 h-100">
                                                            <div class="card-header bg-info text-white fw-bold text-center">
                                                                Grafik <?= $data['nama_sekolah'] ?>
                                                            </div>
                                                            <div class="card-body d-flex flex-column align-items-center justify-content-center" style="height:220px;">
                                                                <div id="apexChart<?= $i ?>" style="width:150px;height:150px;"></div>
                                                                <div class="mt-3 text-center">
                                                                    <span class="badge bg-info me-1">
                                                                        Total: <?= $data['jumlah'] ?>
                                                                    </span>
                                                                    <span class="badge bg-success me-1">
                                                                        Habis: <?= $data['jumlah_habis'] ?>
                                                                    </span>
                                                                    <span class="badge bg-warning text-dark">
                                                                        Tidak Habis: <?= $data['jumlah_kembali'] ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php
                                                    $i++;
                                                }
                                            } else {
                                                echo '<div class="col-12"><p class="text-center">Tidak ada data konfirmasi hari ini.</p></div>';
                                            }
                                            ?>
                                        </div>
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
                                        </script>, SISTEM INFORMASI MONITORING PEMANTAUAN MAKANAN BERGIZI
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
            document.addEventListener("DOMContentLoaded", function() {
                <?php for ($j = 0; $j < count($makanan_stats); $j++): ?>
                    var habis = <?= $makanan_stats[$j]['habis'] ?>;
                    var tidak_habis = <?= $makanan_stats[$j]['tidak_habis'] ?>;
                    var total = <?= $makanan_stats[$j]['total'] ?>;

                    var chartLabels, chartSeries, chartColors;

                    if (habis === 0 && tidak_habis === 0) {
                        // kalau dua-duanya nol, tampilkan total
                        chartLabels = ['Total'];
                        chartSeries = [total];
                        chartColors = ['#0d6efd']; // biru bootstrap
                    } else {
                        chartLabels = ['Habis', 'Tidak Habis'];
                        chartSeries = [habis, tidak_habis];
                        chartColors = ['#56ca00', '#ffb400'];
                    }

                    var options<?= $j ?> = {
                        chart: {
                            type: 'pie',
                            height: 120,
                            width: 120
                        },
                        labels: chartLabels,
                        series: chartSeries,
                        colors: chartColors,
                        legend: {
                            show: false
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function(val, opts) {
                                return opts.w.globals.series[opts.seriesIndex];
                            }
                        }
                    };

                    var chart<?= $j ?> = new ApexCharts(
                        document.querySelector("#apexChart<?= $j ?>"),
                        options<?= $j ?>
                    );
                    chart<?= $j ?>.render();
                <?php endfor; ?>
            });
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