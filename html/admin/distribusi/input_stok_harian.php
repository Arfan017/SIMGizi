<!doctype html>

<?php
session_name('SIMGiziDistribusi');
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_distribusi') {
    header('Location: ../../../index.php');
    exit();
}
include '../../../php/config.php';

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
                                        <h5 class="m-5 text-center text-white fw-bold ">Input Jumlah Porsi Harian</h5>
                                        <!-- <hr class="mt-0"> -->
                                    </div>
                                    <div class="card-body">
                                        <form id="formMenuStokHarian" method="POST">

                                            <div class="mb-4">
                                                <label for="tanggal_stok" class="form-label fs-5 fw-semibold">Tanggal</label>
                                                <input type="date" class="form-control form-control-lg" id="tanggal_stok" name="tanggal_stok" required>
                                            </div>

                                            <hr class="my-4">

                                            <h5 class="mb-3 fw-semibold">Pilih Menu Hari Ini</h5>
                                            <div class="p-3 border rounded">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="menu_kh" class="form-label"><span class="text-danger">*</span> Karbohidrat (KH)</label>
                                                            <select class="form-select" id="menu_kh" name="menu_kh" required>
                                                                <option value="">Memuat...</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="menu_protein1" class="form-label"><span class="text-danger">*</span> Protein 1</label>
                                                            <select class="form-select" id="menu_protein1" name="menu_protein1" required>
                                                                <option value="">Memuat...</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="menu_protein2" class="form-label">Protein 2 <small class="text-muted">(Opsional)</small></label>
                                                            <select class="form-select" id="menu_protein2" name="menu_protein2">
                                                                <option value="">- Pilih Protein 2 -</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="menu_sayur" class="form-label"><span class="text-danger">*</span> Sayur</label>
                                                            <select class="form-select" id="menu_sayur" name="menu_sayur" required>
                                                                <option value="">Memuat...</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="menu_buah" class="form-label">Buah <small class="text-muted">(Opsional)</small></label>
                                                            <select class="form-select" id="menu_buah" name="menu_buah">
                                                                <option value="">- Pilih Buah -</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="menu_tambahan" class="form-label">Tambahan <small class="text-muted">(Opsional)</small></label>
                                                            <input type="text" class="form-control" id="menu_tambahan" name="menu_tambahan" placeholder="Contoh: Susu 125ml">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="my-4">

                                            <div class="mb-4">
                                                <label for="jumlah_total" class="form-label fs-5 fw-semibold"><span class="text-danger">*</span> Jumlah Total Porsi</label>
                                                <input type="number" class="form-control form-control-lg" id="jumlah_total" name="jumlah_total" min="1" required placeholder="Contoh: 2000">
                                            </div>

                                            <button type="submit" class="btn btn-warning w-100 btn-lg">Simpan Menu & Stok</button>
                                        </form>
                                    </div>
                                    <!-- <div class="card-body">
                                        <div class="card-body">
                                            <form id="formStokHarian" action="../../../php/distribusi/crud_stok.php" method="POST">
                                                <div class="mb-3">
                                                    <label for="tanggal_stok" class="form-label">Tanggal</label>
                                                    <input type="date" class="form-control" id="tanggal_stok" name="tanggal_stok" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jumlah_total" class="form-label">Jumlah Total Porsi</label>
                                                    <input type="number" class="form-control" id="jumlah_total" name="jumlah_total" min="1" required placeholder="Contoh: 2000">
                                                </div>
                                                <button type="submit" class="btn btn-outline-warning w-100">Simpan Stok</button>
                                            </form>
                                        </div>
                                    </div> -->
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
        $(document).ready(function() {
            // Fungsi untuk mengisi dropdown
            function populateDropdown(selector, items, placeholder) {
                var dropdown = $(selector);
                dropdown.empty(); // Kosongkan dulu
                if (placeholder) {
                    dropdown.append($('<option>', {
                        value: '',
                        text: placeholder
                    }));
                }
                if (items && items.length > 0) {
                    $.each(items, function(i, item) {
                        dropdown.append($('<option>', {
                            value: item.id,
                            text: item.nama
                        }));
                    });
                } else {
                    dropdown.append($('<option>', {
                        value: '',
                        text: 'Tidak ada pilihan'
                    }));
                    if (selector !== '#menu_protein2' && selector !== '#menu_buah') { // Kecuali opsional
                        dropdown.prop('required', false); // Nonaktifkan required jika tidak ada pilihan
                    }
                }
            }

            // Ambil data bahan makanan saat halaman dimuat
            $.getJSON('../../../php/distribusi/api_get_bahan_makanan.php', function(response) {
                if (response.status === 'success' && response.options) {
                    populateDropdown('#menu_kh', response.options.KH, '- Pilih KH -');
                    populateDropdown('#menu_protein1', response.options.Protein, '- Pilih Protein 1 -');
                    populateDropdown('#menu_protein2', response.options.Protein, '- Pilih Protein 2 (Opsional) -');
                    populateDropdown('#menu_sayur', response.options.Sayur, '- Pilih Sayur -');
                    populateDropdown('#menu_buah', response.options.Buah, '- Pilih Buah (Opsional) -');
                } else {
                    alert('Gagal memuat pilihan menu: ' + (response.message || 'Format data salah'));
                    // Nonaktifkan form atau tampilkan pesan error
                }
            }).fail(function() {
                alert('Gagal menghubungi server untuk mengambil pilihan menu.');
                // Nonaktifkan form atau tampilkan pesan error
            });

            // Handler untuk submit form
            $('#formMenuStokHarian').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '../../../php/distribusi/api_save_menu_stok.php', // Panggil API baru
                    type: 'POST',
                    data: $(this).serialize(), // Kirim semua data form
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message || 'Menu dan Stok berhasil disimpan!');
                            $('#formMenuStokHarian')[0].reset();
                            // Reset dropdowns ke placeholder awal
                            $('#menu_kh').val('');
                            $('#menu_protein1').val('');
                            $('#menu_protein2').val('');
                            $('#menu_sayur').val('');
                            $('#menu_buah').val('');
                        } else {
                            alert('Gagal: ' + (response.message || 'Terjadi kesalahan.'));
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                        console.error("Response Text:", jqXHR.responseText);
                        alert('Terjadi kesalahan saat menyimpan. Gagal menghubungi server.');
                    }
                });
            });
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