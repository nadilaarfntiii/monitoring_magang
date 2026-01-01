<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <base href="<?= base_url(); ?>/">
    
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0..." type="image/png">

    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="./assets/compiled/css/iconly.css">
    <link rel="stylesheet" href="./assets/css/main.css">

    <style>
        .avatar img {
            width: 48px !important;       /* atau ukuran avatar-md */
            height: 48px !important;
            object-fit: cover !important;
            border-radius: 50% !important;
        }
    </style>
</head>

<body>
    <script src="assets/static/js/initTheme.js"></script>
    <div id="app">

        <!-- Sidebar -->
        <div id="sidebar">
            <?= $this->include('layouts/components/sidebar_mhs') ?>
        </div>
        <!-- End Sidebar -->

        <!-- Main -->
        <div id="main">
            <!-- Header / Navbar -->
            <header>
                <nav class="navbar navbar-expand navbar-light navbar-top">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block" id="sidebarToggle">
                            <i class="bi bi-justify fs-3"></i>
                        </a>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <div class="dropdown ms-auto">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex align-items-center">
                                        <div class="user-name text-end me-3">
                                        <h6 class="mb-0 text-gray-600"><?= esc($user_name) ?></h6>
                                            <p class="mb-0 text-sm text-gray-600">
                                                <?php 
                                                    $role = session()->get('role');
                                                    $roles = [
                                                        'mahasiswa'     => 'Mahasiswa'
                                                    ];
                                                    echo $roles[$role] ?? ucfirst($role);
                                                ?>
                                            </p>
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                        <div class="avatar avatar-md">
                                            <?php
                                                $foto = session()->get('foto');
                                                $foto_url = (!empty($foto) && file_exists(FCPATH . 'uploads/foto/' . $foto))
                                                            ? base_url('uploads/foto/' . $foto)
                                                            : base_url('assets/images/pp.jpg');
                                            ?>
                                            <img src="<?= $foto_url ?>" alt="Foto Profile">
                                        </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 11rem;">
                                    <li><h6 class="dropdown-header">Hallo, <?= esc($user_name) ?>!</h6></li>
                                    <li><a class="dropdown-item" href="<?= base_url('mahasiswa/profil'); ?>"><i class="bi bi-person me-2"></i> Profil Saya</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('update_password'); ?>"><i class="bi bi-key me-2"></i> Ubah Kata Sandi</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-left me-2"></i> Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
            <!-- End Header -->

            <!-- Konten Halaman -->
            <?= $this->renderSection('content') ?>
            <!-- End Konten -->

            <!-- Footer -->
            <footer style="
                position: fixed; 
                bottom: 0; 
                left: 5; 
                width: 76%; 
                padding: 5px 10px;">
                            
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2025 &copy; Institut Widya Pratama</p>
                    </div>
                </div>
            </footer>
            <!-- End Footer -->

        </div>
    </div>

    <?php 
    $uri = service('uri');
    $page = $uri->getSegment(1); // data_mahasiswa
    ?>

    <?php if ($page !== 'data_mahasiswa'): ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php endif; ?>
    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/compiled/js/app.js"></script>
    <script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
    <script src="assets/static/js/pages/dashboard.js"></script>

    <script>
        // Script toggle sidebar
        const toggleBtn = document.getElementById("sidebarToggle");
        if(toggleBtn){
            toggleBtn.addEventListener("click", function (e) {
                e.preventDefault();
                document.querySelector(".sidebar-wrapper").classList.toggle("active");
            });
        }
    </script>

</body>
</html>
