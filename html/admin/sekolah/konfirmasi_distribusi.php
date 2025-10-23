<!doctype html>

<?php
session_name('SIMGiziSekolah');
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_sekolah') {
    header('Location: ../../../index.php');
    exit();
}
include '../../../php/config.php';

$id_sekolah = $_SESSION['id_asal_sekolah'];

$query = "SELECT tb_distribusi.*, tb_sekolah.nama_sekolah AS sekolah_tujuan, tb_users.nama AS petugas_distribusi FROM tb_distribusi 
            JOIN tb_sekolah ON tb_sekolah.id_sekolah = tb_distribusi.id_sekolah_tujuan
            JOIN tb_users ON tb_users.id_users = tb_distribusi.id_petugas_distribusi
            WHERE tb_distribusi.status_konfirmasi = '0' AND tb_distribusi.status_pengiriman = '2' AND tb_distribusi.id_sekolah_tujuan = '$id_sekolah' 
            ORDER BY tb_distribusi.tanggal DESC";

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
                    <div class="container-xxl flex-grow-1 container-p-y d-flex flex-column h-100">
                        <div class="mb-4">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">konfirmasi Distribusi</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row gy-6 h-100 flex-grow-1">
                            <div class="col-12 h-100">
                                <div class="card overflow-hidden h-100">
                                    <div class="card-header bg-transparent border-0 pt-4 pb-0 sticky-top bg-white">
                                        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap">
                                            <h4 class="card-title text-success mb-0 fw-bold">Konfirmasi Distribusi</h4>
                                        </div>
                                        <hr class="mt-3 mb-3">
                                    </div>
                                    <div class="card-body">
                                        <?php if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                                <div class="col-12 mb-4">
                                                    <div class="card shadow-sm border border-success">
                                                        <div class="card-body">
                                                            <form class="formKonfirmasi" enctype="multipart/form-data"
                                                                action="../../../php/sekolah/proses_konfirmasi.php" method="POST">

                                                                <input type="hidden" name="id_distribusi" value="<?php echo $row['id_distribusi']; ?>">

                                                                <div class="row g-3">
                                                                    <!-- Kolom Kiri: Informasi Distribusi -->
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <h6 class="text-success fw-bold mb-3">Informasi Distribusi</h6>
                                                                            <div class="mb-2">
                                                                                <span class="fw-bold">Sekolah Tujuan:</span><br>
                                                                                <span><?php echo $row['sekolah_tujuan']; ?></span>
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <span class="fw-bold">Petugas:</span><br>
                                                                                <span><?php echo $row['petugas_distribusi']; ?></span>
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <span class="fw-bold">Jumlah Dikirim:</span><br>
                                                                                <span><?php echo $row['jumlah']; ?> porsi</span>
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <span class="fw-bold">Tanggal:</span><br>
                                                                                <span><?php echo $row['tanggal']; ?></span>
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <span class="fw-bold">Lokasi GPS:</span><br>
                                                                                <span><?php echo $row['lokasi_gps']; ?></span>
                                                                                <button type="button" class="btn btn-outline-info btn-sm ms-2"
                                                                                    onclick="previewLokasi('<?php echo $row['lokasi_gps']; ?>')"
                                                                                    data-bs-toggle="modal" data-bs-target="#modalPreviewMap">
                                                                                    <i class="ri-map-pin-line"></i> Preview Map
                                                                                </button>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Upload Foto -->
                                                                        <div class="mb-3">
                                                                            <label class="form-label fw-bold text-danger">
                                                                                Upload Foto Konfirmasi <span class="text-danger">*</span>
                                                                            </label>
                                                                            <input type="file"
                                                                                class="form-control form-control-sm"
                                                                                name="foto_konfirmasi"
                                                                                accept="image/*"
                                                                                onchange="previewImage(this)"
                                                                                required>
                                                                            <div class="preview-container mt-2"></div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Kolom Kanan: Form Konfirmasi -->
                                                                    <div class="col-md-6">
                                                                        <h6 class="text-success fw-bold mb-3">Form Konfirmasi</h6>

                                                                        <div class="mb-3">
                                                                            <label class="form-label">Status Penerimaan <span class="text-danger">*</span></label>
                                                                            <select class="form-select form-select-sm" name="status" required>
                                                                                <option value="">-- Pilih Status --</option>
                                                                                <option value="1">Diterima</option>
                                                                                <option value="0">Ditolak</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label class="form-label">Jumlah Diterima <span class="text-danger">*</span></label>
                                                                            <input type="number"
                                                                                class="form-control form-control-sm"
                                                                                name="jumlah_diterima"
                                                                                placeholder="Masukkan jumlah yang diterima"
                                                                                min="0"
                                                                                value="<?php echo $row['jumlah']; ?>"
                                                                                required>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label class="form-label">Jumlah Habis <span class="text-danger">*</span></label>
                                                                            <input type="number"
                                                                                class="form-control form-control-sm"
                                                                                name="jumlah_habis"
                                                                                placeholder="Jumlah yang sudah habis dikonsumsi"
                                                                                min="0"
                                                                                required>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label class="form-label">Jumlah Kembali <span class="text-danger">*</span></label>
                                                                            <input type="number"
                                                                                class="form-control form-control-sm"
                                                                                name="jumlah_kembali"
                                                                                placeholder="Jumlah yang dikembalikan"
                                                                                min="0"
                                                                                required>
                                                                            <small class="text-muted">
                                                                                <i class="ri-information-line"></i>
                                                                                Jumlah Habis + Jumlah Kembali harus = Jumlah Diterima
                                                                            </small>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label class="form-label">Catatan</label>
                                                                            <textarea class="form-control form-control-sm"
                                                                                name="catatan"
                                                                                rows="3"
                                                                                placeholder="Catatan tambahan (opsional)"></textarea>
                                                                        </div>

                                                                        <button type="submit" class="btn btn-success w-100">
                                                                            <i class="ri-checkbox-circle-line me-1"></i> Konfirmasi Penerimaan
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

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
                </div>

                <!-- Modal Preview Foto (global, di luar loop) -->
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

                <div class="modal fade" id="modalPreviewMap" tabindex="-1" aria-labelledby="modalMapLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalMapLabel">Preview Lokasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0">
                                <iframe id="mapFrame"
                                    src="https://maps.google.com/maps?q=-0.8898208520946149, 131.32141749340488&z=15&output=embed"
                                    width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false"
                                    tabindex="0"></iframe>
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
            var form = $(this)[0]; // Ambil DOM element asli

            // Ambil nilai untuk validasi
            var status = $(this).find('[name="status"]').val();
            var jumlah_diterima = parseInt($(this).find('[name="jumlah_diterima"]').val()) || 0;
            var jumlah_habis = parseInt($(this).find('[name="jumlah_habis"]').val()) || 0;
            var jumlah_kembali = parseInt($(this).find('[name="jumlah_kembali"]').val()) || 0;
            var foto = $(this).find('[name="foto_konfirmasi"]')[0].files[0];

            // Validasi foto
            if (!foto) {
                $('#konfirmasiErrorMsg').text('Foto konfirmasi wajib diupload!');
                const toast = new bootstrap.Toast(document.getElementById('konfirmasiToastError'));
                toast.show();
                return;
            }

            // Validasi status
            if (!status || status === "") {
                $('#konfirmasiErrorMsg').text('Status penerimaan wajib dipilih!');
                const toast = new bootstrap.Toast(document.getElementById('konfirmasiToastError'));
                toast.show();
                return;
            }

            // Validasi jumlah diterima
            if (jumlah_diterima <= 0) {
                $('#konfirmasiErrorMsg').text('Jumlah diterima wajib diisi dan lebih dari 0!');
                const toast = new bootstrap.Toast(document.getElementById('konfirmasiToastError'));
                toast.show();
                return;
            }

            // Validasi jumlah habis + kembali
            if (jumlah_habis + jumlah_kembali !== jumlah_diterima) {
                $('#konfirmasiErrorMsg').text('Jumlah Habis (' + jumlah_habis + ') + Jumlah Kembali (' + jumlah_kembali + ') harus sama dengan Jumlah Diterima (' + jumlah_diterima + ')!');
                const toast = new bootstrap.Toast(document.getElementById('konfirmasiToastError'));
                toast.show();
                return;
            }

            // Gunakan FormData untuk mengirim file
            var formData = new FormData(form);

            // Disable button submit
            var submitBtn = $(this).find('button[type="submit"]');
            var originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Memproses...');

            // Submit via AJAX dengan FormData
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData: false, // Jangan proses data
                contentType: false, // Jangan set content type
                success: function(response) {
                    submitBtn.prop('disabled', false).html(originalText);

                    if (response.status === 'success') {
                        const toast = new bootstrap.Toast(document.getElementById('konfirmasiToastSuccess'));
                        toast.show();
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        $('#konfirmasiErrorMsg').text(response.message || 'Konfirmasi gagal!');
                        const toast = new bootstrap.Toast(document.getElementById('konfirmasiToastError'));
                        toast.show();
                    }
                },
                error: function(xhr, status, error) {
                    submitBtn.prop('disabled', false).html(originalText);
                    console.log('XHR:', xhr.responseText);
                    console.log('Status:', status);
                    console.log('Error:', error);
                    $('#konfirmasiErrorMsg').text('Tidak dapat terhubung ke server. Cek console untuk detail.');
                    const toast = new bootstrap.Toast(document.getElementById('konfirmasiToastError'));
                    toast.show();
                }
            });
        });

        function previewImage(input) {
            const preview = $(input).closest('form').find('.preview-container');
            preview.html("");

            if (input.files && input.files[0]) {
                // Validasi ukuran file (max 5MB)
                if (input.files[0].size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 5MB');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.html(`
                <div class="border rounded p-2 text-center">
                    <img src="${e.target.result}" class="rounded" 
                         style="width:150px;height:150px;object-fit:cover;">
                    <p class="mb-0 mt-2 small text-success">
                        <i class="ri-checkbox-circle-line"></i> Foto siap diupload
                    </p>
                </div>
            `);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewLokasi(gps) {
            const mapFrame = document.getElementById("mapFrame");
            if (gps) {
                mapFrame.src = `https://maps.google.com/maps?q=${gps}&z=15&output=embed`;
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