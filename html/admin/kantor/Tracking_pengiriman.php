<!doctype html>

<?php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../../../index.php');
    exit();
}
include '../../../php/config.php';

$query = "SELECT 
            tb_distribusi.id_distribusi, 
            tb_distribusi.nama_barang, 
            tb_distribusi.jumlah,
            tb_distribusi.jam_berangkat,
            tb_distribusi.jam_tiba,
            tb_distribusi.gps_awal,
            tb_sekolah.lokasi_gps, 
            tb_sekolah.nama_sekolah 
          FROM tb_distribusi
          JOIN tb_sekolah ON tb_distribusi.id_sekolah_tujuan = tb_sekolah.id_sekolah 
          WHERE tb_distribusi.status_pengiriman IN ('0', '1', '2') AND tb_distribusi.tanggal = CURDATE()";

$result = mysqli_query($conn, $query);

$qgetlokasikantor = "SELECT * FROM tb_kantor WHERE id_kantor = '1'";
$resultkasikantor =  mysqli_query($conn, $qgetlokasikantor);
$resultkasikantor = mysqli_fetch_assoc($resultkasikantor);

$lokasi_kantor = $resultkasikantor["lokasi"];

$markers = [];
while ($row = mysqli_fetch_assoc($result)) {
    if (!empty($row['lokasi_gps']) || !empty($row['gps_awal'])) {
        $markers[] = [
            'id'             => $row['id_distribusi'],
            'barang'         => $row['nama_barang'],
            'jumlah'         => $row['jumlah'],
            'sekolah'        => $row['nama_sekolah'],
            'gps_tujuan'     => $row['lokasi_gps'],
            'gps_awal'       => $row['gps_awal'],
            'jam_berangkat'  => $row['jam_berangkat'],
            'jam_tiba'       => $row['jam_tiba']
        ];
    }
}
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

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../../../assets/vendor/js/helpers.js"></script>
    <script src="../../../assets/js/config.js"></script>
    <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>


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
                                                    <h6 class="mb-0">John Doe</h6>
                                                    <small class="text-body-secondary">Admin</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="d-grid px-4 pt-2 pb-1">
                                            <a class="btn btn-danger d-flex" href="javascript:void(0);">
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
                                    <li class="breadcrumb-item active" aria-current="page">Tracking Pengiriman</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row gy-4 flex-grow-1">
                            <div class="col-lg-8 h-100">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Peta Lokasi Pengiriman</h5>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="map h-100" id="mapid" style="min-height: 600px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 h-100">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Ringkasan Pengiriman Hari Ini</h5>
                                    </div>
                                    <div class="table-responsive ">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nama Sekolah</th>
                                                    <th>Berangkat</th>
                                                    <th>Tiba</th>
                                                </tr>
                                            </thead>
                                            <tbody id="delivery-table-body">
                                            </tbody>
                                        </table>
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
        // Inisialisasi peta
        var map = L.map('mapid').setView([-2.5, 118], 5);

        // Ambil data dari PHP ke JS
        var markersData = <?= json_encode($markers) ?>;

        // Tambah layer tile OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Group untuk auto-zoom semua marker
        var markerGroup = L.featureGroup().addTo(map);

        // Variabel untuk nomor urut tabel
        var tableCounter = 1;

        // Loop semua data pengiriman
        markersData.forEach(function(item) {
            var latLngAwal, latLngTujuan;

            if (item.gps_awal) {

                var coordsAwal = item.gps_awal.split(",");
                var latAwal = parseFloat(coordsAwal[0].trim());
                var lonAwal = parseFloat(coordsAwal[1].trim());
                latLngAwal = L.latLng(latAwal, lonAwal);

                var popupAwal = `
                <b>Status: Keberangkatan</b><br>
                <b>Jam Berangkat:</b> ${item.jam_berangkat || 'N/A'}<br>
                <hr class='my-1'>
                <b>Tujuan:</b> ${item.sekolah}<br>
                <b>Barang:</b> ${item.barang} (${item.jumlah})
            `;

                var markerAwal = L.marker(latLngAwal).bindPopup(popupAwal);
                markerAwal.addTo(markerGroup);

            }
            if (item.gps_tujuan) {

                var coordsTujuan = item.gps_tujuan.split(",");
                var latTujuan = parseFloat(coordsTujuan[0].trim());
                var lonTujuan = parseFloat(coordsTujuan[1].trim());
                latLngTujuan = L.latLng(latTujuan, lonTujuan);

                var statusTiba = item.jam_tiba ? `<b>Jam Tiba:</b> ${item.jam_tiba}` : '<b>Status:</b> Masih dalam perjalanan';

                var popupTujuan = `
                <b>Tujuan: ${item.sekolah}</b><br>
                ${statusTiba}<br>
                <hr class='my-1'>
                <b>Barang:</b> ${item.barang} (${item.jumlah})
            `;

                var markerTujuan = L.marker(latLngTujuan).bindPopup(popupTujuan);
                markerTujuan.addTo(markerGroup);

            }
            if (latLngAwal && latLngTujuan) {
                L.Routing.control({
                    waypoints: [latLngAwal, latLngTujuan],
                    show: false,
                    addWaypoints: false,
                    draggableWaypoints: false,
                    fitSelectedRoutes: false,
                    createMarker: function() {
                        return null;
                    },
                    lineOptions: {
                        styles: [{
                            color: 'blue',
                            opacity: 0.7,
                            weight: 5
                        }]
                    }
                }).addTo(map);
            }

            var jamBerangkat = item.jam_berangkat ? item.jam_berangkat.substring(0, 5) : '-';
            var jamTiba = item.jam_tiba ? item.jam_tiba.substring(0, 5) : '-';

            var tableRow = `
            <tr>
                <td>${item.sekolah}</td>
                <td><span class="badge bg-label-info">${jamBerangkat}</span></td>
                <td><span class="badge bg-label-success">${jamTiba}</span></td>
            </tr>
        `;

            $('#delivery-table-body').append(tableRow);

            tableCounter++;
        });

        setTimeout(function() {
            if (markersData.length > 0 && markerGroup.getLayers().length > 0) {
                map.invalidateSize(); 
                map.fitBounds(markerGroup.getBounds().pad(0.1));
            }
        }, 500);
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