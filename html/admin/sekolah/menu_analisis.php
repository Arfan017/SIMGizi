<!doctype html>

<?php
session_name('SIMGiziSekolah');
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_sekolah') {
    header('Location: ../../../index.php');
    exit();
}
include '../../../php/config.php';
?>

<html
    lang="en"
    class="layout-menu-fixed layout-compact"
    data-assets-path="../../../assets/"

    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title>ADMIN SEKOLAH | SISTEM INFORMASI MONITORING PEMANTAUAN MAKANAN BERGIZI</title>

    <meta name="description" content="" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

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

                    <!-- Icons -->
                    <li class="menu-item">
                        <a href="konfirmasi_distribusi.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-file-check-line"></i>
                            <div data-i18n="Icons">Konfirmasi Penerimaan</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="data_grafik.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-bar-chart-line"></i>
                            <div data-i18n="Icons">Data Grafik</div>
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
                                <a
                                    class="nav-link dropdown-toggle hide-arrow p-0"
                                    href="javascript:void(0);"
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
                <div class="content-wrapper d-flex flex-column h-100">
                    <div class="container-xxl flex-grow-1 container-p-y d-flex flex-column h-100">
                        <div class="mb-4">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Data Grafik</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row gy-6 h-100 flex-grow-1">
                            <div class="col-12 h-100">
                                <div class="card overflow-hidden h-100">
                                    <div class="card-header ">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="card-title text-success mb-0 fw-bold">Distribusi Diterima (per bulan)</h5>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-primary active" id="btnBulanan">Bulanan</button>
                                                <button type="button" class="btn btn-outline-primary" id="btnHarian">Harian</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="chartMenuFrequency"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
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
                </div>

            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- Core JS -->
    <script src="../../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../../assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="../../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../../../assets/vendor/js/menu.js"></script>


    <script>
        $(document).ready(function() {
            var chart; // Variabel global untuk chart

            // Opsi dasar untuk Bar Chart (horizontal)
            var options = {
                chart: {
                    type: 'bar',
                    height: 450,
                    toolbar: {
                        show: true
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                colors: ['#28a745'], // Warna hijau
                dataLabels: {
                    enabled: true,
                    offsetX: -6,
                    style: {
                        fontSize: '12px',
                        colors: ['#fff']
                    },
                    formatter: function(val) {
                        return val;
                    }
                },
                series: [],
                xaxis: {
                    categories: [],
                    title: {
                        text: 'Jumlah Penyajian'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Item Menu'
                    }
                },
                legend: {
                    show: false
                }, // Sembunyikan legend untuk 1 series
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " kali disajikan";
                        }
                    }
                },
                noData: {
                    text: 'Memuat data...'
                }
            };

            // Inisialisasi chart
            var chartEl = document.querySelector('#chartMenuFrequency');
            chart = new ApexCharts(chartEl, options);
            chart.render();

            /**
             * Fungsi untuk memuat data chart berdasarkan tipe view.
             * @param {string} viewType - 'monthly' atau 'daily'
             */
            function loadChartData(viewType) {
                chart.updateOptions({
                    noData: {
                        text: 'Memuat data...'
                    }
                });

                $.ajax({
                    url: '../../../php/sekolah/api_get_menu_frequency.php', // Tetap panggil API yang sama
                    type: 'GET',
                    data: {
                        view: viewType
                    }, // Kirim 'view' BUKAN 'start_date'/'end_date'
                    dataType: 'json',
                    success: function(response) {
                        console.log("Data JSON diterima:", response);

                        if (!response || response.status !== 'success' || !response.data || !response.data.labels || !response.data.series) {
                            var errorMsg = response ? (response.message || 'Format data salah/tidak lengkap') : 'Respon tidak valid';
                            console.error("Error API/Data:", errorMsg);
                            chart.updateOptions({
                                series: [],
                                xaxis: {
                                    categories: []
                                },
                                noData: {
                                    text: errorMsg
                                }
                            });
                            return;
                        }

                        console.log("Mencoba update chart...");
                        try {
                            // Update chart
                            chart.updateOptions({
                                series: response.data.series,
                                xaxis: { // Untuk horizontal bar, label ada di Y-axis
                                    categories: [], // Kosongkan X categories (angka)
                                    title: {
                                        text: 'Jumlah Penyajian'
                                    }
                                },
                                yaxis: { // Masukkan label item menu di Y-axis
                                    categories: response.data.labels,
                                    title: {
                                        text: 'Item Menu'
                                    }
                                },
                                noData: {
                                    text: (response.data.labels.length === 0) ? 'Tidak ada data menu pada periode ini.' : 'Memuat...'
                                }
                            });
                            console.log("Update chart berhasil.");

                        } catch (e) {
                            console.error("Error saat memanggil updateOptions:", e);
                            chart.updateOptions({
                                series: [],
                                xaxis: {
                                    categories: []
                                },
                                yaxis: {
                                    categories: []
                                },
                                noData: {
                                    text: 'Error saat merender chart.'
                                }
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown, jqXHR.responseText);
                        chart.updateOptions({
                            series: [],
                            xaxis: {
                                categories: []
                            },
                            yaxis: {
                                categories: []
                            },
                            noData: {
                                text: 'Gagal menghubungi API.'
                            }
                        });
                    }
                });
            }

            // Listener untuk tombol filter
            $('#btnBulanan').on('click', function() {
                $(this).addClass('active').siblings().removeClass('active');
                loadChartData('monthly'); // Kirim 'monthly'
            });

            $('#btnHarian').on('click', function() {
                $(this).addClass('active').siblings().removeClass('active');
                loadChartData('daily'); // Kirim 'daily'
            });

            // Muat data default (bulanan) saat halaman pertama kali dibuka
            loadChartData('monthly');
        });
    </script>

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