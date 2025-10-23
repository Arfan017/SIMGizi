<!doctype html>

<?php
session_name('SIMGiziDistribusi');
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_distribusi') {
    header('Location: ../../../index.php');
    exit();
}
include '../../../php/config.php';

$id_petugas = $_SESSION['user_id'];

$stok_sisa = 0;
$tanggal_hari_ini = date("Y-m-d");
$query_stok = mysqli_query($conn, "SELECT jumlah_sisa FROM tb_stok_harian WHERE tanggal = '$tanggal_hari_ini'");
if (mysqli_num_rows($query_stok) > 0) {
    $data_stok = mysqli_fetch_assoc($query_stok);
    $stok_sisa = $data_stok['jumlah_sisa'];
}

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

    <title>ADMIN DISTRIBUSI | SISTEM INFORMASI MONITORING PEMANTAUAN MAKANAN BERGIZI</title>

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
                    <a href="index.php" class="app-brand-link">
                        <i class="icon-base ri ri-inbox-archive-line icon-32px bg-warning"></i>
                        <span class="app-brand-text demo menu-text fw-semibold ms-2">ADMIN DISTRIBUSI</span>
                    </a>
                </div>

                <ul class="menu-inner py-1">
                    <li class="menu-header mt-7"><span class="menu-header-text">Menu</span></li>

                    <li class="menu-item">
                        <a href="index.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-dashboard-line"></i>
                            <div data-i18n="Basic">Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="input_bahan_makanan.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-inbox-line"></i>
                            <div data-i18n="Basic">Input Bahan Makanan</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="input_stok_harian.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-inbox-archive-line"></i>
                            <div data-i18n="Basic">Input Porsi harian</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="input_distribusi.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-git-repository-line"></i>
                            <div data-i18n="Basic">Input Distribusi</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="data_distribusi.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-table-line"></i>
                            <div data-i18n="Basic">Data Distribusi</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="evaluasi.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-file-check-line"></i>
                            <div data-i18n="Basic">Evaluasi</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="riwayat_distribusi.php" class="menu-link">
                            <i class="menu-icon icon-base ri ri-history-line"></i>
                            <div data-i18n="Basic">Riwayat Distribusi</div>
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
                                        <div class="alert alert-warning d-flex justify-content-between align-items-center" role="alert">
                                            <h5 class="alert-heading mb-0">Sisa Porsi Hari Ini:</h5>
                                            <span class="badge bg-warning fs-5"><?php echo number_format($stok_sisa); ?></span>
                                        </div>
                                        <hr>
                                        <form id="formDistribusi" enctype="multipart/form-data" method="POST" action="../../php/distribusi/crud_distribusi.php">
                                            <div class="mb-3">
                                                <input type="hidden" name="id_petugas_distribusi" value="<?php echo $id_petugas ?>">
                                                <input type="hidden" name="nama_barang" value="Makanan">

                                                <label for="sekolah" class="form-label mb-1">Sekolah Tujuan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white"><i class="ri ri-school-line"></i></span>
                                                    <select class="form-select" name="sekolah" id="sekolah" required>
                                                        <option value="">Pilih Sekolah</option>
                                                        <?php
                                                        // pastikan query SELECT juga ambil kolom lokasi_gps
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='" . $row['id_sekolah'] . "' data-lokasi='" . $row['lokasi_gps'] . "'>" . $row['nama_sekolah'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="jumlah" class="form-label mb-1">Jumlah Dikirim</label>
                                                <div class="input-group ">
                                                    <span class="input-group-text bg-white"><i class="ri ri-restaurant-2-line"></i></span>
                                                    <input type="number" name="jumlah" class="form-control" id="jumlah" min="1" max="<?php echo $stok_sisa; ?>" required placeholder="Jumlah">
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
        document.addEventListener("DOMContentLoaded", function() {
            var sekolahSelect = document.getElementById("sekolah");
            var lokasiInput = document.getElementById("lokasi");

            sekolahSelect.addEventListener("change", function() {
                var selectedOption = this.options[this.selectedIndex];
                var lokasi = selectedOption.getAttribute("data-lokasi");

                if (lokasi) {
                    lokasiInput.value = lokasi; // set lokasi otomatis
                } else {
                    lokasiInput.value = ""; // kosongkan jika tidak ada
                }
            });
        });

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

                        // Tampilkan data baru di panel
                        $('#panelDistribusiBaru').html(`
                            <div class="mb-3">
                                <span class="fw-bold">Sekolah Tujuan:</span> ${response.data.sekolah}<br>
                                <span class="fw-bold">Jumlah Dikirim:</span> ${response.data.jumlah}<br>
                                <span class="fw-bold">Tanggal:</span> ${response.data.tanggal}<br>
                                <span class="fw-bold">Jam:</span> ${response.data.jam}<br>
                                <span class="fw-bold">Lokasi GPS:</span> ${response.data.lokasi}<br>
                            </div>
                            <button type="button" class="btn btn-success w-100" id="btnKirimDistribusi" data-id="${response.data.id}">
                                <i class="ri-send-plane-2-line"></i> Kirim Distribusi
                            </button>
                        `);

                        // Optional: setTimeout untuk reload jika ingin
                        // setTimeout(function() { location.reload(); }, 1500);
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