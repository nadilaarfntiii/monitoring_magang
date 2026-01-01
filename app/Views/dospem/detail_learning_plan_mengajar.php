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

                <!-- STEP 2: SATUAN PENDIDIKAN -->
                <div class="form-step">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Satuan Pendidikan</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Sekolah</label>
                            <input type="text" class="form-control" value="<?= $profil['nama_mitra'] ?? '-' ?>" readonly>
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
                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" rows="2" readonly><?= $profil['mitra_alamat'] ?? '-' ?></textarea>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary btn-sm fw-bold prev-step">Previous</button>
                        <button type="button" class="btn btn-primary btn-sm fw-bold next-step">Next</button>
                    </div>
                </div>

                <!-- STEP 3: INFORMASI TEACHING ASSISTANT -->
                <div class="form-step">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Informasi Teaching Assistant</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="tanggal_mulai" value="<?= $profil['tanggal_mulai'] ?? '' ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" name="tanggal_selesai" value="<?= $profil['tanggal_selesai'] ?? '' ?>" readonly>
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

                <!-- STEP 4: RENCANA KEGIATAN -->
                <div class="form-step">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">A. Rencana Kegiatan Pembelajaran</h6>

                    <div class="mb-3">
                        <label for="nama_kegiatan" class="form-label fw-bold">Nama Kegiatan / Program Kerja</label>
                        <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" readonly
                            value="<?= esc($learningPlan['nama_kegiatan'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="pelaksana_kegiatan" class="form-label fw-bold">Pelaksana Kegiatan</label>
                        <select name="pelaksana_kegiatan" id="pelaksana_kegiatan" class="form-select" disabled>
                            <option value="">-- Pilih --</option>
                            <option value="Individu" <?= (isset($learningPlan['pelaksana_kegiatan']) && $learningPlan['pelaksana_kegiatan'] == 'Individu') ? 'selected' : '' ?>>Individu</option>
                            <option value="Kelompok" <?= (isset($learningPlan['pelaksana_kegiatan']) && $learningPlan['pelaksana_kegiatan'] == 'Kelompok') ? 'selected' : '' ?>>Kelompok</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="uraian_kegiatan" class="form-label fw-bold">Uraian Kegiatan</label>
                        <textarea name="uraian_kegiatan" id="uraian_kegiatan" class="form-control" rows="5" readonly><?= esc($learningPlan['uraian_kegiatan'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="metode_media" class="form-label fw-bold">Metode dan Media yang Digunakan</label>
                        <textarea name="metode_media" id="metode_media" class="form-control" rows="5" readonly><?= esc($learningPlan['metode_media'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="rtl_kegiatan" class="form-label fw-bold">Rencana Tindak Lanjut (RTL)</label>
                        <textarea name="rtl_kegiatan" id="rtl_kegiatan" class="form-control" rows="5" readonly><?= esc($learningPlan['rtl_kegiatan'] ?? '') ?></textarea>
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold border-bottom pb-2 mb-3">B. Uraian Menggunakan Metode STAR</h6>
                    <p class="text-muted" style="font-size: 0.9rem;">
                        Jelaskan kegiatan Anda menggunakan metode <strong>STAR</strong> (Situation, Task, Action, Result).
                    </p>

                    <textarea name="situation" id="situation" class="form-control mb-3" rows="4" readonly><?= esc($learningPlan['situation'] ?? '') ?></textarea>
                    <textarea name="task" id="task" class="form-control mb-3" rows="4" readonly><?= esc($learningPlan['task'] ?? '') ?></textarea>
                    <textarea name="action" id="action" class="form-control mb-3" rows="4" readonly><?= esc($learningPlan['action'] ?? '') ?></textarea>
                    <textarea name="result" id="result" class="form-control mb-3" rows="4" readonly><?= esc($learningPlan['result'] ?? '') ?></textarea>

                    <div class="text-end">
                        <button type="button" class="btn btn-secondary btn-sm fw-bold prev-step">Previous</button>
                        <button type="button" class="btn btn-primary btn-sm fw-bold next-step">Next</button>
                    </div>
                </div>

                <!-- STEP 5: CAPAIAN PEMBELAJARAN -->
                <div class="form-step">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">
                        Capaian Pembelajaran Mata Kuliah Magang (IWIMA Mengajar Skema Teaching Assistant)
                    </h6>

                    <textarea name="capaian_magang" class="form-control mb-3" rows="5" readonly><?= $learningPlan['capaian_magang'] ?? 'Setelah melaksanakan kegiatan magang, mahasiswa mempunyai pengalaman dan kemampuan dalam bidang pendidikan untuk turut serta mengajarkan dan memperdalam ilmunya dengan cara menjadi guru di satuan pendidikan.' ?></textarea>

                    <h6 class="fw-bold mt-3">Aktivitas Pembelajaran Magang</h6>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Kompetensi</th>
                                    <th>Kompetensi Teknis</th>
                                    <th>Pengalaman</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $checkedMagang = isset($aktivitas) 
                                    ? array_column(array_filter($aktivitas, fn($a) => $a['tipe'] == 'Magang'), 'urutan') 
                                    : [];

                                $hardSkills = [
                                    'Kemampuan menyusun RPP sesuai kurikulum dan kebutuhan peserta didik',
                                    'Kemampuan melaksanakan pengajaran sesuai bidang kompetensi',
                                    'Penguasaan materi ajar',
                                    'Kemampuan mengintegrasikan teknologi dalam pengajaran',
                                    'Kemampuan menghasilkan laporan dari aktivitas magang'
                                ];
                                $softSkills = [
                                    'Kemampuan berinteraksi dengan peserta didik',
                                    'Kemampuan bekerjasama dalam tim',
                                    'Kepemimpinan, kedisiplinan, dan tanggung jawab dalam melaksanakan tugas'
                                ];

                                $no = 1;
                                $rowspanHard = count($hardSkills);
                                $rowspanSoft = count($softSkills);
                                $urutan = 1;
                                ?>

                                <!-- Hard Skills -->
                                <?php foreach ($hardSkills as $index => $teks): ?>
                                <tr>
                                    <?php if ($index === 0): ?>
                                        <td rowspan="<?= $rowspanHard ?>" class="text-center align-middle fw-bold"><?= $no ?></td>
                                        <td rowspan="<?= $rowspanHard ?>" class="align-middle">Hard Skills</td>
                                    <?php endif; ?>
                                    <td><?= $teks ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" name="dicentang[<?= $urutan ?>]" value="1"
                                            <?= in_array($urutan, $checkedMagang) ? 'checked' : '' ?>
                                            disabled>
                                    </td>
                                </tr>
                                <?php $urutan++; endforeach; ?>

                                <!-- Soft Skills -->
                                <?php 
                                $no++;
                                foreach ($softSkills as $index => $teks): 
                                ?>
                                <tr>
                                    <?php if ($index === 0): ?>
                                        <td rowspan="<?= $rowspanSoft ?>" class="text-center align-middle fw-bold"><?= $no ?></td>
                                        <td rowspan="<?= $rowspanSoft ?>" class="align-middle">Soft Skills</td>
                                    <?php endif; ?>
                                    <td><?= $teks ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" name="dicentang[<?= $urutan ?>]" value="1"
                                            <?= in_array($urutan, $checkedMagang) ? 'checked' : '' ?>
                                            disabled>
                                    </td>
                                </tr>
                                <?php $urutan++; endforeach; ?>
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
