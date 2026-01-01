<?= $this->extend('layouts/mitra') ?>

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
                                    <li class="breadcrumb-item"><a href="<?= base_url('mitra/kelola_learning_plan') ?>">Kelola Learning Plan</a></li>
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

                <!-- STEP 2: INFORMASI PERUSAHAAN -->
                <div class="form-step">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Informasi Perusahaan</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Perusahaan</label>
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

                <!-- STEP 3: INFORMASI MAGANG -->
                <div class="form-step">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Informasi Magang</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" value="<?= $profil['tanggal_mulai'] ?? '' ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
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
                        Deskripsi posisi dan uraian pekerjaan selama magang:
                    </p>
                    <textarea class="form-control mb-3" rows="5" readonly><?= esc($learningPlan['deskripsi_pekerjaan'] ?? '') ?></textarea>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary btn-sm fw-bold prev-step">Previous</button>
                        <button type="button" class="btn btn-primary btn-sm fw-bold next-step">Next</button>
                    </div>
                </div>

                <!-- STEP 5: CAPAIAN PEMBELAJARAN MAGANG -->
                <div class="form-step">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Capaian Pembelajaran Magang</h6>
                    <textarea class="form-control mb-3" rows="5" readonly><?= $learningPlan['capaian_magang'] ?? 'Setelah melaksanakan kegiatan magang, mahasiswa mempunyai pengalaman dan kemampuan dalam merekap data/mengelola aplikasi, menganalisis permasalahan, memberikan solusi, serta merancang sistem/aplikasi baru.' ?></textarea>

                    <h6 class="fw-bold mt-3">Aktivitas Pembelajaran Magang</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Kompetensi Teknis</th>
                                    <th>Pengalaman</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $checkedMagang = isset($aktivitas) ? array_column(array_filter($aktivitas, fn($a) => $a['tipe'] == 'Magang'), 'urutan') : [];
                                $kompetensi = [
                                    1 => 'Kemampuan dalam merekam data/mengelola aplikasi dalam organisasi',
                                    2 => 'Kemampuan menganalisis permasalahan sistem berjalan',
                                    3 => 'Kemampuan menganalisis kebutuhan sistem baru',
                                    4 => 'Kemampuan mengevaluasi proses bisnis organisasi',
                                    5 => 'Kemampuan merancang sistem/aplikasi baru atau mengembangkan yang sudah ada',
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
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Capaian Pembelajaran Mata Kuliah</h6>
                    <textarea class="form-control mb-3" rows="5" readonly><?= $learningPlan['capaian_mata_kuliah'] ?? 'Mahasiswa mampu memahami dan membuat makalah komunikasi bisnis, menganalisis sistem, dan mendesain sistem menggunakan tools tertentu.' ?></textarea>

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
                                    1 => 'Kemampuan memahami jalur komunikasi antara pimpinan dan staf dalam organisasi',
                                    2 => 'Kemampuan menganalisis sistem yang berjalan untuk mengetahui permasalahan dan kebutuhan sistem baru',
                                    3 => 'Kemampuan menggambarkan sistem berjalan menggunakan tools tertentu (Flowchart/UML)',
                                    4 => 'Kemampuan mendesain sistem/aplikasi baru menggunakan tools tertentu (Storyboard/UML/LKT)',
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

                        <a href="<?= base_url('mitra/kelola_learning_plan') ?>" class="btn btn-sm btn-primary fw-bold me-2">
                            Kembali
                        </a>

                        <?php if ($learningPlan['status_approval_pembimbing'] == 'Menunggu'): ?>
                            <!-- ✅ Tombol Setujui dengan form POST -->
                                <form action="<?= base_url('mitra/kelola_learning_plan/setuju/' . $learningPlan['id_lp']) ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-circle"></i> Setujui
                                    </button>
                                </form>

                                <!-- 🔻 Tombol Tolak (buka modal) -->
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalReject">
                                    <i class="bi bi-x-circle"></i> Tolak
                                </button>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Modal Reject -->
                <div class="modal fade" id="modalReject" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="<?= base_url('mitra/kelola_learning_plan/tolak/' . $learningPlan['id_lp']) ?>" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title">Tolak Learning Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan Pembimbing Mitra</label>
                            <textarea name="catatan_pembimbing" id="catatan" class="form-control" rows="3" required></textarea>
                        </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak</button>
                        </div>
                    </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT -->
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

    showStep(currentStep); // tampilkan STEP 1 saat load
});

</script>


<?= $this->endSection() ?>
