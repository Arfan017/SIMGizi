<?php
session_name('SIMGiziDistribusi');
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_distribusi') {
    header('Location: ../../../index.php');
    exit();
}
include '../../../php/config.php';
?>
<!doctype html>
<html lang="en" ...>

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
                    <i class="ri-checkbox-circle-line me-1"></i> Data menu makanan berhasil disimpan!
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
                <div class="content-wrapper d-flex flex-column h-100">
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
                                        <h5 class="m-5 text-center text-white fw-bold ">Input Bahan Makanan</h5>
                                        <!-- <hr class="mt-0"> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="row h-100">
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <h5 class="card-header">Tambah Bahan Baru</h5>
                                                    <div class="card-body">
                                                        <form id="formTambahBahan">
                                                            <div class="mb-3">
                                                                <label for="nama_bahan" class="form-label">Nama Bahan</label>
                                                                <input type="text" class="form-control" id="nama_bahan" name="nama_bahan" required placeholder="Contoh: Nasi Putih">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="kategori" class="form-label">Kategori</label>
                                                                <select class="form-select" id="kategori" name="kategori" required>
                                                                    <option value="">Pilih Kategori</option>
                                                                    <option value="KH">KH (Karbohidrat)</option>
                                                                    <option value="Protein">Protein</option>
                                                                    <option value="Sayur">Sayur</option>
                                                                    <option value="Buah">Buah</option>
                                                                </select>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Tambah Bahan</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="card h-100">
                                                    <h5 class="card-header">Daftar Bahan Makanan</h5>
                                                    <div class="table-responsive text-nowrap flex-grow-1" style="max-height: 550px; overflow-y: auto;">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Nama Bahan</th>
                                                                    <th>Kategori</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tabelBahanBody">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- / Form Input -->

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
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <script src="../../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../../assets/vendor/js/menu.js"></script>
    <script src="../../../assets/js/main.js"></script>

    <script>
        $(document).ready(function() {
            // Fungsi untuk memuat dan menampilkan data bahan
            function loadBahanMakanan() {
                var tbody = $('#tabelBahanBody');
                tbody.html('<tr><td colspan="3" class="text-center">Memuat data...</td></tr>'); // Tampilkan loading

                $.ajax({
                    url: '../../../php/distribusi/api_crud_bahan.php', // Panggil API GET
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        tbody.empty(); // Kosongkan tabel
                        if (response.status === 'success' && response.data && response.data.length > 0) {
                            $.each(response.data, function(index, item) {
                                var row = '<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td>' + item.nama_bahan + '</td>' +
                                    '<td>' + item.kategori + '</td>' +
                                    // '<td><button class="btn btn-sm btn-danger btn-hapus" data-id="' + item.id_bahan + '">Hapus</button></td>' + // Opsional
                                    '</tr>';
                                tbody.append(row);
                            });
                        } else if (response.status === 'success') {
                            tbody.html('<tr><td colspan="3" class="text-center">Belum ada data bahan makanan.</td></tr>');
                        } else {
                            tbody.html('<tr><td colspan="3" class="text-center text-danger">Gagal memuat data: ' + (response.message || '') + '</td></tr>');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown, jqXHR.responseText);
                        tbody.html('<tr><td colspan="3" class="text-center text-danger">Gagal memuat data. Periksa koneksi atau API.</td></tr>');
                    }
                });
            }

            // Handler untuk submit form tambah bahan
            $('#formTambahBahan').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize(); // Ambil data form

                $.ajax({
                    url: '../../../php/distribusi/api_crud_bahan.php', // Panggil API POST
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message || 'Bahan berhasil ditambahkan!');
                            $('#formTambahBahan')[0].reset(); // Kosongkan form
                            loadBahanMakanan(); // Muat ulang tabel
                        } else {
                            alert('Gagal: ' + (response.message || 'Terjadi kesalahan.'));
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown, jqXHR.responseText);
                        alert('Gagal menambahkan bahan. Periksa F12 Console.');
                    }
                });
            });

            // Opsional: Handler untuk tombol hapus (jika ditambahkan)
            // $('#tabelBahanBody').on('click', '.btn-hapus', function() {
            //     var idBahan = $(this).data('id');
            //     if (confirm('Yakin ingin menghapus bahan ini?')) {
            //         // Buat AJAX request DELETE ke api_crud_bahan.php (perlu ditambahkan logikanya di PHP)
            //         $.ajax({
            //             url: '../../../php/api/api_crud_bahan.php',
            //             type: 'DELETE', // Atau POST dengan parameter _method=DELETE
            //             data: { id_bahan: idBahan },
            //             dataType: 'json',
            //             success: function(response) { ... loadBahanMakanan(); ... },
            //             error: function() { ... }
            //         });
            //     }
            // });

            // Muat data bahan saat halaman pertama kali dibuka
            loadBahanMakanan();
        });
    </script>
</body>

</html>