<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <base href="<?= base_url(); ?>/">

    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="./assets/compiled/css/iconly.css">
</head>

<body>
    <script src="assets/static/js/initTheme.js"></script>
    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">

                <div class="logo">
                    <a href="/mitra/dashboard"><img src="./assets/images/iwima.png" alt="Logo" srcset=""></a>
                </div>

                <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                        role="img" class="iconify iconify--system-uicons" width="20" height="20"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                        <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                opacity=".3"></path>
                            <g transform="translate(-210 -1)">
                                <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                <circle cx="220.5" cy="11.5" r="4"></circle>
                                <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                            </g>
                        </g>
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                        <label class="form-check-label"></label>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                        role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet"
                        viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                        </path>
                    </svg>
                </div>

                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>

        </div>
    </div>

                <!-- Sidebar Menu -->
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item <?= (uri_string() == 'mitra/dashboard') ? 'active' : '' ?>">
                            <a href="<?= base_url('mitra/dashboard') ?>" class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?= (uri_string() == 'mitra/mahasiswa') ? 'active' : '' ?>">
                            <a href="<?= base_url('mitra/mahasiswa') ?>" class="sidebar-link">
                                <i class="bi bi-people-fill"></i>
                                <span>Data Mahasiswa</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?= (uri_string() == 'mitra/kelola_jam_kerja') ? 'active' : '' ?>">
                            <a href="<?= base_url('mitra/kelola_jam_kerja') ?>" class="sidebar-link">
                                <i class="bi bi-clock"></i>
                                <span>Kelola Jam Kerja</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?= (uri_string() == 'mitra/kelola_presensi') ? 'active' : '' ?>">
                            <a href="<?= base_url('mitra/kelola_presensi') ?>" class="sidebar-link">
                                <i class="bi bi-clipboard-check"></i>
                                <span>Kelola Presensi</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?= (uri_string() == 'mitra/kelola_learning_plan') ? 'active' : '' ?>">
                            <a href="<?= base_url('mitra/kelola_learning_plan') ?>" class="sidebar-link">
                                <i class="bi bi-journal-bookmark"></i>
                                <span>Kelola Learning Plan</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?= (uri_string() == 'mitra/kelola_logbook') ? 'active' : '' ?>">
                            <a href="<?= base_url('mitra/kelola_logbook') ?>" class="sidebar-link">
                                <i class="bi bi-book"></i>
                                <span>Kelola Logbook</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?= (uri_string() == 'mitra/kelola_penilaian') ? 'active' : '' ?>">
                            <a href="<?= base_url('mitra/kelola_penilaian') ?>" class="sidebar-link">
                                <i class="bi bi-clipboard-check"></i>
                                <span>Kelola Penilaian</span>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <!-- Main Content -->
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
                                                        'mitra'     => 'Pembimbing Perusahaan'
                                                    ]; 
                                                    echo $roles[$role] ?? ucfirst($role);
                                                ?>
                                            </p>
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                        <div class="avatar avatar-md">
                                                <img src="<?= base_url(
                                                    file_exists(FCPATH . 'uploads/foto/' . $foto)
                                                        ? 'uploads/foto/' . $foto
                                                        : 'assets/images/pp.jpg'
                                                ) ?>"
                                                alt="Foto Profile">
                                        </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 11rem;">
                                    <li><h6 class="dropdown-header">Hallo, <?= esc($user_name) ?>!</h6></li>
                                    <li><a class="dropdown-item" href="<?= base_url('mitra/profil'); ?>"><i class="bi bi-person me-2"></i> Profil Saya</a></li>
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
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Dashboard</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('mitra/dashboard') ?>">Dashboard</a></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" 
                        class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 11.03a.75.75 0 0 0 1.07 0l4-4a.75.75 0 1 0-1.06-1.06L7.5 9.44 5.53 7.47a.75.75 0 1 0-1.06 1.06l2.5 2.5z"/>
                    </svg>
                    <div>
                        <?= session()->getFlashdata('success'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($isDefaultPassword) && $isDefaultPassword): ?>
                <div class="alert alert-warning d-flex align-items-center" role="alert" style="border-left: 5px solid #f0ad4e; background-color: #fff8e1; color: #856404; padding: 15px; font-size: 0.95rem; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px; margin-right: 10px; flex-shrink: 0;" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.964 0L.165 13.233c-.457.778.091 1.767.982 1.767h13.707c.89 0 1.438-.99.982-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                    <div>
                        <strong>Perhatian!</strong> Anda masih menggunakan <strong>password default</strong>. 
                        Silakan <a href="<?= base_url('update_password') ?>" style="color: #000; text-decoration: underline; font-weight: bold;">ubah kata sandi</a> sekarang.
                    </div>
                </div>
            <?php endif; ?>

            <!-- Isi Konten -->
            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-12">
                        <div class="row">
                            <!-- MAHASISWA MAGANG AKTIF -->
                                <div class="col-6 col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-4 d-flex justify-content-start">
                                                    <div class="stats-icon blue mb-2">
                                                        <i class="iconly-boldWork"></i>
                                                    </div>
                                                </div>
                                                <div class="col-8">
                                                    <h6 class="text-muted font-semibold mb-1">
                                                        Mahasiswa Magang Aktif
                                                    </h6>
                                                    <h6 class="font-extrabold mb-0">28</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- MAHASISWA MAGANG SELESAI -->
                                <div class="col-6 col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-4 d-flex justify-content-start">
                                                    <div class="stats-icon green mb-2">
                                                        <i class="iconly-boldProfile"></i>
                                                    </div>
                                                </div>
                                                <div class="col-8">
                                                    <h6 class="text-muted font-semibold mb-1">
                                                        Mahasiswa Magang Selesai
                                                    </h6>
                                                    <h6 class="font-extrabold mb-0">64</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- LP PENDING -->
                                <div class="col-6 col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-4 d-flex justify-content-start">
                                                    <div class="stats-icon purple mb-2">
                                                        <i class="iconly-boldPaper"></i>
                                                    </div>
                                                </div>
                                                <div class="col-8">
                                                    <h6 class="text-muted font-semibold mb-1">
                                                        LP Pending
                                                    </h6>
                                                    <h6 class="font-extrabold mb-0">7</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PRESENSI PENDING -->
                                <div class="col-6 col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-4 d-flex justify-content-start">
                                                    <div class="stats-icon red mb-2">
                                                        <i class="iconly-boldCalendar"></i>
                                                    </div>
                                                </div>
                                                <div class="col-8">
                                                    <h6 class="text-muted font-semibold mb-1">
                                                        Presensi Pending
                                                    </h6>
                                                    <h6 class="font-extrabold mb-0">12</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- LOGBOOK PENDING -->
                                <div class="col-6 col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-4 d-flex justify-content-start">
                                                    <div class="stats-icon orange mb-2">
                                                        <i class="iconly-boldDocument"></i>
                                                    </div>
                                                </div>
                                                <div class="col-8">
                                                    <h6 class="text-muted font-semibold mb-1">
                                                        Logbook Pending
                                                    </h6>
                                                    <h6 class="font-extrabold mb-0">9</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Footer -->
            <footer class="footer clearfix mb-0 text-muted" style="position: fixed; bottom: 0; width: 100%; padding: 5px 10px;">
                <div class="float-start">
                    <p>2025 &copy; Institut Widya Pratama</p>
                </div>
            </footer>
        </div>
    </div>

    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/compiled/js/app.js"></script>

    <script>
        // Sidebar Toggle
        document.getElementById("sidebarToggle").addEventListener("click", function (e) {
            e.preventDefault();
            document.querySelector(".sidebar-wrapper").classList.toggle("active");
        });
    </script>
</body>
</html>
