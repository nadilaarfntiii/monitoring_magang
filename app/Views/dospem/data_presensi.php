<?= $this->extend('layouts/dospem') ?>

<?= $this->section('title') ?>
Data Presensi Mahasiswa Bimbingan
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
                            <h3>Data Presensi</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('dospem/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Data Presensi</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-1">
                Rekap Presensi Mahasiswa <?= esc($semester) ?> |
                Tahun Ajaran: <strong><?= esc($tahun_ajaran) ?></strong>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle text-center" id="table1" style="width:100%">
                    <thead class="table-light">
                        <tr class="align-middle">
                            <th style="width: 3%;">No</th>
                            <th style="width: 10%;">NIM</th>
                            <th style="width: 20%;">Nama Mahasiswa</th>
                            <th style="width: 6%;">Hadir</th>
                            <th style="width: 6%;">Sakit</th>
                            <th style="width: 6%;">Izin</th>
                            <th style="width: 6%;">Alpha</th>
                            <th style="width: 10%;">Total Kehadiran</th>
                            <th style="width: 10%;">Total Hari Kerja</th>
                            <th style="width: 10%;">Persentase</th>
                            <th style="width: 7%;">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rekap_presensi)) : ?>
                            <?php $no = 1; foreach ($rekap_presensi as $m) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($m['nim']) ?></td>
                                    <td class="text-start"><?= esc($m['nama_lengkap']) ?></td>
                                    <td><?= esc($m['hadir']) ?></td>
                                    <td><?= esc($m['sakit']) ?></td>
                                    <td><?= esc($m['ijin']) ?></td>
                                    <td><?= esc($m['alpa']) ?></td>
                                    <td><?= esc($m['total_kehadiran']) ?></td>
                                    <td><?= esc($m['total_hari_kerja']) ?></td>
                                    <td>
                                        <span class="badge 
                                            <?= ($m['persentase'] >= 80) ? 'bg-success' : (($m['persentase'] >= 60) ? 'bg-warning' : 'bg-danger') ?>">
                                            <?= $m['persentase'] ?>%
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('dospem/detailPresensi/'.$m['nim']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="11" class="text-center text-muted">Belum ada data presensi mahasiswa.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script>
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/static/js/pages/datatables.js"></script>

<?php if (!empty($logbooks)): ?>
<script>
    $(document).ready(function() {
        $('#table1').DataTable();
    });
</script>
<?php endif; ?>

<?= $this->endSection() ?>
