<!doctype html>

<html lang="en" class="layout-wide customizer-hide" data-assets-path="/assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title>LOGIN | SISTEM INFORMASI MONITORING PEMANTAUAN MAKANAN BERGIZI BERBASIS</title>

    <meta name="description" content="" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />
    <link rel="stylesheet" href="assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css -->
    <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="assets/vendor/css/core.css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- endbuild -->

    <!-- Page CSS -->

    <!-- Page -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
</head>

<body>
    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999;">
        <div id="loginToastSuccess" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ri-checkbox-circle-line me-1"></i> Login berhasil! Mengalihkan...
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        <div id="loginToastError" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ri-error-warning-line me-1"></i> <span id="loginErrorMsg"></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="row w-100 justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card shadow-lg border-0 overflow-hidden">
                    <div class="row g-0">
                        <!-- kiri - Tittel -->
                        <div class="col-lg-4 bg-primary text-white p-5 d-flex flex-column justify-content-center">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold mt-3 text-white">SISTEM INFORMASI MONITORING PEMANTAUAN MAKANAN BERGIZI</h3>
                            </div>
                        </div>

                        <!-- Kanan - Forum Login -->
                        <div class="col-lg-8 p-5">
                            <div class="h-100 d-flex flex-column justify-content-center">
                                <!-- Header -->
                                <div class="text-center mb-4">
                                    <div class="mb-3">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width: 80px; height: 80px;" id="login-icon">
                                            <i class="icon-base icon-48px ri ri-account-circle-line text-primary"></i>
                                        </div>
                                    </div>
                                    <h2 class="fw-bold mb-2" id="login-title">LOGIN</h2>
                                    <hr>
                                </div>

                                <!-- Forum Login -->
                                <form id="loginForm" method="POST" action="php/login.php">
                                    <div class="mb-8">
                                        <label for="username" class="form-label fw-semibold">
                                            <i class="icon-base ri ri-user-line icon-24px me-2"></i>
                                            Username
                                        </label>
                                        <input type="text" class="form-control form-control-lg" id="username" name="username"
                                            placeholder="Masukkan username Anda" required>
                                    </div>
                                    <div class="mb-8">
                                        <div class="form-password-toggle form-control-validation">
                                            <label for="password" class="form-label fw-semibold">
                                                <i class="icon-base ri ri-lock-password-line icon-24px me-2"></i>
                                                Password
                                            </label>
                                            <div class="input-group">
                                                <input type="password" class="form-control form-control-lg" id="password" name="password"
                                                    placeholder="Masukkan password Anda" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg fw-semibold" id="login-btn">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>
                                            Masuk ke Sistem
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            const toast = new bootstrap.Toast(document.getElementById('loginToastSuccess'));
                            toast.show();
                            setTimeout(function() {
                                window.location.href = response.redirect || 'dashboard.php';
                            }, 1500);
                        } else {
                            $('#loginErrorMsg').text(response.message || 'Login gagal!');
                            const toast = new bootstrap.Toast(document.getElementById('loginToastError'));
                            toast.show();
                        }
                    },
                    error: function() {
                        $('#loginErrorMsg').text('Tidak dapat terhubung ke server.');
                        const toast = new bootstrap.Toast(document.getElementById('loginToastError'));
                        toast.show();
                    }
                });
            });
        });
    </script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->

</body>

</html>