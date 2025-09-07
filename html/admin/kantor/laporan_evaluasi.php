<!doctype html>

<?php
include '../../../php/config.php';

// Query to get evaluation report data
$query = "SELECT * FROM tb_distribusi WHERE status_evaluasi = '0' AND status_konfirmasi = '1' ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query);

$query_riwayat_evaluasi = "SELECT tb_evaluasi.*, tb_distribusi.tujuan, tb_distribusi.tanggal 
                           FROM tb_evaluasi 
                           JOIN tb_distribusi ON tb_evaluasi.id_distribusi = tb_distribusi.id_distribusi 
                           ORDER BY tb_evaluasi.id_evaluasi DESC";
$result_riwayat_evaluasi = mysqli_query($conn, $query_riwayat_evaluasi);

$q_sekolah = "SELECT * FROM tb_sekolah";
$result_sekolah = mysqli_query($conn, $q_sekolah);

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
                                <div class="nav-align-top nav-tabs-shadow">
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
                                                    <h5 class="mb-0 text-info">Riwayat Evaluasi</h5>
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
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="navs-justified-laporan-evaluasi" role="tabpanel">
                                            <div class="d-flex align-items-center justify-content-end flex-nowrap gap-2">
                                                <div class="form-floating form-floating-outline flex-grow-1">
                                                    <select class="form-select" name="sekolah" id="filterSekolah" required>
                                                        <option value="">Pilih Sekolah</option>
                                                        <?php
                                                        while ($row = mysqli_fetch_assoc($result_sekolah)) {
                                                            echo "<option value='" . $row['nama_sekolah'] . "'>" . $row['nama_sekolah'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-floating form-floating-outline flex-grow-1">
                                                    <input type="date" id="filterTanggalAwal" class="form-control" placeholder="Tanggal Awal" />
                                                    <label for="filterTanggalAwal">Tanggal Awal</label>
                                                </div>
                                                <div class="form-floating form-floating-outline flex-grow-1">
                                                    <input type="date" id="filterTanggalAkhir" class="form-control" placeholder="Tanggal Akhir" />
                                                    <label for="filterTanggalAkhir">Tanggal Akhir</label>
                                                </div>
                                                <div class="">
                                                    <button type="submit" class="btn btn-outline-success">Filter</button>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="card-body p-0">
                                                <div class="overflow-auto" style="height: calc(660px - 90px);">
                                                    <div class="p-0">
                                                        <?php
                                                        if (mysqli_num_rows($result) > 0) {
                                                            while ($data = mysqli_fetch_assoc($result)) {
                                                                // Display each evaluation report
                                                        ?>
                                                                <div class="col-12">
                                                                    <div class="card p-3 shadow-sm border">
                                                                        <div class="d-flex align-items-center mb-2">
                                                                            <img src="../../../assets/img/avatars/1.png" alt="Petugas" class="rounded"
                                                                                style="width:48px; height:48px; object-fit:cover;">
                                                                            <div class="ms-2">
                                                                                <div class="fw-semibold"><?= $data["tujuan"] ?></div>
                                                                                <small class="text-muted">tanggal: <?= $data["tanggal"] ?></small>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mb-2">
                                                                        </div>
                                                                        <form class="formEvaluasi" method="POST" action="../../../php/kantor/proses_evaluasi.php">
                                                                            <div class="row g-2 align-items-center">
                                                                                <input type="hidden" name="id_distribusi" value="<?= $data["id_distribusi"] ?>">
                                                                                <div class="col-md-3">
                                                                                    <select class="form-select form-select-sm" id="status" name="status_distribusi">
                                                                                        <option value="">Status Distribusi</option>
                                                                                        <option value="1">Baik</option>
                                                                                        <option value="2">Kurang Baik</option>
                                                                                        <option value="3">Tidak Baik</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-7">
                                                                                    <input type="text" class="form-control form-control-sm" name="catatan" id="catatan" placeholder="Catatan Evaluasi">
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <button type="submit" class="btn btn-outline-success w-100">Evaluasi</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
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
                                                    <select class="form-select" name="sekolah" id="filterSekolah" required>
                                                        <option value="">Pilih Sekolah</option>
                                                        <?php
                                                        while ($row = mysqli_fetch_assoc($result_sekolah)) {
                                                            echo "<option value='" . $row['nama_sekolah'] . "'>" . $row['nama_sekolah'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-floating form-floating-outline flex-grow-1">
                                                    <input type="date" id="filterTanggalAwal" class="form-control" placeholder="Tanggal Awal" />
                                                    <label for="filterTanggalAwal">Tanggal Awal</label>
                                                </div>
                                                <div class="form-floating form-floating-outline flex-grow-1">
                                                    <input type="date" id="filterTanggalAkhir" class="form-control" placeholder="Tanggal Akhir" />
                                                    <label for="filterTanggalAkhir">Tanggal Akhir</label>
                                                </div>
                                                <div class="">
                                                    <button type="submit" class="btn btn-outline-success">Filter</button>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="card-body p-0">
                                                <div class="overflow-auto" style="height: calc(660px - 90px);">
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
                                                                            <img src="../../../assets/img/avatars/1.png"
                                                                                class="card-img-top"
                                                                                alt="Foto Distribusi"
                                                                                style="object-fit:cover; height:200px;">
                                                                            <div class="card-body">
                                                                                <h5 class="card-title text-primary mb-2"><?= $data["tujuan"] ?></h5>
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