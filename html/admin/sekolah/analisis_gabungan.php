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
                        <!-- <div class="row gy-3 h-100"> -->
                        <!-- <div class="col-md-6"> -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title text-success mb-0 fw-bold">Grafik Penerimaan</h4>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-success active" id="btnJumlahBulanan">Bulanan</button>
                                    <button type="button" class="btn btn-outline-success" id="btnJumlahHarian">Harian</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="chartJumlah"></div>
                            </div>
                        </div>
                        <!-- </div> -->

                        <!-- <div class="col-md-6"> -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title text-success mb-0 fw-bold">Frekuensi Item Menu</h4>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-success active" id="btnFrekuensiBulanan">Bulanan</button>
                                    <button type="button" class="btn btn-outline-success" id="btnFrekuensiHarian">Harian</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="chartFrekuensi"></div>
                            </div>
                        </div>
                        <!-- </div> -->
                        <!-- </div> -->
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
                    <div class="content-backdrop fade"></div>
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

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../../../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../../../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../../../assets/js/dashboards-analytics.js"></script>

    <script>
        $(document).ready(function() {
            // Variabel global untuk instance chart
            var chartJumlah;
            var chartFrekuensi;

            // --- Opsi LENGKAP untuk Chart Jumlah (Stacked Bar) ---
            var optionsJumlah = {
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    toolbar: {
                        show: true
                    }
                },
                series: [], // Akan diisi dari API
                xaxis: {
                    categories: [], // Akan diisi dari API
                    labels: {
                        rotate: -45
                    }
                },
                colors: ['#28a745', '#ffc107'], // Hijau & Kuning
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '60%',
                        dataLabels: {
                            total: {
                                enabled: true,
                                style: {
                                    fontSize: '13px',
                                    fontWeight: 900
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                }, // Label di dalam segmen disembunyikan
                legend: {
                    position: 'top',
                    horizontalAlign: 'center'
                },
                grid: {
                    borderColor: '#e7e7e7'
                },
                noData: {
                    text: 'Memuat data...'
                } // Teks loading awal
            };

            // --- Opsi LENGKAP untuk Chart Frekuensi (Vertical Bar dg Warna Kategori) ---
            var optionsFrekuensi = {
                chart: {
                    type: 'bar',
                    height: 450,
                    toolbar: {
                        show: true
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false, // Vertikal
                        columnWidth: '60%',
                        // --- Distribusi Warna Berdasarkan Fungsi ---
                        distributed: true, // PENTING: Aktifkan mode warna terdistribusi
                        dataLabels: {
                            position: 'top',
                        },
                    }
                },
                // --- Hapus colors utama, akan diatur oleh fungsi ---
                // colors: ['#0d6efd'],
                dataLabels: {
                    enabled: true,
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ["#304758"]
                    },
                    formatter: function(val) {
                        return val;
                    }
                },
                series: [], // Akan diisi dari API
                xaxis: {
                    // Kategori X sekarang diambil dari 'x' di data series
                    type: 'category', // Set tipe ke category
                    categories: [], // Kosongkan, akan diisi dari data series
                    title: {
                        text: 'Item Menu'
                    },
                    labels: {
                        rotate: -45,
                        trim: true
                    } // Miringkan & potong label panjang
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Penyajian'
                    }
                },
                legend: {
                    show: false
                }, // Tetap sembunyikan
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " kali";
                        }
                    }
                },
                noData: {
                    text: 'Memuat data...'
                },
                // --- Fungsi untuk menentukan warna batang ---
                fill: {
                    opacity: 1 // Pastikan warna solid
                },
                // ApexCharts secara otomatis akan menggunakan 'colors' di bawah JIKA 'distributed: true'
                // Definisikan palet warna untuk kategori
                colors: ['#0d6efd', '#28a745', '#ffc107', '#fd7e14', '#6f42c1'] // Contoh: Biru (KH?), Hijau(Sayur?), Kuning(Protein?), Oranye(Buah?), Ungu(Lainnya?)
                // ATAU Gunakan fungsi untuk pemetaan eksplisit (lebih aman jika urutan data berubah)
                /*
                colors: function({ value, seriesIndex, dataPointIndex, w }) {
                    // Ambil kategori dari data asli yang kita kirim
                    let category = w.config.series[seriesIndex].data[dataPointIndex].kategori;
                    switch (category) {
                        case 'KH': return '#0d6efd'; // Biru
                        case 'Protein': return '#ffc107'; // Kuning
                        case 'Sayur': return '#28a745'; // Hijau
                        case 'Buah': return '#fd7e14'; // Oranye
                        default: return '#6c757d'; // Abu-abu
                    }
                }
                */
            };

            // --- Inisialisasi Chart (Dengan Pengecekan Elemen) ---
            var chartElJumlah = document.querySelector('#chartJumlah');
            if (chartElJumlah) {
                chartJumlah = new ApexCharts(chartElJumlah, optionsJumlah);
                chartJumlah.render();
                console.log("Chart Jumlah diinisialisasi.");
            } else {
                console.error("Elemen #chartJumlah tidak ditemukan!");
                $('#chartJumlah').html('<p class="text-danger">Error: Kontainer chart #chartJumlah tidak ditemukan.</p>');
            }

            var chartElFrekuensi = document.querySelector('#chartFrekuensi');
            if (chartElFrekuensi) {
                chartFrekuensi = new ApexCharts(chartElFrekuensi, optionsFrekuensi);
                chartFrekuensi.render();
                console.log("Chart Frekuensi diinisialisasi.");
            } else {
                console.error("Elemen #chartFrekuensi tidak ditemukan!");
                $('#chartFrekuensi').html('<p class="text-danger">Error: Kontainer chart #chartFrekuensi tidak ditemukan.</p>');
            }


            /**
             * Fungsi HANYA untuk memuat data Chart Jumlah (Habis/Kembali).
             */
            function loadChartJumlahData(viewType) {
                // Tampilkan loading di div chart
                $('#chartJumlah').html('<p class="text-center text-muted">Memuat data...</p>');
                if (chartJumlah) {
                    chartJumlah.destroy(); // Hancurkan chart lama jika ada
                    chartJumlah = null; // Hapus referensi lama
                }

                $.ajax({
                    url: '../../../php/sekolah/api_get_chart_data.php', // API chart jumlah
                    type: 'GET',
                    data: {
                        view: viewType
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Data Jumlah Diterima:", JSON.stringify(response, null, 2));
                        try {
                            // Validasi data (seperti sebelumnya)
                            if (!response || response.status !== 'success' || !response.labels || !response.series || !Array.isArray(response.labels) || !Array.isArray(response.series)) {
                                throw new Error(response ? (response.message || 'Format data jumlah salah') : 'Respon jumlah tidak valid');
                            }

                            console.log("Membuat ulang Chart Jumlah...");
                            // Buat Opsi Baru Lengkap untuk pembuatan ulang
                            var newOptionsJumlah = {
                                chart: {
                                    type: 'bar',
                                    height: 350,
                                    stacked: true,
                                    toolbar: {
                                        show: true
                                    }
                                },
                                series: response.series, // Data baru
                                xaxis: {
                                    categories: response.labels,
                                    labels: {
                                        rotate: -45
                                    }
                                }, // Data baru
                                colors: ['#28a745', '#ffc107'],
                                plotOptions: {
                                    bar: {
                                        horizontal: false,
                                        columnWidth: '60%',
                                        dataLabels: {
                                            total: {
                                                enabled: true,
                                                style: {
                                                    fontSize: '13px',
                                                    fontWeight: 900
                                                }
                                            }
                                        }
                                    }
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                legend: {
                                    position: 'top',
                                    horizontalAlign: 'center'
                                },
                                grid: {
                                    borderColor: '#e7e7e7'
                                },
                                noData: {
                                    text: 'Tidak ada data penerimaan.'
                                }
                            };

                            // Kosongkan div sebelum render baru
                            $('#chartJumlah').empty();

                            // Buat instance baru
                            chartJumlah = new ApexCharts(document.querySelector('#chartJumlah'), newOptionsJumlah);
                            chartJumlah.render(); // Render chart baru
                            console.log("Chart Jumlah berhasil dibuat ulang.");

                        } catch (e) {
                            console.error("Error saat memproses/membuat ulang Chart Jumlah:", e);
                            $('#chartJumlah').html('<p class="text-center text-danger">Error: ' + e.message + '</p>');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error Jumlah:", textStatus, errorThrown, jqXHR.responseText);
                        $('#chartJumlah').html('<p class="text-center text-danger">Gagal memuat data chart jumlah.</p>');
                    }
                });
            } // Akhir fungsi loadChartJumlahData

            /**
             * Fungsi HANYA untuk memuat data Chart Frekuensi Item Menu (dg Warna Kategori).
             */
            function loadChartFrekuensiData(viewType) {
                if (!chartFrekuensi) {
                    console.error("Instance chartFrekuensi belum siap.");
                    return;
                }
                // Reset state loading (penting untuk hapus kategori lama)
                chartFrekuensi.updateOptions({
                    series: [],
                    xaxis: {
                        categories: [],
                        title: {
                            text: 'Item Menu'
                        }
                    }, // Reset X axis
                    yaxis: {
                        title: {
                            text: 'Jumlah Penyajian'
                        }
                    },
                    noData: {
                        text: 'Memuat data...'
                    }
                });

                $.ajax({
                    url: '../../../php/sekolah/api_get_menu_frequency.php',
                    type: 'GET',
                    data: {
                        view: viewType
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Data Frekuensi Diterima:", JSON.stringify(response, null, 2));
                        try {
                            // Validasi data (API sekarang mengirim 'series' langsung)
                            if (!response || response.status !== 'success' || !response.data || !response.data.series || !Array.isArray(response.data.series) || response.data.series.length === 0 || !response.data.series[0].data || !Array.isArray(response.data.series[0].data)) {
                                throw new Error(response ? (response.message || 'Format data frekuensi salah') : 'Respon frekuensi tidak valid');
                            }

                            console.log("Memperbarui Chart Frekuensi (Warna Kategori)...");

                            // --- PERBAIKAN: Update Series Langsung ---
                            // ApexCharts akan otomatis mengambil label 'x' dari data series untuk xaxis
                            chartFrekuensi.updateOptions({
                                series: response.data.series, // Update data batang (format [{x, y, kategori},...])
                                xaxis: {
                                    // Biarkan kosong, ApexCharts akan mengisinya dari 'x'
                                    categories: [],
                                    title: {
                                        text: 'Item Menu'
                                    },
                                    labels: {
                                        rotate: -45,
                                        trim: true, // Aktifkan trim jika label panjang
                                        style: {
                                            fontSize: '10px'
                                        }
                                    } // Perkecil font jika perlu
                                },
                                // Set ulang teks noData
                                noData: {
                                    text: (response.data.series[0].data.length === 0) ? 'Tidak ada data menu.' : ''
                                }
                            });
                            console.log("Chart Frekuensi berhasil diperbarui.");
                            // --- AKHIR PERBAIKAN ---

                        } catch (e) {
                            console.error("Error saat update Chart Frekuensi:", e);
                            $('#chartFrekuensi').html('<p class="text-center text-danger">Error: ' + e.message + '</p>');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error Frekuensi:", textStatus, errorThrown, jqXHR.responseText);
                        $('#chartFrekuensi').html('<p class="text-center text-danger">Gagal memuat data chart frekuensi.</p>');
                    }
                });
            } // Akhir loadChartFrekuensiData

            // --- Listener untuk Tombol Filter (Tidak Berubah) ---
            $('#btnJumlahBulanan').on('click', function() {
                $(this).addClass('active').siblings().removeClass('active');
                loadChartJumlahData('monthly');
            });
            $('#btnJumlahHarian').on('click', function() {
                $(this).addClass('active').siblings().removeClass('active');
                loadChartJumlahData('daily');
            });
            $('#btnFrekuensiBulanan').on('click', function() {
                $(this).addClass('active').siblings().removeClass('active');
                loadChartFrekuensiData('monthly');
            });
            $('#btnFrekuensiHarian').on('click', function() {
                $(this).addClass('active').siblings().removeClass('active');
                loadChartFrekuensiData('daily');
            });

            // Muat data default (bulanan) untuk KEDUA chart
            loadChartJumlahData('monthly');
            loadChartFrekuensiData('monthly');
        });
    </script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async="async" defer="defer" src="https://buttons.github.io/buttons.js"></script>
</body>

</html>