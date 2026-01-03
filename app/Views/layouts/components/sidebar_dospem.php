<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

                        <!-- LOGO -->
                        <div class="logo">
                            <a href="<?= base_url('dospem/dashboard') ?>">
                                <img src="./assets/images/iwima.png" alt="Logo">
                            </a>
                        </div>

                        <!-- Tema terang/gelap -->
                        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                 preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                          opacity=".3"></path>
                                    <g transform="translate(-210 -1)">
                                        <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                        <circle cx="220.5" cy="11.5" r="4"></circle>
                                        <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                                    </g>
                                </g>
                            </svg>

                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor:pointer">
                                <label class="form-check-label"></label>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                 preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                      d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                                </path>
                            </svg>
                        </div>

                        <!-- Tombol close sidebar -->
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block">
                                <i class="bi bi-x bi-middle"></i>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Sidebar Menu -->
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item <?= (uri_string() == 'dospem/dashboard') ? 'active' : '' ?>">
                            <a href="<?= base_url('dospem/dashboard') ?>" class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?= (uri_string() == 'dospem/data_mahasiswa') ? 'active' : '' ?>">
                            <a href="<?= base_url('dospem/data_mahasiswa') ?>" class="sidebar-link">
                                <i class="bi bi-people-fill"></i>
                                <span>Data Mahasiswa</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?= (uri_string() == 'dospem/data_presensi') ? 'active' : '' ?>">
                            <a href="<?= base_url('dospem/data_presensi') ?>" class="sidebar-link">
                                <i class="bi bi-clipboard-check"></i>
                                <span>Data Presensi</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?= (uri_string() == 'dospem/data_learning_plan') ? 'active' : '' ?>">
                            <a href="<?= base_url('dospem/data_learning_plan') ?>" class="sidebar-link">
                                <i class="bi bi-journal-bookmark"></i>
                                <span>Data Learning Plan</span>
                            </a>
                        </li>

                        <li class="sidebar-item has-sub <?= (in_array(uri_string(), ['dospem/bimbingan_magang', 'dospem/bimbingan_asb', 'dospem/bimbingan_dsib', 'dospem/bimbingan_kombis'])) ? 'active' : '' ?>">
                            <a href="#" class="sidebar-link">
                                <i class="bi bi-people"></i>
                                <span>Bimbingan</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item <?= (uri_string() == 'dospem/bimbingan_magang') ? 'active' : '' ?>">
                                    <a href="<?= base_url('dospem/bimbingan_magang') ?>" class="submenu-link">Magang</a>
                                </li>
                                <!-- DINONAKTIFKAN -->
                                <!--
                                <li class="submenu-item <?= (uri_string() == 'dospem/bimbingan_asb') ? 'active' : '' ?>">
                                    <a href="<?= base_url('dospem/bimbingan_asb') ?>" class="submenu-link">Analisis Sistem Informasi Bisnis</a>
                                </li>

                                <li class="submenu-item <?= (uri_string() == 'dospem/bimbingan_dsib') ? 'active' : '' ?>">
                                    <a href="<?= base_url('dospem/bimbingan_dsib') ?>" class="submenu-link">Desain Sistem Informasi Bisnis</a>
                                </li>

                                <li class="submenu-item <?= (uri_string() == 'dospem/bimbingan_kombis') ? 'active' : '' ?>">
                                    <a href="<?= base_url('dospem/bimbingan_kombis') ?>" class="submenu-link">Komunikasi Bisnis</a>
                                </li>
                                -->
                            </ul>
                        </li>

                        <li class="sidebar-item <?= (uri_string() == 'dospem/kelola_penilaian') ? 'active' : '' ?>">
                            <a href="<?= base_url('dospem/kelola_penilaian') ?>" class="sidebar-link">
                                <i class="bi bi-clipboard-check"></i>
                                <span>Kelola Penilaian</span>
                            </a>
                        </li>

                        <!-- <li class="sidebar-item <?= (uri_string() == 'dospem/arsip_mahasiswa') ? 'active' : '' ?>">
                            <a href="<?= base_url('dospem/arsip_mahasiswa') ?>" class="sidebar-link">
                                <i class="bi bi-archive"></i>
                                <span>Arsip Mahasiswa</span>
                            </a>
                        </li> -->


                    </ul>
                </div>

            </div>
        </div>
    </div>

    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <?php 
    $current = uri_string();

    if (
        $current != 'dospem/data_mahasiswa' && 
        $current != 'dospem/kelola_penilaian' &&
        $current != 'dospem/arsip_mahasiswa'
    ): ?>
            
        <script src="assets/compiled/js/app.js"></script>

    <?php endif; ?>



    <script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
    <script src="assets/static/js/pages/dashboard.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const subMenus = document.querySelectorAll(".sidebar-item.has-sub");

        subMenus.forEach((item) => {
            const toggle = item.querySelector(".sidebar-toggle");
            const activeChild = item.querySelector(".submenu-item.active");

            // 🔹 Jika ada submenu aktif, buka otomatis saat halaman dimuat
            if (activeChild) {
                item.classList.add("submenu-open");
            }

            // 🔹 Event klik untuk buka/tutup submenu
            if (toggle) {
                toggle.addEventListener("click", (e) => {
                    e.preventDefault();

                    const isOpen = item.classList.contains("submenu-open");

                    // Tutup semua submenu lain
                    subMenus.forEach((other) => {
                        if (other !== item) {
                            other.classList.remove("submenu-open");
                        }
                    });

                    // Toggle buka/tutup submenu yang diklik
                    if (!isOpen) {
                        item.classList.add("submenu-open");
                    }
                });
            }
        });
    });
    </script>


</body>
</html>
