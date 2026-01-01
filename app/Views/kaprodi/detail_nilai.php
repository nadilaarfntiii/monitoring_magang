<?= $this->extend('layouts/kaprodi') ?>

<?= $this->section('title') ?>
Detail Nilai Magang
<?= $this->endSection() ?>

<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<?= $this->section('content') ?>

<!-- Page Heading & Breadcrumb -->
<div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Detail Nilai Magang</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('kaprodi/nilai_mahasiswa') ?>">Daftar Nilai Magang</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Detail Nilai Magang</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>


<div class="card">

    <div class="card-body">

        <!-- INFO MAHASISWA -->
        <div class="card border-primary shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title fw-bold text-primary mb-3">Informasi Mahasiswa</h5>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="text-secondary" style="width:40%;">NIM</th>
                                    <td style="width:5%;">:</td>
                                    <td class="fw-semibold"><?= esc($mahasiswa['nim']) ?></td>
                                </tr>
                                <tr>
                                    <th class="text-secondary">Nama Lengkap</th>
                                    <td>:</td>
                                    <td class="fw-semibold"><?= esc($mahasiswa['nama_lengkap']) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="text-secondary" style="width:40%;">Program Magang</th>
                                    <td style="width:5%;">:</td>
                                    <td class="fw-semibold"><?= esc($mahasiswa['nama_program']) ?></td>
                                </tr>
                                <tr>
                                    <th class="text-secondary">Tempat Magang</th>
                                    <td>:</td>
                                    <td class="fw-semibold"><?= esc($mahasiswa['nama_mitra']) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL NILAI -->
        <?php if (empty($komponen)): ?>
            <div class="alert alert-warning text-center fw-bold">
                Data nilai belum diinput.
            </div>
        <?php else: ?>
        <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:20%;">Mata Kuliah</th>
                    <th style="width:15%;">Role</th>
                    <th style="width:30%;">Komponen Nilai</th>
                    <th style="width:10%;">Bobot (%)</th>
                    <th style="width:20%;">Nilai</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $lastMKRole = '';
                $mkRoleCounts = [];

                // Hitung jumlah baris per MK+Role
                foreach ($komponen as $k) {
                    $key = $k['kode_mk'].'_'.$k['role'];
                    if (!isset($mkRoleCounts[$key])) $mkRoleCounts[$key] = 0;
                    $mkRoleCounts[$key]++;
                }

                foreach ($komponen as $index => $k):
                    $key = $k['kode_mk'].'_'.$k['role'];
                ?>
                <tr>
                    <?php if ($key !== $lastMKRole): ?>
                        <td rowspan="<?= $mkRoleCounts[$key] ?>"><?= $no++ ?></td>
                        <td rowspan="<?= $mkRoleCounts[$key] ?>"><?= esc($k['nama_mk']) ?></td>
                        <td rowspan="<?= $mkRoleCounts[$key] ?>"><?= ucfirst(esc($k['role'])) ?></td>
                    <?php endif; ?>
                    <td class="text-start"><?= esc($k['komponen']) ?></td>
                    <td><?= esc($k['presentase']) ?>%</td>
                    <td>
                            <?= isset($nilaiTersimpan[$k['id_nilai_magang']]) 
                            ? rtrim(rtrim($nilaiTersimpan[$k['id_nilai_magang']]['nilai'], '1'), '.') 
                            : '-' ?>
                    </td>
                </tr>

                <?php
                // Baris total per MK+Role
                $isLastRowForMKRole = (!isset($komponen[$index+1])) || 
                                      ($komponen[$index+1]['kode_mk'] !== $k['kode_mk']) || 
                                      ($komponen[$index+1]['role'] !== $k['role']);
                if ($isLastRowForMKRole):
                ?>
                <tr class="table-light fw-bold">
                    <td colspan="4" class="text-end">
                        Total Nilai <?= esc($k['nama_mk']) ?> (<?= esc($k['role']) ?>) :
                    </td>
                    <td class="text-center">
                        <?php
                        // Hitung total bobot untuk MK + role
                        $totalBobot = 0;
                        foreach ($komponen as $k2) {
                            if ($k2['kode_mk'] === $k['kode_mk'] && $k2['role'] === $k['role']) {
                                $totalBobot += $k2['presentase'];
                            }
                        }
                        echo $totalBobot . '%';
                        ?>
                    </td>
                    <td class="text-center">
                        <?= isset($totalPerMKRole[$key]) ? number_format($totalPerMKRole[$key],0) : '0' ?>
                    </td>
                </tr>

                <!-- ============== TAMBAHAN: TOTAL NILAI PER MATA KULIAH =============== -->
                <?php
                // Cetak total per Mata Kuliah hanya sekali (setelah role terakhir)
                $isLastRowForMK = (!isset($komponen[$index+1])) || 
                                ($komponen[$index+1]['kode_mk'] !== $k['kode_mk']);

                if ($isLastRowForMK):
                ?>
                <tr class="table-warning fw-bold">
                    <td colspan="4" class="text-end">
                        Nilai Akhir Mata Kuliah <?= esc($k['nama_mk']) ?> :
                    </td>
                    <td colspan="2" class="text-center">
                        <?= isset($totalPerMK[$k['kode_mk']]) 
                            ? rtrim(rtrim(number_format($totalPerMK[$k['kode_mk']], 2, '.', ''), '0'), '.') 
                            : '0' ?>
                    </td>
                </tr>

                <tr class="table-success fw-bold">
                    <td colspan="4" class="text-end">
                        Grade Mata Kuliah <?= esc($k['nama_mk']) ?> :
                    </td>
                    <td colspan="2" class="text-center">
                        <?= isset($gradePerMK[$k['kode_mk']]) ? $gradePerMK[$k['kode_mk']] : '-' ?>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endif; ?>
                <?php $lastMKRole = $key; endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
        <!-- BUTTON BACK DI BAWAH TABEL -->
        <div class="mt-3">
            <a href="<?= base_url('kaprodi/nilai_mahasiswa') ?>" 
            class="btn btn-primary btn-sm">
                Kembali
            </a>
        </div>
    </div>
</div>


<?= $this->endSection() ?>
