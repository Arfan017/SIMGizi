<!doctype html>

<?php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../../../index.php');
    exit();
}
include '../../../php/config.php';

// Query to get evaluation report data
$query = "SELECT tb_distribusi.*, tb_sekolah.nama_sekolah AS sekolah_tujuan, tb_konfirmasi_distribusi.gambar FROM tb_distribusi 
          JOIN tb_sekolah ON tb_distribusi.id_sekolah_tujuan = tb_sekolah.id_sekolah
          JOIN tb_konfirmasi_distribusi ON tb_distribusi.id_distribusi = tb_konfirmasi_distribusi.id_distribusi
          WHERE status_evaluasi = '0' AND status_konfirmasi = '1' ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query);

$query_riwayat_evaluasi = "SELECT tb_evaluasi.*, tb_distribusi.id_sekolah_tujuan, tb_distribusi.tanggal, tb_sekolah.nama_sekolah AS sekolah_tujuan, tb_konfirmasi_distribusi.gambar FROM tb_evaluasi 
                           JOIN tb_distribusi ON tb_evaluasi.id_distribusi = tb_distribusi.id_distribusi 
                           JOIN tb_sekolah ON tb_distribusi.id_sekolah_tujuan = tb_sekolah.id_sekolah
                           JOIN tb_konfirmasi_distribusi ON tb_evaluasi.id_distribusi = tb_konfirmasi_distribusi.id_distribusi 
                           ORDER BY tb_evaluasi.id_evaluasi DESC";

$result_riwayat_evaluasi = mysqli_query($conn, $query_riwayat_evaluasi);

$q_sekolah1 = "SELECT * FROM tb_sekolah";
$result_sekolah1 = mysqli_query($conn, $q_sekolah1);

$q_sekolah2 = "SELECT * FROM tb_sekolah";
$result_sekolah2 = mysqli_query($conn, $q_sekolah2);
?>

<html
    lang="en"
    class="layout-menu-fixed layout-compact"
    data-assets-path="../../../assets/"
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

    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999;">
        <div id="evalToastSuccess" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ri-checkbox-circle-line me-1"></i> Evaluasi berhasil disimpan!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        <div id="evalToastError" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ri-error-warning-line me-1"></i> <span id="evalErrorMsg"></span>
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
                                    <li class="breadcrumb-item active" aria-current="page">Laporan Evaluasi</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row gy-6 flex-grow-1">
                            <!-- Data Tables -->
                            <div class="col-12">
                                <div class="nav-align-top nav-tabs-shadow h-100">
                                    <ul class="nav nav-tabs nav-fill " role="tablist">
                                        <li class="nav-item">
                                            <button
                                                type="button "
                                                class="nav-link active"
                                                role="tab"
                                                data-bs-toggle="tab"
                                                data-bs-target="#navs-justified-laporan-evaluasi"
                                                aria-controls="navs-justified-laporan-evaluasi"
                                                aria-selected="true">
                                                <span class="d-none d-sm-inline-flex align-items-center">
                                                    <h5 class="mb-0 text-info">Evaluasi</h5>
                                                </span>
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button
                                                type="button"
                                                class="nav-link"
                                                role="tab"
                                                data-bs-toggle="tab"
                                                data-bs-target="#navs-justified-riwayat-evaluasi"
                                                aria-controls="navs-justified-riwayat-evaluasi"
                                                aria-selected="false">
                                                <span class="d-none d-sm-inline-flex align-items-center ">
                                                    <h5 class="mb-0 text-info">Riwayat Evaluasi</h5>
                                                </span>
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content h-100">
                                        <div class="tab-pane fade show active" id="navs-justified-laporan-evaluasi" role="tabpanel">
                                            <div class="d-flex align-items-center justify-content-end flex-nowrap gap-2">
                                                <div class="form-floating form-floating-outline flex-grow-1">
                                                    <select class="form-select" name="sekolah" id="filterSekolahEvaluasi" required>
                                                        <option value="">Pilih Sekolah</option>
                                                        <?php
                                                        while ($row = mysqli_fetch_assoc($result_sekolah1)) {
                                                            echo "<option value='" . $row['id_sekolah'] . "'>" . $row['nama_sekolah'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-floating form-floating-outline flex-grow-1">
                                                    <input type="date" id="filterTanggalAwalEvaluasi" class="form-control" placeholder="Tanggal Awal" />
                                                    <label for="filterTanggalAwalEvaluasi">Tanggal Awal</label>
                                                </div>
                                                <div class="form-floating form-floating-outline flex-grow-1">
                                                    <input type="date" id="filterTanggalAkhirEvaluasi" class="form-control" placeholder="Tanggal Akhir" />
                                                    <label for="filterTanggalAkhirEvaluasi">Tanggal Akhir</label>
                                                </div>
                                                <div class="">
                                                    <button type="button" class="btn btn-outline-info" id="btnFilterEvaluasi">
                                                        <span class="icon-base ri ri-filter-line icon-16px me-1_5"></span>Filter
                                                    </button>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="card-body p-0">
                                                <div class="overflow-auto">
                                                    <div class="p-0">
                                                        <?php
                                                        if (mysqli_num_rows($result) > 0) {
                                                            while ($data = mysqli_fetch_assoc($result)) {
                                                                // Display each evaluation report
                                                        ?>
                                                                <div class="col-12">
                                                                    <div class="card p-3 shadow-sm bg-transparent border border-info">
                                                                        <div class="row g-0">
                                                                            <!-- Bagian Kiri: Foto -->
                                                                            <div class="col-md-4 d-flex flex-column align-items-center justify-content-center border-end border-info">
                                                                                <img src="../../../uploads/<?php echo $data["gambar"] ?>"
                                                                                    alt="Foto Distribusi"
                                                                                    class="rounded mb-2 preview-foto-btn"
                                                                                    style="width:120px; height:120px; object-fit:cover; cursor:pointer;"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#modalPreviewFoto"
                                                                                    data-foto="../../../uploads/<?php echo $data["gambar"] ?>">
                                                                            </div>
                                                                            <!-- Bagian Kanan: Data & Form Evaluasi -->
                                                                            <div class="col-md-8 ps-4">
                                                                                <div class="mb-2">
                                                                                    <span class="fw-bold">Sekolah Tujuan:</span> <?= $data["sekolah_tujuan"] ?><br>
                                                                                    <span class="fw-bold">Tanggal:</span> <?= $data["tanggal"] ?><br>
                                                                                    <span class="fw-bold">ID Distribusi:</span> <?= $data["id_distribusi"] ?><br>
                                                                                </div>
                                                                                <form class="formEvaluasi" method="POST" action="../../../php/kantor/proses_evaluasi.php">
                                                                                    <input type="hidden" name="id_distribusi" value="<?= $data["id_distribusi"] ?>">
                                                                                    <div class="row g-2 align-items-center">
                                                                                        <div class="col-md-4">
                                                                                            <select class="form-select form-select-sm" name="status_distribusi">
                                                                                                <option value="">Status Distribusi</option>
                                                                                                <option value="1">Baik</option>
                                                                                                <option value="2">Kurang Baik</option>
                                                                                                <option value="3">Tidak Baik</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-5">
                                                                                            <input type="text" class="form-control form-control-sm" name="catatan" placeholder="Catatan Evaluasi">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <button type="submit" class="btn btn-outline-success w-100">Evaluasi</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                </div>
                                                        <?php
                                                            }
                                                        } else {
                                                            echo "<div class='text-muted'>Tidak ada laporan evaluasi.</div>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="navs-justified-riwayat-evaluasi" role="tabpanel">
                                            <div class="d-flex align-items-center justify-content-end flex-nowrap gap-2">
                                                <div class="form-floating form-floating-outline flex-grow-1">
                                                    <select class="form-select" name="sekolah" id="filterSekolahRiwayat" required>
                                                        <option value="">Pilih Sekolah</option>
                                                        <?php
                                                        while ($row = mysqli_fetch_assoc($result_sekolah2)) {
                                                            echo "<option value='" . $row['id_sekolah'] . "'>" . $row['nama_sekolah'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-floating form-floating-outline flex-grow-1">
                                                    <input type="date" id="filterTanggalAwalRiwayat" class="form-control" placeholder="Tanggal Awal" />
                                                    <label for="filterTanggalAwalRiwayat">Tanggal Awal</label>
                                                </div>
                                                <div class="form-floating form-floating-outline flex-grow-1">
                                                    <input type="date" id="filterTanggalAkhirRiwayat" class="form-control" placeholder="Tanggal Akhir" />
                                                    <label for="filterTanggalAkhirRiwayat">Tanggal Akhir</label>
                                                </div>
                                                <div class="">
                                                    <button type="button" class="btn btn-outline-info" id="btnFilterRiwayat">
                                                        <span class="icon-base ri ri-filter-line icon-16px me-1_5"></span>Filter
                                                    </button>
                                                </div>
                                                <div class="">
                                                    <button class="btn btn-outline-info w-100" type="button" onclick="cetakLaporan()">
                                                        <span class="icon-base ri ri-printer-line icon-16px me-1_5"></span>Cetak
                                                    </button>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="card-body p-0">
                                                <div class="overflow-auto">
                                                    <div class="p-3">
                                                        <?php if (mysqli_num_rows($result_riwayat_evaluasi) > 0): ?>
                                                            <div class="row g-3">
                                                                <?php while ($data = mysqli_fetch_assoc($result_riwayat_evaluasi)): ?>
                                                                    <?php
                                                                    if ($data["status_distribusi"] == 1) {
                                                                        $data["status_distribusi"] = "Baik";
                                                                        $badge = "success";
                                                                    } elseif ($data["status_distribusi"] == 2) {
                                                                        $data["status_distribusi"] = "Kurang Baik";
                                                                        $badge = "warning";
                                                                    } elseif ($data["status_distribusi"] == 3) {
                                                                        $data["status_distribusi"] = "Tidak Baik";
                                                                        $badge = "danger";
                                                                    }
                                                                    ?>
                                                                    <div class="col-md-6 col-lg-4">
                                                                        <div class="card shadow-sm border-0 rounded-3 h-100">
                                                                            <img src="../../../uploads/<?php echo $data["gambar"] ?>"
                                                                                class="card-img-top"
                                                                                alt="Foto Distribusi"
                                                                                style="object-fit:cover; height:200px;">
                                                                            <div class="card-body">
                                                                                <h5 class="card-title text-primary mb-2"><?= $data["sekolah_tujuan"] ?></h5>
                                                                                <p class="card-text mb-1">
                                                                                    <strong>Tanggal:</strong> <?= $data["tanggal"] ?>
                                                                                </p>
                                                                                <p class="card-text mb-1">
                                                                                    <strong>Status Distribusi:</strong>
                                                                                    <span class="badge bg-<?= $badge ?>"><?= $data["status_distribusi"] ?></span>
                                                                                </p>
                                                                                <p class="card-text mb-1">
                                                                                    <strong>Catatan Evaluasi:</strong><br>
                                                                                    <?= $data["catatan"] ?>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endwhile; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="text-muted">Tidak ada laporan evaluasi.</div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Preview Foto (letakkan di luar loop, satu saja) -->
                    <div class="modal fade" id="modalPreviewFoto" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Preview Foto Distribusi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="previewFotoImg" src="" alt="Foto Distribusi" class="img-fluid rounded">
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
        $(document).on('click', '.preview-foto-btn', function() {
            var fotoSrc = $(this).data('foto');
            $('#previewFotoImg').attr('src', fotoSrc);
        });

        $(document).on('submit', '.formEvaluasi', function(e) {
            e.preventDefault();
            var form = $(this);

            // Validasi form
            var status = form.find('[name="status_distribusi"]').val();
            var catatan = form.find('[name="catatan"]').val();

            if (!status) {
                $('#evalErrorMsg').text('Status distribusi wajib dipilih!');
                const toast = new bootstrap.Toast(document.getElementById('evalToastError'));
                toast.show();
                return;
            }
            if (!catatan) {
                $('#evalErrorMsg').text('Catatan evaluasi wajib diisi!');
                const toast = new bootstrap.Toast(document.getElementById('evalToastError'));
                toast.show();
                return;
            }

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const toast = new bootstrap.Toast(document.getElementById('evalToastSuccess'));
                        toast.show();
                        setTimeout(function() {
                            location.reload();
                        }, 1200);
                    } else {
                        $('#evalErrorMsg').text(response.message || 'Gagal menyimpan evaluasi!');
                        const toast = new bootstrap.Toast(document.getElementById('evalToastError'));
                        toast.show();
                    }
                },
                error: function() {
                    $('#evalErrorMsg').text('Tidak dapat terhubung ke server.');
                    const toast = new bootstrap.Toast(document.getElementById('evalToastError'));
                    toast.show();
                }
            });
        });

        $('#btnFilterEvaluasi').on('click', function() {
            var sekolah = $('#filterSekolahEvaluasi').val();
            var tglAwal = $('#filterTanggalAwalEvaluasi').val();
            var tglAkhir = $('#filterTanggalAkhirEvaluasi').val();

            $.ajax({
                url: '../../../php/kantor/filter_evaluasi.php',
                type: 'POST',
                data: {
                    sekolah: sekolah,
                    tanggal_awal: tglAwal,
                    tanggal_akhir: tglAkhir
                },
                dataType: 'json',
                success: function(response) {
                    var html = '';
                    if (response.length > 0) {
                        $.each(response, function(i, data) {
                            html += `<div class="col-12">
                                        <div class="card p-3 shadow-sm border">
                                            <div class="row g-0">
                                                <div class="col-md-4 d-flex flex-column align-items-center justify-content-center border-end">
                                                    <img src="../../../uploads/${data.gambar}"
                                                        alt="Foto Distribusi"
                                                        class="rounded mb-2 preview-foto-btn"
                                                        style="width:120px; height:120px; object-fit:cover; cursor:pointer;"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalPreviewFoto"
                                                        data-foto="../../../uploads/${data.gambar}">
                                                </div>
                                                <div class="col-md-8 ps-4">
                                                    <div class="mb-2">
                                                        <span class="fw-bold">Sekolah Tujuan:</span> ${data.sekolah_tujuan}<br>
                                                        <span class="fw-bold">Tanggal:</span> ${data.tanggal}<br>
                                                        <span class="fw-bold">ID Distribusi:</span> ${data.id_distribusi}<br>
                                                    </div>
                                                    <form class="formEvaluasi" method="POST" action="../../../php/kantor/proses_evaluasi.php">
                                                        <input type="hidden" name="id_distribusi" value="${data.id_distribusi}">
                                                        <div class="row g-2 align-items-center">
                                                            <div class="col-md-4">
                                                                <select class="form-select form-select-sm" name="status_distribusi">
                                                                    <option value="">Status Distribusi</option>
                                                                    <option value="1">Baik</option>
                                                                    <option value="2">Kurang Baik</option>
                                                                    <option value="3">Tidak Baik</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="text" class="form-control form-control-sm" name="catatan" placeholder="Catatan Evaluasi">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <button type="submit" class="btn btn-outline-success w-100">Evaluasi</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>`;
                        });
                    } else {
                        html = "<div class='text-muted'>Tidak ada laporan evaluasi.</div>";
                    }

                    // Pastikan selector tepat pada wrapper data
                    $('#navs-justified-laporan-evaluasi .card-body .overflow-auto .p-0').html(html);
                },
                error: function() {
                    alert('Gagal memfilter data evaluasi!');
                }
            });
        });

        $('#btnFilterRiwayat').on('click', function() {
            var sekolah = $('#filterSekolahRiwayat').val();
            var tglAwal = $('#filterTanggalAwalRiwayat').val();
            var tglAkhir = $('#filterTanggalAkhirRiwayat').val();

            $.ajax({
                url: '../../../php/kantor/filter_riwayat_evaluasi.php',
                type: 'POST',
                data: {
                    sekolah: sekolah,
                    tanggal_awal: tglAwal,
                    tanggal_akhir: tglAkhir
                },
                dataType: 'json',
                success: function(response) {
                    var html = '';
                    if (response.length > 0) {
                        html += '<div class="row g-3">';
                        $.each(response, function(i, data) {
                            let badge = "secondary";
                            let statusText = "-";
                            if (data.status_distribusi == 1) {
                                badge = "success";
                                statusText = "Baik";
                            } else if (data.status_distribusi == 2) {
                                badge = "warning";
                                statusText = "Kurang Baik";
                            } else if (data.status_distribusi == 3) {
                                badge = "danger";
                                statusText = "Tidak Baik";
                            }
                            html += `<div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 rounded-3 h-100">
                            <img src="../../../uploads/${data.gambar}" class="card-img-top" alt="Foto Distribusi" style="object-fit:cover; height:200px;">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-2">${data.sekolah_tujuan}</h5>
                                <p class="card-text mb-1"><strong>Tanggal:</strong> ${data.tanggal}</p>
                                <p class="card-text mb-1"><strong>Status Distribusi:</strong> <span class="badge bg-${badge}">${statusText}</span></p>
                                <p class="card-text mb-1"><strong>Catatan Evaluasi:</strong><br>${data.catatan}</p>
                            </div>
                        </div>
                    </div>`;
                        });
                        html += '</div>';
                    } else {
                        html = "<div class='text-muted'>Tidak ada laporan evaluasi.</div>";
                    }
                    $('#navs-justified-riwayat-evaluasi .card-body .p-3').html(html);
                },
                error: function() {
                    alert('Gagal memfilter riwayat evaluasi!');
                }
            });
        });



        function cetakLaporan() {
            // Ambil filter yang sedang dipilih
            var sekolah = $('#filterSekolahRiwayat').val();
            var tglAwal = $('#filterTanggalAwalRiwayat').val();
            var tglAkhir = $('#filterTanggalAkhirRiwayat').val();

            // Buat URL ke file cetak, kirim filter via GET
            var url = '../../../php/kantor/cetak_riwayat_evaluasi.php?sekolah=' + encodeURIComponent(sekolah) +
                '&tanggal_awal=' + encodeURIComponent(tglAwal) +
                '&tanggal_akhir=' + encodeURIComponent(tglAkhir);

            // Buka halaman cetak di tab baru
            window.open(url, '_blank');
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