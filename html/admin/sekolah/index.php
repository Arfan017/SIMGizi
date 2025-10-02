<!doctype html>

<?php
session_start();
if (!isset($_SESSION['role'])) {
  header('Location: ../../../index.php');
  exit();
}
include '../../../php/config.php';

$id_sekolah = $_SESSION['id_asal_sekolah'];

// Query to get account data
$query = "SELECT tb_distribusi.*, tb_users.nama, tb_sekolah.nama_sekolah as sekolah_tujuan FROM tb_distribusi 
          JOIN tb_users ON tb_distribusi.id_petugas_distribusi = tb_users.id_users
          JOIN tb_sekolah ON tb_sekolah.id_sekolah = tb_distribusi.id_sekolah_tujuan
          WHERE tb_distribusi.status_konfirmasi = '0' AND tb_distribusi.status_pengiriman = '1' AND id_sekolah_tujuan = '$id_sekolah' 
          ORDER BY tb_distribusi.tanggal DESC";
$result = mysqli_query($conn, $query);

// Jumlah seluruh data distribusi
$q_total = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tb_distribusi WHERE status_konfirmasi = '1' AND id_sekolah_tujuan = '$id_sekolah'");
$total_distribusi = mysqli_fetch_assoc($q_total)['total'];

// Jumlah terkonfirmasi (status = 1)
$q_terkonfirmasi = mysqli_query($conn, "SELECT COUNT(*) AS terkonfirmasi FROM tb_distribusi WHERE status_konfirmasi = '1' AND id_sekolah_tujuan = '$id_sekolah'");
$terkonfirmasi = mysqli_fetch_assoc($q_terkonfirmasi)['terkonfirmasi'];

// Jumlah belum terkonfirmasi (status = 0)
$q_belum = mysqli_query($conn, "SELECT COUNT(*) AS belum FROM tb_distribusi WHERE status_konfirmasi = '0' AND id_sekolah_tujuan = '$id_sekolah'");
$belum_terkonfirmasi = mysqli_fetch_assoc($q_belum)['belum'];

?>

<html
  lang="en"
  class="layout-menu-fixed layout-compact"
  data-assets-path="../../../assets/"

  data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
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
                <a
                  class="nav-link dropdown-toggle hide-arrow p-0"
                  href="javascript:void(0);"
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
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y d-flex flex-column h-100 pb-0">
            <div class="mb-4">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                </ol>
              </nav>
            </div>
            <div class="row gy-3">
              <!-- Dashboard Card -->
              <div class="col-12">
                <div class="card h-100">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h4 class="card-title text-success mb-0 fw-bold">Dashboard Admin Sekolah</h4>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row g-6">
                      <div class="col-md-4 col-6">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-md">
                            <div class="avatar-initial bg-primary rounded shadow-xs">
                              <i class="icon-base ri ri-pie-chart-2-line icon-24px"></i>
                            </div>
                          </div>
                          <div class="ms-3">
                            <p class="mb-0">Laporan Distribusi</p>
                            <h5 class="mb-0"><?php echo $total_distribusi; ?></h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4 col-6">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-md">
                            <div class="avatar-initial bg-success rounded shadow-xs">
                              <i class="icon-base ri ri-check-double-line icon-24px"></i>
                            </div>
                          </div>
                          <div class="ms-3">
                            <p class="mb-0">Terkonfirmasi</p>
                            <h5 class="mb-0"><?php echo $terkonfirmasi; ?></h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4 col-6">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-md">
                            <div class="avatar-initial bg-warning rounded shadow-xs">
                              <i class="icon-base ri ri-check-line icon-24px"></i>
                            </div>
                          </div>
                          <div class="ms-3">
                            <p class="mb-0">Belum Terkonfirmasi</p>
                            <h5 class="mb-0"><?php echo $belum_terkonfirmasi; ?></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--/ Dashboard Card -->

              <!-- Data Baru Table -->
              <div class="col-md">
                <div class="card overflow-hidden">
                  <!-- Fixed Header -->
                  <div class="card-header border-0 pt-4 pb-0 sticky-top bg-white">
                    <h5 class="card-title text-success mb-0 fw-bold">Data Baru</h5>
                    <hr class="mt-3 mb-0">
                  </div>
                  <!-- Scrollable Body -->
                  <div class="card-body p-0">
                    <div class="overflow-auto" style="height: calc(480px - 90px);">
                      <div class="p-4">
                        <table class="table-responsive table table-sm">
                          <thead>
                            <tr>
                              <th class="text-truncate">Tanggal</th>
                              <th class="text-truncate">Petugas Distribusi</th>
                              <th class="text-truncate">Jumlah Distribusi</th>
                              <th class="text-truncate">Status</th>
                              <th class="text-truncate">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                              while ($row = mysqli_fetch_assoc($result)) {
                                $status_badge = $row['status_konfirmasi'] == '1' ? 'bg-label-success' : 'bg-label-warning';
                                $status_text = $row['status_konfirmasi'] == '1' ? 'Terkonfirmasi' : 'Belum Terkonfirmasi';
                            ?>
                                <tr>
                                  <td>
                                    <div class="d-flex align-items-center">
                                      <div>
                                        <h6 class="mb-0 text-truncate"><?php echo $row['tanggal']; ?></h6>
                                      </div>
                                    </div>
                                  </td>
                                  <td class="text-truncate"><?php echo $row['nama']; ?></td>
                                  <td class="text-truncate">
                                    <span><?php echo $row['jumlah']; ?></span>
                                  </td>
                                  <td>
                                    <span class="badge <?php echo $status_badge; ?> rounded-pill"><?php echo $status_text; ?></span>
                                  </td>
                                  <td>
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm btn-outline-success btnDetail"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ModalDetail"
                                        data-id_distribusi="<?= $row['id_distribusi'] ?>"
                                        data-tanggal="<?= $row['tanggal'] ?>"
                                        data-jumlah="<?= $row['jumlah'] ?>"
                                        data-tujuan="<?= $row['sekolah_tujuan'] ?>"
                                        data-lokasi_gps="<?= $row['lokasi_gps'] ?>"
                                        data-nama="<?= $row['nama'] ?>">
                                        Detail
                                      </button>
                                    </div>
                                  </td>
                                </tr>
                              <?php
                              }
                            } else {
                              ?>
                              <tr>
                                <td colspan="5" class="text-center">Tidak ada data distribusi</td>
                              </tr>
                            <?php
                            }
                            ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <!--/ Data Baru Table -->
                </div>
              </div>
            </div>

            <!-- Modal Detail -->
            <div class="modal fade" id="ModalDetail" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header align-items-center">
                    <img src="../../../assets/img/avatars/1.png" alt="Petugas" class="rounded"
                      style="width:48px; height:48px; object-fit:cover;">
                    <div class="ms-2">
                      <div class="fw-semibold"><span id="detailTujuan"></span></div>
                      <small class="text-muted">Petugas: <span id="detailNama"></span></small>
                    </div>
                  </div>
                  <div class="modal-body">
                    <div class="mb-2">
                      <span class="me-2"><b>ID Distribusi:</b> <span id="detailIdDistribusi"></span></span><br>
                      <span class="me-2"><b>Dikirim:</b> <span id="detailJumlah"></span></span><br>
                      <span class="me-2"><b>Tgl:</b> <span id="detailTanggal"></span></span><br>
                      <span>
                        <b>Lokasi GPS:</b>
                        <span id="detailLokasi"></span>
                        <button type="button" class="btn btn-outline-info btn-sm ms-2" id="btnPreviewMap">
                          <i class="icon-base ri ri-map-pin-line"></i>
                        </button>
                      </span>
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
                      src=""
                      width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false"
                      tabindex="0"></iframe>
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
            <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
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
    $(document).on("click", ".btnDetail", function() {
      let id_distribusi = $(this).data("id_distribusi");
      let tanggal = $(this).data("tanggal");
      let jumlah = $(this).data("jumlah");
      let lokasi = $(this).data("lokasi_gps");
      let tujuan = $(this).data("tujuan");
      let nama = $(this).data("nama");

      // Isi modal
      $("#detailIdDistribusi").text(id_distribusi);
      $("#detailTanggal").text(tanggal);
      $("#detailJumlah").text(jumlah);
      $("#detailTujuan").text(tujuan);
      $("#detailLokasi").text(lokasi);
      $("#detailNama").text(nama);

      // Simpan lokasi GPS untuk tombol map
      $("#btnPreviewMap").data("lokasi", lokasi);
    });

    $(document).on("click", "#btnPreviewMap", function() {
      let lokasi = $(this).data("lokasi");
      if (lokasi) {
        $("#googleMapFrame").attr("src", "https://maps.google.com/maps?q=" + lokasi + "&z=15&output=embed");
        $("#modalMap").modal("show");
      }
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