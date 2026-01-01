<?= $this->extend('layouts/mhs') ?>

<?= $this->section('title') ?>
Hasil Bimbingan Desain Sistem
<?= $this->endSection() ?>

<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0 fw-bold">Hasil Bimbingan Makalah Desain Sistem</h5>
  </div>

  <div class="card-body">
    <?php if (!empty($bimbingan)): ?>
      <!-- === CARD INFO BIMBINGAN === -->
      <div class="card border-primary shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title fw-bold text-primary mb-3">
            <?= esc($bimbingan[0]['judul_ta'] ?? 'Judul Tugas Akhir Belum Ada') ?>
          </h5>

          <div class="row">
            <!-- Kolom kiri -->
            <div class="col-md-6">
              <table class="table table-borderless align-middle mb-0">
                <tbody>
                  <tr>
                    <th class="text-secondary" style="width: 40%;">NIM / Nama</th>
                    <td style="width: 5%;">:</td>
                    <td class="fw-semibold">
                      <?= esc(($bimbingan[0]['nim'] ?? '-') . ' / ' . ($bimbingan[0]['nama_lengkap'] ?? '-')) ?>
                    </td>
                  </tr>
                  <tr>
                    <th class="text-secondary">Jumlah Bimbingan</th>
                    <td>:</td>
                    <td class="fw-semibold"><?= count($bimbingan) ?> kali</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Kolom kanan -->
            <div class="col-md-6">
              <table class="table table-borderless align-middle mb-0">
                <tbody>
                  <tr>
                    <th class="text-secondary" style="width: 40%;">Mata Kuliah</th>
                    <td style="width: 5%;">:</td>
                    <td class="fw-semibold"><?= esc($bimbingan[0]['nama_mk'] ?? 'Mata Kuliah Magang') ?></td>
                  </tr>
                  <tr>
                    <th class="text-secondary">SKS</th>
                    <td>:</td>
                    <td class="fw-semibold"><?= esc($bimbingan[0]['sks'] ?? '-') ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- === TABEL BIMBINGAN === -->
      <div class="table-responsive">
        <table class="table align-middle text-center" id="table1">
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>Progress</th>
              <th style="width: 40%;">Catatan Detail</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach ($bimbingan as $b): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d-m-Y', strtotime($b['tanggal_bimbingan'])) ?></td>
                <td><?= esc($b['progress_ta']) ?></td>
                <td style="text-align: justify; white-space: pre-line;"><?= esc($b['catatan_detail']) ?></td>
                <td>
                  <?php if (strtolower($b['status_bimbingan']) == 'acc'): ?>
                    <span class="badge bg-success">ACC</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark">Revisi</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-light-warning">
        <i class="bi bi-info-circle me-2"></i> Belum ada hasil bimbingan yang diinput oleh dosen pembimbing.
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- === JS IMPORT (disamakan juga) === -->
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script> 
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/static/js/pages/datatables.js"></script>

<?php if (!empty($bimbingan)): ?>
<script>
    $(document).ready(function() {
        $('#table1').DataTable();
    });
</script>
<?php endif; ?>

<?= $this->endSection() ?>
