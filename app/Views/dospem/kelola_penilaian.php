<?= $this->extend('layouts/dospem') ?>

<?= $this->section('title') ?>
Kelola Penilaian Mahasiswa Magang
<?= $this->endSection() ?>

<!-- CSS -->
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
                            <h3>Kelola Nilai Mahasiswa</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('dospem/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kelola Nilai Mahasiswa</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
    

    <div class="card-body">

        <!-- FLASHDATA -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-light-success color-success d-flex align-items-center justify-content-between">
                <div><i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success'); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="alert alert-light-danger color-danger d-flex align-items-center justify-content-between">
                <div><i class="bi bi-exclamation-circle"></i> <?= session()->getFlashdata('error'); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <!-- END FLASHDATA -->

        <div class="table-responsive">
            <table class="table align-middle text-center" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Tempat Magang</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($mahasiswa)): ?>
                        <tr>
                            <td colspan="7" class="text-muted fw-bold text-center">
                                Belum ada mahasiswa bimbingan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($mahasiswa as $m): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($m['nim']) ?></td>
                                <td class="text-start"><?= esc($m['nama_lengkap']) ?></td>
                                <td><?= esc($m['nama_mitra']) ?></td>
                                <td><?= esc($m['nama_unit']) ?></td>

                                <td>
                                    <?php
                                        $status = strtolower($m['status']);
                                        $badgeClass = match ($status) {
                                            'aktif' => 'success',
                                            'selesai' => 'primary',
                                            'tidak selesai' => 'warning',
                                            'tidak aktif' => 'secondary',
                                            default => 'light'
                                        };
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>

                                <td>
                                    <a href="<?= base_url('dospem/input_nilai_magang/' . $m['nim']) ?>" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square me-1"></i> Input Nilai
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>

    </div>
</div>


<!-- JS -->
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script> 
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<?php if (!empty($mahasiswa)): ?>
<script src="assets/static/js/pages/datatables.js"></script>

<script>
$(document).ready(function(){
    // Inisialisasi DataTable (tidak double init)
    if (!$.fn.DataTable.isDataTable('#table1')) {
        $('#table1').DataTable();
    }
});
</script>
<?php endif; ?>



<?= $this->endSection() ?>
