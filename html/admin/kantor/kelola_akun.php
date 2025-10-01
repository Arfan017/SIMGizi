<!doctype html>

<?php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../../../index.php');
    exit();
}
include '../../../php/config.php';

// Query to get account data
$query = "SELECT tb_users.*, 
                 IF(tb_users.id_asal_sekolah = NULL, '-', tb_sekolah.nama_sekolah) AS asal_sekolah 
          FROM tb_users 
          LEFT JOIN tb_sekolah ON tb_users.id_asal_sekolah = tb_sekolah.id_sekolah order by tb_users.id_users ASC";
$result = mysqli_query($conn, $query);

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
                    <div class="container-xxl flex-grow-1 container-p-y d-flex flex-column h-100">
                        <div class="mb-4">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kelola Akun</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row gy-6 h-100 flex-grow-1">
                            <!-- Data Tables -->
                            <div class="col-12 h-100">
                                <div class="card overflow-hidden h-100">
                                    <div class="card-header bg-transparent border-0 pt-4 pb-0 sticky-top bg-white">
                                        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap">
                                            <h4 class="card-title text-info mb-0 fw-bold">Kelola Akun</h4>
                                            <div class="d-flex align-items-center justify-content-end flex-nowrap gap-2">
                                                <button class="btn btn-outline-info w-60" type="button" data-bs-toggle="modal" data-bs-target="#ModalTambahAkun">
                                                    <span class="icon-base ri ri-user-add-line icon-16px me-1_5"></span>Tambah Akun
                                                </button>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text bg-white border-end-0">
                                                        <i class="icon-base ri ri-search-line text-muted"></i>
                                                    </span>
                                                    <input type="text" class="form-control border-start-0" placeholder="Cari laporan evaluasi...">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="alertContainer"></div>
                                        <hr class="mt-3 mb-3">
                                    </div>
                                    <div class="table-responsive overflow-auto" style="height: calc(570px - 90px);">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-truncate">Id</th>
                                                    <th class="text-truncate">Nama</th>
                                                    <th class="text-truncate">Peran</th>
                                                    <th class="text-truncate">Username</th>
                                                    <th class="text-truncate">Asal Sekolah</th>
                                                    <th class="text-truncate">No HP</th>
                                                    <th class="text-truncate">Status</th>
                                                    <th class="text-truncate">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $status_badge = $row['status'] == 'Aktif' ? 'bg-label-success' : 'bg-label-danger';
                                                ?>
                                                        <tr>
                                                            <td class="text-truncate"><?php echo $row['id_users']; ?></td>
                                                            <td class="text-truncate"><?php echo $row['nama']; ?></td>
                                                            <td class="text-truncate"><?php echo $row['role']; ?></td>
                                                            <td class="text-truncate">
                                                                <span><?php echo $row['username']; ?></span>
                                                            </td>
                                                            <td class="text-truncate"><?php echo $row['asal_sekolah'] ?? "-"; ?></td>
                                                            <td class="text-truncate"><?php echo $row['no_hp']; ?></td>
                                                            <td><span class="badge <?php echo $status_badge; ?> rounded-pill"><?php echo $row['status']; ?></span></td>
                                                            <td>
                                                                <div>
                                                                    <button type="button" class="btn btn-sm btn-outline-warning btnEditAkun"
                                                                        data-bs-target="#ModalEditAkun"
                                                                        data-bs-toggle="modal"
                                                                        data-id_users="<?= $row['id_users'] ?>"
                                                                        data-username="<?= $row['username'] ?>"
                                                                        data-nama="<?= $row['nama'] ?>"
                                                                        data-peran="<?= $row['role'] ?>"
                                                                        data-password="<?= $row['password'] ?>"
                                                                        data-no_hp="<?= $row['no_hp'] ?>"
                                                                        data-status="<?= $row['status'] ?>">
                                                                        <span class="icon-base ri ri-info-card-line icon-16px me-1_5"></span>Edit
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-outline-danger btnHapusAkun"
                                                                        data-bs-target="#ModalHapusAkun"
                                                                        data-bs-toggle="modal"
                                                                        data-id_users="<?= $row['id_users'] ?>"
                                                                        data-username="<?= $row['username'] ?>"
                                                                        data-nama="<?= $row['nama'] ?>"
                                                                        data-peran="<?= $row['role'] ?>"
                                                                        data-password="<?= $row['password'] ?>"
                                                                        data-no_hp="<?= $row['no_hp'] ?>">
                                                                        <span class="icon-base ri ri-delete-bin-2-line icon-16px me-1_5"></span>Hapus
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">Tidak ada data akun</td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--/ Data Tables -->
                        </div>

                        <!-- Modal Tambah Akun -->
                        <div class="modal fade" id="ModalTambahAkun" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form id="formTambahAkun" method="POST" action="../../../php/kantor/crud_akun.php">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel1">Tambah Akun</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-muted">Silakan isi form di bawah ini dengan benar untuk menambahkan akun baru.</p>
                                            <hr>
                                            <div class="row g-4">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan Nama" required />
                                                        <label>Nama</label>
                                                    </div>
                                                </div>
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <select class="form-select" name="role" required>
                                                            <option value="">Pilih Peran</option>
                                                            <option value="admin_kantor">Admin Kantor</option>
                                                            <option value="admin_sekolah">Admin Sekolah</option>
                                                            <option value="admin_distribusi">Admin Distribusi</option>
                                                        </select>
                                                        <label>Pilih Peran</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-4">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required />
                                                        <label>Username</label>
                                                    </div>
                                                </div>
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required />
                                                        <label>Password</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-4" id="rowAsalSekolah" style="display:none;">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <select class="form-select" name="id_asal_sekolah" id="asalSekolah">
                                                            <option value="">Pilih Asal Sekolah</option>
                                                            <?php
                                                            $q_sekolah = mysqli_query($conn, "SELECT id_sekolah, nama_sekolah FROM tb_sekolah");
                                                            while ($sekolah = mysqli_fetch_assoc($q_sekolah)) {
                                                                echo '<option value="' . $sekolah['id_sekolah'] . '">' . $sekolah['nama_sekolah'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <label>Asal Sekolah</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-4">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" name="no_hp" class="form-control" placeholder="Masukkan No HP" required />
                                                        <label>No HP</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal">
                                                <span class="icon-base ri ri-close-line icon-16px me-1_5"></span>Tutup
                                            </button>
                                            <button type="submit" name="create" class="btn btn-outline-success">
                                                <span class="icon-base ri ri-add-line icon-16px me-1_5"></span>Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Hapus -->
                        <div class="modal fade" id="ModalHapusAkun" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form id="formHapusAkun" method="POST" action="../../../php/kantor/crud_akun.php">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel1">Hapus Akun</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-muted">Anda yakin ingin menghapus akun ini?</p>
                                            <hr>
                                            <div class="row g-4">
                                                <div class="col mb-2">
                                                    <input type="hidden" id="hapusIdUsers" name="id_users" />
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" id="hapusNama" class="form-control" placeholder="Masukkan Nama" disabled />
                                                        <label for="emailBasic">Nama</label>
                                                    </div>
                                                </div>
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" id="hapusPeran" class="form-control" placeholder="Masukkan Nama" disabled />
                                                        <label for="hapusPeran">Peran</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-4">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" id="hapusUsername" class="form-control" placeholder="Masukkan Username" disabled />
                                                        <label for="hapusUsername">Username</label>
                                                    </div>
                                                </div>
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="password" id="hapusPassword" class="form-control"
                                                            placeholder="Masukkan Password" disabled />
                                                        <label for="hapusPassword">Password</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                                                <span class="icon-base ri ri-close-line icon-16px me-1_5"></span>Tutup</button>
                                            <button type="submit" class="btn btn-outline-danger btnDelete">
                                                <span class="icon-base ri ri-delete-bin-2-line icon-16px me-1_5"></span>Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="ModalEditAkun" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form id="formEditAkun" method="POST" action="../../../php/kantor/crud_akun.php">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Akun</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_users" id="editIdUsers" />
                                            <div class="row g-4">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" name="nama_lengkap" id="editNama" class="form-control" placeholder="Masukkan Nama" required />
                                                        <label>Nama</label>
                                                    </div>
                                                </div>
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <select class="form-select" name="role" id="editPeran" required>
                                                            <option value="">Pilih Peran</option>
                                                            <option value="admin_kantor">Admin Kantor</option>
                                                            <option value="admin_sekolah">Admin Sekolah</option>
                                                            <option value="admin_distribusi">Admin Distribusi</option>
                                                        </select>
                                                        <label>Pilih Peran</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-4">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" name="username" id="editUsername" class="form-control" placeholder="Masukkan Username" required />
                                                        <label>Username</label>
                                                    </div>
                                                </div>
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="password" name="password" id="editPassword" class="form-control" placeholder="Masukkan Password (kosongkan jika tidak diubah)" />
                                                        <label>Password</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-4">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" name="no_hp" id="editNoHp" class="form-control" placeholder="Masukkan No HP" required />
                                                        <label>No HP</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-4" id="rowEditAsalSekolah" style="display:none;">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <select class="form-select" name="id_asal_sekolah" id="editAsalSekolah">
                                                            <option value="">Pilih Asal Sekolah</option>
                                                            <?php
                                                            $q_sekolah = mysqli_query($conn, "SELECT id_sekolah, nama_sekolah FROM tb_sekolah");
                                                            while ($sekolah = mysqli_fetch_assoc($q_sekolah)) {
                                                                echo '<option value="' . $sekolah['id_sekolah'] . '">' . $sekolah['nama_sekolah'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <label>Asal Sekolah</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-select" name="status" id="editStatus" required>
                                                    <option value="Aktif">Aktif</option>
                                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                                </select>
                                                <label>Status</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal">
                                                <span class="icon-base ri ri-close-line icon-16px me-1_5"></span>Tutup
                                            </button>
                                            <button type="submit" class="btn btn-outline-success">
                                                <span class="icon-base ri ri-check-line icon-16px me-1_5"></span>Simpan
                                            </button>
                                        </div>
                                    </form>
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
        $(document).ready(function() {
            $('select[name="role"]').on('change', function() {
                if ($(this).val() === 'admin_sekolah') {
                    $('#rowAsalSekolah').show();
                    $('#asalSekolah').prop('required', true);
                } else {
                    $('#rowAsalSekolah').hide();
                    $('#asalSekolah').prop('required', false);
                }
            });
        });

        $(document).ready(function() {
            $("#formTambahAkun").on("submit", function(e) {
                e.preventDefault(); // cegah reload

                $.ajax({
                    url: "../../../php/kantor/crud_akun.php",
                    type: "POST",
                    data: $(this).serialize() + "&create=1",
                    dataType: "json",
                    success: function(response) {
                        let alertClass = "";
                        if (response.status === "success") {
                            alertClass = "alert-success";
                            $("#formTambahAkun")[0].reset();
                            $("#ModalTambahAkun").modal("hide");
                            setTimeout(function() {
                                location.reload(); // refresh tabel agar data baru muncul
                            }, 1000);
                        } else if (response.status === "exists") {
                            alertClass = "alert-warning";
                        } else {
                            alertClass = "alert-danger";
                        }

                        $("#alertContainer").html(`
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <strong>${response.status.toUpperCase()}!</strong> ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                    },
                    error: function() {
                        $("#alertContainer").html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>ERROR!</strong> Tidak dapat terhubung ke server.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                    }
                });
            });
        });

        $("#formHapusAkun").on("submit", function(e) {
            e.preventDefault(); // cegah reload

            $.ajax({
                url: "../../../php/kantor/crud_akun.php",
                type: "POST",
                data: $(this).serialize() + "&delete=1",
                dataType: "json",
                success: function(response) {
                    let alertClass = "";
                    if (response.status === "success") {
                        alertClass = "alert-success";
                        $("#formHapusAkun")[0].reset();
                        $("#ModalHapusAkun").modal("hide");
                        setTimeout(function() {
                            location.reload(); // refresh tabel agar data baru muncul
                        }, 1000);
                    } else if (response.status === "exists") {
                        alertClass = "alert-warning";
                    } else {
                        alertClass = "alert-danger";
                    }

                    $("#alertContainer").html(`
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <strong>${response.status.toUpperCase()}!</strong> ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                },
                error: function() {
                    $("#alertContainer").html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>ERROR!</strong> Tidak dapat terhubung ke server.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                }
            });
        });

        $(document).on('click', '.btnHapusAkun', function() {
            var id_users = $(this).data('id_users');
            var nama = $(this).data('nama');
            var peran = $(this).data('peran');
            var username = $(this).data('username');
            var password = $(this).data('password');

            // Isi data ke input modal
            $('#hapusIdUsers').val(id_users);
            $('#hapusNama').val(nama);
            $('#hapusPeran').val(peran);
            $('#hapusUsername').val(username);
            $('#hapusPassword').val(password);
        });

        $("#formEditAkun").on("submit", function(e) {
            e.preventDefault();
            $.ajax({
                url: "../../../php/kantor/crud_akun.php",
                type: "POST",
                data: $(this).serialize() + "&update=1",
                dataType: "json",
                success: function(response) {
                    let alertClass = "";
                    if (response.status === "success") {
                        alertClass = "alert-success";
                        $("#formEditAkun")[0].reset();
                        $("#ModalEditAkun").modal("hide");
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        alertClass = "alert-danger";
                    }
                    $("#alertContainer").html(`
                            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                                <strong>${response.status.toUpperCase()}!</strong> ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                },
                error: function() {
                    $("#alertContainer").html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>ERROR!</strong> Tidak dapat terhubung ke server.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                }
            });
        });

        $(document).on('click', '.btnEditAkun', function() {
            var id_users = $(this).data('id_users');
            var nama = $(this).data('nama');
            var peran = $(this).data('peran');
            var username = $(this).data('username');
            var password = $(this).data('password');
            var no_hp = $(this).data('no_hp');

            // Isi data ke input modal
            $('#editIdUsers').val(id_users);
            $('#editNama').val(nama);
            $('#editPeran').val(peran);
            $('#editUsername').val(username);
            $('#editNoHp').val(no_hp);
        });

        $(document).ready(function() {
            // Tampilkan/hide dropdown asal sekolah saat peran diubah
            $('#editPeran').on('change', function() {
                if ($(this).val() === 'admin_sekolah') {
                    $('#rowEditAsalSekolah').show();
                    $('#editAsalSekolah').prop('required', true);
                } else {
                    $('#rowEditAsalSekolah').hide();
                    $('#editAsalSekolah').prop('required', false);
                }
            });

            // Isi data ke modal edit akun
            $(document).on('click', '.btnEditAkun', function() {
                var id_users = $(this).data('id_users');
                var nama = $(this).data('nama');
                var peran = $(this).data('peran');
                var username = $(this).data('username');
                var password = $(this).data('password');
                var no_hp = $(this).data('no_hp');
                var id_asal_sekolah = $(this).data('id_asal_sekolah') || '';

                $('#editIdUsers').val(id_users);
                $('#editNama').val(nama);
                $('#editPeran').val(peran);
                $('#editUsername').val(username);
                $('#editNoHp').val(no_hp);

                // Set asal sekolah jika ada
                $('#editAsalSekolah').val(id_asal_sekolah);

                // Tampilkan dropdown asal sekolah jika peran admin_sekolah
                if (peran === 'admin_sekolah') {
                    $('#rowEditAsalSekolah').show();
                    $('#editAsalSekolah').prop('required', true);
                } else {
                    $('#rowEditAsalSekolah').hide();
                    $('#editAsalSekolah').prop('required', false);
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