<!doctype html>

<?php
include '../../../php/config.php';

// Query to get account data
$query = "SELECT tb_distribusi.* , tb_users.nama FROM tb_distribusi JOIN tb_users ON tb_distribusi.id_petugas_distribusi = tb_users.id_users ORDER BY tb_distribusi.tanggal ASC";
$result = mysqli_query($conn, $query);

$q_sekolah = "SELECT * FROM tb_sekolah";
$result_sekolah = mysqli_query($conn, $q_sekolah);
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
                                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Monitoring</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row gy-6 flex-grow-1">
                            <!-- Data Tables -->
                            <div class="col-12 d-flex flex-column h-100">
                                <div class="card overflow-hidden flex-grow-1 d-flex flex-column">
                                    <div class="card-header bg-transparent border-0 pt-4 pb-0 sticky-top bg-white">
                                        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap">

                                            <h4 class="card-title text-info mb-0 fw-bold">Monitoring</h4>
                                            <div class="d-flex align-items-center justify-content-end flex-nowrap gap-2">
                                                <!-- <button class="btn btn-outline-info w-50" type="button">
                                                    <span class="icon-base ri ri-printer-line icon-16px me-1_5"></span>Cetak
                                                </button>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text bg-white border-end-0">
                                                        <i class="icon-base ri ri-search-line text-muted"></i>
                                                    </span>
                                                    <input type="text" class="form-control border-start-0"
                                                        placeholder="Cari monitoring...">
                                                </div> -->
                                                <button class="btn btn-outline-info w-100" type="button" data-bs-toggle="modal" data-bs-target="#ModalFilterData">
                                                    <span class="icon-base ri ri-filter-line icon-16px me-1_5"></span>Filter
                                                </button>
                                            </div>
                                        </div>
                                        <hr class="mt-3 mb-3">
                                    </div>

                                    <div class="table-responsive overflow-auto flex-grow-1" style="height: calc(570px - 90px);">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-truncate">Tanggal</th>
                                                    <th class="text-truncate">Nama Sekolah</th>
                                                    <th class="text-truncate">Nama Petugas</th>
                                                    <th class="text-truncate">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                                        <tr>
                                                            <td class="text-truncate"><?php echo $row['tanggal']; ?></td>
                                                            <td class="text-truncate"><?php echo $row['tujuan']; ?></td>
                                                            <td class="text-truncate">
                                                                <span><?php echo $row['nama']; ?></span>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $status_badge = '';
                                                                if ($row['status_pengiriman'] === '0') {
                                                                    $status_badge = 'bg-label-warning';
                                                                    $status = 'Belum Dikirim';
                                                                } elseif ($row['status_pengiriman'] === '1') {
                                                                    $status_badge = 'bg-label-info';
                                                                    $status = 'Dikirim';
                                                                } elseif ($row['status_pengiriman'] === '2') {
                                                                    $status_badge = 'bg-label-success';
                                                                    $status = 'Diterima';
                                                                }

                                                                ?>
                                                                <span class="badge <?php echo $status_badge; ?> rounded-pill"><?php echo $status; ?></span>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            <!--/ Data Tables -->
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
                                                    <input type="date" id="emailBasic" class="form-control"
                                                        placeholder="Masukkan Nama" />
                                                    <label for="emailBasic">Tanggal Mulai</label>
                                                </div>
                                            </div>
                                            <div class="col mb-2">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="date" id="emailBasic" class="form-control" placeholder="Masukkan Nama" />
                                                    <label for="emailBasic">Tanggal Akhir</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-4">
                                            <div class="col mb-2">
                                                <div class="form-floating form-floating-outline">
                                                    <select class="form-select" name="sekolah" id="sekolah" required>
                                                        <option value="">Pilih Sekolah</option>
                                                        <?php
                                                        while ($row = mysqli_fetch_assoc($result_sekolah)) {
                                                            echo "<option value='" . $row['nama_sekolah'] . "'>" . $row['nama_sekolah'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col mb-2">
                                                <div class="form-floating form-floating-outline">
                                                    <select class="form-select" id="status_pengiriman">
                                                        <option value="">Status Pengiriman</option>
                                                        <option value="1">Diterima</option>
                                                        <option value="2">Dikirima</option>
                                                        <option value="3">Belum Dikirima</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal">
                                            <span class="icon-base ri ri-close-line icon-16px me-1_5"></span>Tutup
                                        </button>
                                        <button type="button" class="btn btn-outline-success">
                                            <span
                                                class="icon-base ri ri-filter-line icon-16px me-1_5"></span>Filter</button>
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