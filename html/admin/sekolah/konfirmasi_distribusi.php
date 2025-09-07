<!doctype html>

<?php
include '../../../php/config.php';

$query = "SELECT * FROM tb_distribusi WHERE status_konfirmasi = '0'";
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
        <div id="konfirmasiToastSuccess" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ri-checkbox-circle-line me-1"></i> Konfirmasi berhasil!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        <div id="konfirmasiToastError" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ri-error-warning-line me-1"></i> <span id="konfirmasiErrorMsg"></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
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
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="mb-4">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">konfirmasi Distribusi</li>
                                </ol>
                            </nav>
                        </div>

                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title text-success mb-0 fw-bold">Konfirmasi Distribusi</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                                <div class="overflow-auto" style="max-height: 570px;">
                                                    <div class="card p-3 shadow-sm border">

                                                        <div class="d-flex align-items-center mb-2">
                                                            <img src="../../../uploads/<?php echo $row['foto']; ?>" alt="Petugas" class="rounded"
                                                                style="width:48px; height:48px; object-fit:cover;">
                                                            <div class="ms-2">
                                                                <div class="fw-semibold"><?php echo $row['tujuan']; ?></div>
                                                                <small class="text-muted">Petugas: <?php echo $row['id_distribusi']; ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="mb-2">
                                                            <span class="me-2"><b>Dikirim:</b><?php echo $row['jumlah']; ?></span>
                                                            <span class="me-2"><b>Tgl:</b><?php echo $row['tanggal']; ?></span>
                                                            <span>
                                                                <b>Lokasi:</b>
                                                                <a href="#" class="text-primary text-decoration-underline"><i class="ri-map-pin-2-fill"><?php echo $row['lokasi_gps']; ?></i></a>
                                                                <button type="button" class="btn btn-outline-info btn-sm ms-2" data-bs-toggle="modal"
                                                                    data-bs-target="#modalMap">
                                                                    Preview Map
                                                                </button>
                                                            </span>
                                                        </div>
                                                        <form class="formKonfirmasi" action="../../../php/sekolah/proses_konfirmasi.php" method="POST">
                                                            <input type="hidden" name="id_distribusi" value="<?php echo $row['id_distribusi']; ?>">
                                                            <div class="row g-2 align-items-center">
                                                                <div class="col-md-4">
                                                                    <select class="form-select form-select-sm" id="status" name="status">
                                                                        <option>Status Penerimaan</option>
                                                                        <option value="1">Diterima</option>
                                                                        <option value="0">Ditolak</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="number" class="form-control form-control-sm" id="jumlah" name="jumlah" placeholder="Jumlah Diterima">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="text" class="form-control form-control-sm" id="catatan" name="catatan" placeholder="Catatan (opsional)">
                                                                </div>
                                                            </div>
                                                            <button type="submit" class="btn btn-success btn-sm w-100 mt-2">Konfirmasi</button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php
                                            }
                                        } else {
                                            echo "<p class='text-muted'>Tidak ada distribusi yang perlu dikonfirmasi.</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Google Map Preview -->
                    <div class="modal fade" id="modalMap" tabindex="-1" aria-labelledby="modalMapLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalMapLabel">Preview Lokasi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0">
                                    <iframe id="googleMapFrame"
                                        src="https://maps.google.com/maps?q=-0.8898208520946149, 131.32141749340488&z=15&output=embed"
                                        width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false"
                                        tabindex="0"></iframe>
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
        $(document).on('submit', '.formKonfirmasi', function(e) {
            e.preventDefault();
            var form = $(this);

            // Validasi form
            var status = form.find('[name="status"]').val();
            var jumlah = form.find('[name="jumlah"]').val();

            if (!status || status === "Status Penerimaan") {
                $('#konfirmasiErrorMsg').text('Status penerimaan wajib dipilih!');
                const toast = new bootstrap.Toast(document.getElementById('konfirmasiToastError'));
                toast.show();
                return;
            }
            if (!jumlah || jumlah <= 0) {
                $('#konfirmasiErrorMsg').text('Jumlah diterima wajib diisi dan lebih dari 0!');
                const toast = new bootstrap.Toast(document.getElementById('konfirmasiToastError'));
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
                        const toast = new bootstrap.Toast(document.getElementById('konfirmasiToastSuccess'));
                        toast.show();
                        setTimeout(function() {
                            location.reload();
                        }, 1200);
                    } else {
                        $('#konfirmasiErrorMsg').text(response.message || 'Konfirmasi gagal!');
                        const toast = new bootstrap.Toast(document.getElementById('konfirmasiToastError'));
                        toast.show();
                    }
                },
                error: function() {
                    $('#konfirmasiErrorMsg').text('Tidak dapat terhubung ke server.');
                    const toast = new bootstrap.Toast(document.getElementById('konfirmasiToastError'));
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