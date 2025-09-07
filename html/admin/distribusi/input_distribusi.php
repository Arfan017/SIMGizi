<!doctype html>

<?php
include '../../../php/config.php';

$query = "SELECT * FROM tb_sekolah";
$result = mysqli_query($conn, $query);

?>


<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="../../../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title>ADMIN SEKOLAH | SISTEM INFORMASI MONITORING PEMANTAUAN MAKANAN BERGIZI</title>

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
    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999;">
        <div id="distribusiToastSuccess" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ri-checkbox-circle-line me-1"></i> Data distribusi berhasil disimpan!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        <div id="distribusiToastError" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ri-error-warning-line me-1"></i> <span id="distribusiErrorMsg"></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu">
                <div class="app-brand demo">
                    <a href="index.html" class="app-brand-link">
                        <i class="icon-base ri ri-inbox-archive-line icon-32px bg-warning"></i>
                        <span class="app-brand-text demo menu-text fw-semibold ms-2">ADMIN DISTRIBUSI</span>
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
                        <a href="input_distribusi.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-git-repository-line"></i>
                            <div data-i18n="Basic">Input Distribusi</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="riwayat_distribusi.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-table-line"></i>
                            <div data-i18n="Basic">Data Distribusi</div>
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
                    <div class="container-xxl flex-grow-1 container-p-y d-flex flex-column h-100 pb-0">
                        <div class="mb-4">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Input Distribusi</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row flex-grow-1">
                            <!-- Form Input -->
                            <div class="col-12 d-flex flex-column h-100">
                                <div class="card overflow-hidden flex-grow-1 d-flex flex-column">
                                    <div class="card-head bg-warning card-img-top">
                                        <h5 class="m-5 text-center text-white fw-bold ">Input Distribusi Makanan</h5>
                                        <!-- <hr class="mt-0"> -->
                                    </div>
                                    <div class="card-body">
                                        <form id="formDistribusi" enctype="multipart/form-data" method="POST" action="../../php/distribusi/crud_distribusi.php">
                                            <div class="mb-3">
                                                <input type="hidden" name="id_petugas_distribusi" value="1">
                                                <input type="hidden" name="nama_barang" value="Makanan">
                                                <label for="sekolah" class="form-label mb-1">Sekolah Tujuan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white"><i class="ri ri-school-line"></i></span>
                                                    <select class="form-select" name="sekolah" id="sekolah" required>
                                                        <option value="">Pilih Sekolah</option>
                                                        <?php
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='" . $row['nama_sekolah'] . "'>" . $row['nama_sekolah'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="jumlah" class="form-label mb-1">Jumlah Dikirim</label>
                                                <div class="input-group ">
                                                    <span class="input-group-text bg-white"><i class="ri ri-restaurant-2-line"></i></span>
                                                    <input type="number" name="jumlah" class="form-control" id="jumlah" min="1" required placeholder="Jumlah">
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <label for="tanggal" class="form-label mb-1">Tanggal</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-white"><i class="ri ri-calendar-line"></i></span>
                                                        <input type="date" name="tanggal" class="form-control" id="tanggal" required>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="jam" class="form-label mb-1">Jam</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-white"><i class="ri ri-time-line"></i></span>
                                                        <input type="time" name="jam" class="form-control" id="jam" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="foto" class="form-label mb-1">Foto Makanan</label>
                                                <input type="file" name="foto" class="form-control" id="foto" accept="image/*" required>
                                                <div id="preview" class="mt-2"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label mb-1">Lokasi GPS</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white"><i class="ri ri-map-pin-line"></i></span>
                                                    <input type="text" name="lokasi" class="form-control" id="lokasi" readonly placeholder="Klik tombol untuk ambil lokasi">
                                                    <button type="button" class="btn btn-outline-primary" onclick="getLocation()">
                                                        <i class="ri ri-crosshair-line"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-outline-warning w-100 mt-2">Simpan Distribusi</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- / Form Input -->

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
        $('#formDistribusi').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: '../../../php/distribusi/crud_distribusi.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const toast = new bootstrap.Toast(document.getElementById('distribusiToastSuccess'));
                        toast.show();
                        $('#formDistribusi')[0].reset();
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        $('#distribusiErrorMsg').text(response.message);
                        const toast = new bootstrap.Toast(document.getElementById('distribusiToastError'));
                        toast.show();
                    }
                },
                error: function() {
                    var debugStr = '';
                    formData.forEach(function(value, key) {
                        debugStr += key + ': ' + value + '\n';
                    });
                    $('#distribusiErrorMsg').text('Gagal menyimpan data.\n' + debugStr);
                    const toast = new bootstrap.Toast(document.getElementById('distribusiToastError'));
                    toast.show();
                }
            });
        });


        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    $('#lokasi').val(position.coords.latitude + ',' + position.coords.longitude);
                }, function() {
                    alert('Gagal mendapatkan lokasi!');
                });
            } else {
                alert('Browser tidak mendukung geolokasi!');
            }
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