<?= $this->extend('layouts/dospem') ?>

<?= $this->section('title') ?>
Detail Learning Plan Mahasiswa
<?= $this->endSection() ?>

<!-- CSS -->
<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<?= $this->section('content') ?>

<style>
.form-step { display: none; }
.form-step.active { display: block; }

.card-body {
    padding-top: 0rem !important;
}

.container.mt-4 {
    margin-top: 0rem !important;
}

.table-responsive {
    margin-top: 0.5rem !important;
}

input[readonly], textarea[readonly] {
    background-color: #f8f9fa !important;
}

input[type="checkbox"][disabled] {
    cursor: not-allowed;
}
</style>

<!-- Page Heading & Breadcrumb -->
<div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Detail Learning Plan</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('dospem/data_learning_plan') ?>">Data Learning Plan</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Detail Learning Plan</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold">Learning Plan <?= esc($profil['nama_lengkap']) ?></h5>
    </div>

    <div class="card-body">
        <div class="container mt-4">
            <div class="card shadow-sm border-0">
                <!-- STEP 1: INFORMASI MAHASISWA -->
                <div class="form-step active">
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
                            <input type="date" class="form-control" value="<?= $profil['tanggal_mulai'] ?? '' ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" value="<?= $profil['tanggal_selesai'] ?? '' ?>" readonly>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Jam Kerja (Hari dan Waktu)</label>
                            <textarea class="form-control" rows="2" readonly><?= $jamKerja ?? ($profil['keterangan'] ?? '') ?></textarea>
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
                    <textarea name="deskripsi_pekerjaan" id="deskripsi_pekerjaan"
                        class="form-control mb-3" rows="5" readonly><?= esc($learningPlan['deskripsi_pekerjaan'] ?? '') ?></textarea>
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
                    <textarea style="text-align: justify;" name="capaian_magang" class="form-control mb-3" rows="5" readonly><?= $learningPlan['capaian_magang'] ?? 'Setelah melaksanakan kegiatan magang...' ?></textarea>

                    <h6 class="fw-bold mt-3">Aktivitas Pembelajaran Magang</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="width:5%;">No</th>
                                    <th style="width:65%;">Kompetensi Teknis</th>
                                    <th style="width:30%;">Pengalaman</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $checkedMagang = isset($aktivitas) ? array_column(array_filter($aktivitas, fn($a) => $a['tipe'] == 'Magang'), 'urutan') : [];
                                $kompetensi = [
                                    1 => 'Kemampuan dalam merekam data/mengelola aplikasi',
                                    2 => 'Kemampuan dalam menganalisis permasalahan sistem',
                                    3 => 'Kemampuan menganalisis kebutuhan sistem baru',
                                    4 => 'Kemampuan mengevaluasi proses bisnis organisasi',
                                    5 => 'Kemampuan merancang atau mengembangkan sistem'
                                ];
                                foreach ($kompetensi as $i => $teks): ?>
                                <tr>
                                    <td class="text-center"><?= $i ?></td>
                                    <td><?= $teks ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" <?= in_array($i, $checkedMagang) ? 'checked' : '' ?> disabled>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
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
                        Capaian Pembelajaran IWIMA Mengajar Skema Adopsi Teknologi Di Sekolah/Instansi
                    </h6>
                    <textarea name="capaian_mk_khusus" class="form-control mb-3" rows="5" readonly><?= $learningPlan['capaian_mata_kuliah'] ?? 'Setelah melaksanakan kegiatan magang...' ?></textarea>

                    <h6 class="fw-bold mt-3">Aktivitas Pembelajaran Mata Kuliah</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Kompetensi Teknis</th>
                                    <th>Keterkaitan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $checkedMk = isset($aktivitas) ? array_column(array_filter($aktivitas, fn($a) => $a['tipe'] == 'Mata Kuliah'), 'urutan') : [];
                                $kompetensiMk = [
                                    1 => 'Kemampuan memahami jalur komunikasi organisasi',
                                    2 => 'Kemampuan menganalisis sistem berjalan',
                                    3 => 'Kemampuan menggambarkan sistem dengan tools',
                                    4 => 'Kemampuan mendesain sistem baru/aplikasi',
                                    5 => 'Kemampuan membangun sistem berdasarkan desain'
                                ];
                                foreach ($kompetensiMk as $i => $teks): ?>
                                <tr>
                                    <td class="text-center"><?= $i ?></td>
                                    <td><?= $teks ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" <?= in_array($i, $checkedMk) ? 'checked' : '' ?> disabled>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary btn-sm fw-bold prev-step">Previous</button>
                        <a href="<?= base_url('dospem/data_learning_plan') ?>" class="btn btn-sm btn-primary fw-bold me-2">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/compiled/js/app.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const steps = document.querySelectorAll(".form-step");
    const nextBtns = document.querySelectorAll(".next-step");
    const prevBtns = document.querySelectorAll(".prev-step");
    let currentStep = 0;

    function showStep(index) {
        steps.forEach((step, i) => step.classList.toggle('active', i === index));
    }

    nextBtns.forEach(btn => btn.addEventListener("click", () => {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    }));

    prevBtns.forEach(btn => btn.addEventListener("click", () => {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    }));

    showStep(currentStep);
});
</script>

<?= $this->endSection() ?>
