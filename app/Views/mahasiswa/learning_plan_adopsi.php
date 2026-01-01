<?= $this->extend('layouts/mhs') ?>

<?= $this->section('title') ?>
Input Learning Plan
<?= $this->endSection() ?>

<!-- === CSS Import (sama seperti halaman presensi) === -->
<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<?= $this->section('content') ?>

<style>
.btn i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    line-height: 1;
    vertical-align: middle;
    margin-top: -1px;
}
.form-step { display: none; }
.form-step.active { display: block; }

.card-body {
    padding-top: 0rem !important; /* Kurangi jarak atas isi card */
}

.container.mt-4 {
    margin-top: 0rem !important; /* Kurangi jarak antara card dengan header */
}

.table-responsive {
    margin-top: 0.5rem !important; /* Kurangi jarak atas tabel */
}

</style>

<!-- Page Heading & Breadcrumb -->
<div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Learning Plan</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('mahasiswa/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Learning Plan</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <!-- <h5 class="card-title mb-0 fw-bold">Learning Plan / Rencana Kegiatan Magang</h5> -->
    </div>

    <!-- 🔔 ALERT FLASHDATA -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-light-success color-success d-flex align-items-center justify-content-between">
            <div><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success'); ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif(session()->getFlashdata('error')): ?>
        <div class="alert alert-light-danger color-danger d-flex align-items-center justify-content-between">
            <div><i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error'); ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <!-- END ALERT -->


    <div class="card-body">
        <div class="container mt-4">
            <div class="card shadow-sm border-0">
                <!-- STEP 0 & 1: STATUS APPROVAL + INFORMASI MAHASISWA -->
                <div class="form-step active">

                    <!-- STATUS APPROVAL -->
                    <?php if (!empty($learningPlan) && 
                        $learningPlan['status_approval_pembimbing'] !== 'Draft' && 
                        $learningPlan['status_approval_kaprodi'] !== 'Draft'): ?>

                        <h6 class="fw-bold border-bottom pb-2 mb-3">Status Learning Plan</h6>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle text-center" id="table1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Status Pembimbing</th>
                                            <th>Catatan Pembimbing</th>
                                            <th>Status Kaprodi</th>
                                            <th>Catatan Kaprodi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>

                                            <!-- STATUS PEMBIMBING -->
                                            <td>
                                                <?php if ($learningPlan['status_approval_pembimbing'] == 'Disetujui'): ?>
                                                    <span class="badge bg-success">Disetujui</span>
                                                <?php elseif ($learningPlan['status_approval_pembimbing'] == 'Menunggu'): ?>
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                <?php elseif ($learningPlan['status_approval_pembimbing'] == 'Ditolak'): ?>
                                                    <span class="badge bg-danger">Ditolak</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Draft</span>
                                                <?php endif; ?>
                                            </td>

                                            <td><?= ($learningPlan['status_approval_pembimbing'] === 'Draft' || $learningPlan['status_approval_pembimbing'] === 'Menunggu') ? '-' : esc($learningPlan['catatan_pembimbing'] ?? '-') ?></td>

                                            <!-- STATUS KAPRODI -->
                                            <td>
                                                <?php if ($learningPlan['status_approval_kaprodi'] == 'Disetujui'): ?>
                                                    <span class="badge bg-success">Disetujui</span>
                                                <?php elseif ($learningPlan['status_approval_kaprodi'] == 'Menunggu'): ?>
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                <?php elseif ($learningPlan['status_approval_kaprodi'] == 'Ditolak'): ?>
                                                    <span class="badge bg-danger">Ditolak</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Draft</span>
                                                <?php endif; ?>
                                            </td>

                                            <td><?= ($learningPlan['status_approval_kaprodi'] === 'Draft' || $learningPlan['status_approval_kaprodi'] === 'Menunggu') ? '-' : esc($learningPlan['catatan_kaprodi'] ?? '-') ?></td>
                                            <!-- AKSI (TOMBOL CETAK) -->
                                            <td>
                                                <?php 
                                                $pmb = $learningPlan['status_approval_pembimbing'];
                                                $kprd = $learningPlan['status_approval_kaprodi'];
                                                ?>

                                                <?php if ($pmb === 'Disetujui' && $kprd === 'Disetujui'): ?>
                                                    <a href="<?= base_url('mahasiswa/learningplan/cetak/' . $learningPlan['id_lp']) ?>" 
                                                    target="_blank"
                                                    class="btn btn-sm btn-success fw-bold">
                                                        <i class="bi bi-printer"></i> Cetak
                                                    </a>
                                                <?php else: ?>
                                                    <small class="text-muted">-</small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>


                    <form id="learningPlanForm" action="<?= base_url('mahasiswa/learningplan/storeAdopsi') ?>" method="post">
                    <input type="hidden" name="id_profil" value="<?= $profil['id_profil'] ?>">

                        <!-- INFORMASI MAHASISWA -->
                        <h6 class="fw-bold border-bottom pb-2 mb-3">Informasi Mahasiswa</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIM</label>
                                <input type="text" class="form-control" value="<?= $profil['nim'] ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" value="<?= $profil['nama_lengkap'] ?? '-' ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?= $profil['email'] ?? '-' ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Telp/Hp</label>
                                <input type="text" class="form-control" value="<?= $profil['handphone'] ?? '-' ?>" readonly>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea class="form-control" rows="2" readonly><?= $profil['alamat'] ?? '-' ?></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Program Studi</label>
                                <input type="text" class="form-control" value="<?= $profil['program_studi'] ?? '-' ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Semester</label>
                                <input type="text" class="form-control" value="<?= $profil['semester'] ?? '-' ?>" readonly>
                            </div>
                        </div>

                        <!-- TOMBOL NEXT -->
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-primary btn-sm fw-bold next-step">Next</button>
                        </div>
                    </div>

            <!-- STEP 2: INFORMASI SEKOLAH -->
            <div class="form-step">
                <h6 class="fw-bold border-bottom pb-2 mb-3">Informasi Sekolah</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Sekolah/Instansi</label>
                        <input type="text" class="form-control" value="<?= $profil['nama_mitra'] ?? '-' ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bagian/Unit</label>
                        <input type="text" class="form-control" value="<?= $profil['nama_unit'] ?? '-' ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Penanggung Jawab</label>
                        <input type="text" class="form-control" value="<?= $profil['nama_pembimbing'] ?? '-' ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="<?= $profil['unit_email'] ?? '-' ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nomor Telp/Hp</label>
                        <input type="text" class="form-control" value="<?= $profil['unit_no_hp'] ?? '-' ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" rows="2" readonly><?= $profil['mitra_alamat'] ?? '-' ?></textarea>
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary btn-sm fw-bold prev-step">Previous</button>
                    <button type="button" class="btn btn-primary btn-sm fw-bold next-step">Next</button>
                </div>
            </div>

            <!-- STEP 3: INFORMASI IWIMA MENGAJAR -->
            <div class="form-step">
                <h6 class="fw-bold border-bottom pb-2 mb-3">Informasi IWIMA Mengajar</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="tanggal_mulai"
                            value="<?= $profil['tanggal_mulai'] ?? '' ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tanggal_selesai"
                            value="<?= $profil['tanggal_selesai'] ?? '' ?>" readonly>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Jam Kerja (Hari dan Waktu)</label>
                        <textarea name="jam_kerja" class="form-control" rows="2" readonly><?= $jamKerja ?? ($profil['keterangan'] ?? '') ?></textarea>
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary btn-sm fw-bold prev-step">Previous</button>
                    <button type="button" class="btn btn-primary btn-sm fw-bold next-step">Next</button>
                </div>
            </div>


            <!-- STEP 4: DESKRIPSI PEKERJAAN -->
            <div class="form-step">
                <h6 class="fw-bold border-bottom pb-2 mb-3">Deskripsi Pekerjaan</h6>
                <p class="text-muted mb-3" style="font-size: 0.9rem;">
                    Deskripsikan <strong>Tema Adopsi Teknologi</strong> yang <strong>Akan Dibangun</strong>.
                </p>

                <textarea 
                    name="deskripsi_pekerjaan" 
                    id="deskripsi_pekerjaan"
                    class="form-control mb-3" 
                    rows="5"
                    placeholder="Deskripsikan kegiatan magang..."
                    <?= $isReadonly ? 'readonly' : '' ?>><?= esc($learningPlan['deskripsi_pekerjaan'] ?? '') ?></textarea>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary btn-sm fw-bold prev-step">Previous</button>
                    <button type="button" class="btn btn-primary btn-sm fw-bold next-step">Next</button>
                </div>
            </div>


          <!-- STEP 5: CAPAIAN PEMBELAJARAN MAGANG -->
            <div class="form-step">
            <h6 class="fw-bold border-bottom pb-2 mb-3">
            Capaian Pembelajaran Mata Kuliah IWIMA Mengajar Skema Adopsi Teknologi
            </h6>
            <textarea style="text-align: justify;" name="capaian_magang" class="form-control mb-3" rows="5" readonly><?= $learningPlan['capaian_magang'] ??'Setelah melaksanakan kegiatan magang, mahasiswa mempunyai pengalaman dan kemampuan dalam merekap data/mengelola sebuah aplikasi, menganalisis permasalahan yang berkaitan dengan bidang teknik informatika/sistem informasi, memberikan solusi alternatif atas permasalahan yang terjadi, menganalisis kebutuhan sistem baru/aplikasi baru, merancang sistem/aplikasi baru atau pengembangan sistem/aplikasi yang sudah ada, dan membuat laporan magang.' ?>
            </textarea>

                <h6 class="fw-bold mt-3">Aktivitas Pembelajaran Magang</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 65%;">Kompetensi Teknis</th>
                                <th style="width: 30%;">Pengalaman</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $checkedMagang = isset($aktivitas) ? array_column(array_filter($aktivitas, fn($a) => $a['tipe'] == 'Magang'), 'urutan') : [];
                            ?>
                            <?php 
                            $kompetensi = [
                                1 => 'Kemampuan dalam merekam data/mengelola sebuah aplikasi dalam organisasi',
                                2 => 'Kemampuan dalam menganalisis permasalahan pada sistem yang berjalan yang berkaitan dengan Teknik Informatika/Sistem Informasi',
                                3 => 'Kemampuan dalam menganalisis kebutuhan sistem/persyaratan sistem baru/aplikasi baru yang dibutuhkan organisasi',
                                4 => 'Kemampuan dalam mengevaluasi proses bisnis dan strategi Sekolah untuk merancang sistem bagi organisasi',
                                5 => 'Kemampuan dalam merancang sistem baru/aplikasi baru atau pengembangan sistem/aplikasi yang sudah ada sesuai dengan kebutuhan organisasi',
                            ];
                            for ($i = 1; $i <= 5; $i++): 
                            ?>
                            <tr>
                                <td class="text-center"><?= $i ?></td>
                                <td><?= $kompetensi[$i] ?></td>
                                <td class="text-center">
                                    <input type="checkbox" name="dicentang[<?= $i ?>]" value="1"
                                        <?= in_array($i, $checkedMagang) ? 'checked' : '' ?>
                                        <?= $isReadonly ? 'disabled' : '' ?>>
                                </td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary btn-sm fw-bold prev-step">Previous</button>
                    <button type="button" class="btn btn-primary btn-sm fw-bold next-step">Next</button>
                </div>
            </div>


            <!-- STEP 6: CAPAIAN MK KHUSUS -->
            <div class="form-step">
                <h6 class="fw-bold border-bottom pb-2 mb-3">
                    Capaian Pembelajaran IWIMA Mengajar Skema Adopsi Teknologi Di Sekolah/Instansi.
                </h6>
                <textarea name="capaian_mk_khusus" class="form-control mb-3" rows="5" readonly><?= $learningPlan['capaian_mata_kuliah'] ?? 'Setelah melaksanakan kegiatan magang, mahasiswa mempunyai kemampuan untuk memahami dan membuat makalah komunikasi bisnis.' ?>
                </textarea>

                <h6 class="fw-bold mt-3">Aktivitas Pembelajaran Mata Kuliah Komunikasi Bisnis, Analisis Sistem dan Desain Sistem, Pembangunan Sistem di Sekolah</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 65%;">Kompetensi Teknis</th>
                                <th style="width: 30%;">Keterkaitan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $checkedMk = isset($aktivitas) ? array_column(array_filter($aktivitas, fn($a) => $a['tipe'] == 'Mata Kuliah'), 'urutan') : [];
                            $kompetensiMk = [
                                1 => 'Kemampuan memahami jalur komunikasi antara pimpinan dan staff dalam organisasi',
                                2 => 'Kemampuan menganalisis sistem yang berjalan dalam Sekolah/instansi guna mengetahui permasalahan dan persyaratan sistem baru/hasil pengembangan dari sistem yang sudah ada',
                                3 => 'Kemampuan menggambarkan sistem yang berjalan dengan menggunakan tools tertentu (Flowchart/UML)',
                                4 => 'Kemampuan mendesain sistem baru/aplikasi baru dengan menggunakan tools tertentu (Flowchart/Storyboard/UML/LKT)',
                                5 => 'Kemampuan membangun sistem berdasarkan desain sistem yang dihasilkan'
                            ];
                            ?>
                            <?php foreach ($kompetensiMk as $i => $teks): ?>
                            <tr>
                                <td class="text-center"><?= $i ?></td>
                                <td><?= $teks ?></td>
                                <td class="text-center">
                                    <input type="checkbox" name="mk_khusus[<?= $i ?>]" value="1"
                                    <?= in_array($i, $checkedMk) ? 'checked' : '' ?>
                                    <?= $isReadonly ? 'disabled' : '' ?>>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (!$isReadonly): ?>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary btn-sm fw-bold prev-step">Previous</button>
                        <button type="submit" name="action" value="draft" class="btn btn-warning btn-sm fw-bold">
                            <i class="bi bi-file-earmark"></i> Draft
                        </button>
                        <button type="submit" name="action" value="submit" class="btn btn-primary btn-sm fw-bold">
                            <i class="bi bi-send"></i> Kirim
                        </button>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info text-center mt-3">
                        Form ini tidak dapat diedit karena sudah dikirim untuk persetujuan.
                    </div>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>

<!-- SCRIPT -->
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script> 
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
const steps = document.querySelectorAll(".form-step");
const nextBtns = document.querySelectorAll(".next-step");
const prevBtns = document.querySelectorAll(".prev-step");
let currentStep = 0;

nextBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        steps[currentStep].classList.remove("active");
        currentStep++;
        steps[currentStep].classList.add("active");
    });
});
prevBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        steps[currentStep].classList.remove("active");
        currentStep--;
        steps[currentStep].classList.add("active");
    });
});
</script>

<script>
    // Validasi deskripsi pekerjaan saat tombol "Kirim" ditekan
    document.querySelector('button[name="action"][value="submit"]')?.addEventListener('click', function (e) {
        const deskripsi = document.getElementById('deskripsi_pekerjaan');
        if (deskripsi && deskripsi.value.trim() === '') {
            e.preventDefault();
            alert('Deskripsi pekerjaan wajib diisi sebelum mengirim data.');
        }
    });

</script>

<?= $this->endSection() ?>
