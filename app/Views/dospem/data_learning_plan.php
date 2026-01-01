<?= $this->extend('layouts/dospem') ?>

<?= $this->section('title') ?>
Learning Plan
<?= $this->endSection() ?>

<!-- === CSS Import === -->
<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<?= $this->section('content') ?>

<!-- CUSTOM LABEL STYLE -->
<style>
.label-with-icon {
  display: flex;
  align-items: center;
  gap: 6px;
  font-weight: 700;
  font-size: 0.9rem;
  color: #212529;
  margin-bottom: 4px;
}

.label-with-icon i {
  font-size: 1rem;
  color: #212529;
  line-height: 1;
  display: flex;
  align-items: center;
}
</style>

<!-- Page Heading & Breadcrumb -->
<div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Data Learning Plan</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('dospem/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Data Learning Plan</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0 fw-bold">Learning Plan</h5>
  </div>

  <!-- 🔔 ALERT FLASHDATA -->
  <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-light-success color-success d-flex align-items-center justify-content-between">
          <div><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success'); ?></div>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
  <?php elseif (session()->getFlashdata('error')): ?>
      <div class="alert alert-light-danger color-danger d-flex align-items-center justify-content-between">
          <div><i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error'); ?></div>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
  <?php endif; ?>
  <!-- END ALERT -->

  <div class="card-body">
    <div class="table-responsive">
      <table class="table align-middle text-center" id="table1">
        <thead>
          <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Validasi Pembimbing</th>
            <th>Validasi Kaprodi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($learningPlans)): ?>
            <?php $no = 1; foreach ($learningPlans as $lp): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($lp['nim']) ?></td>
                <td><?= esc($lp['nama_lengkap']) ?></td>
                <td><?= esc($lp['program_studi']) ?></td>
                <td>
                  <?php if ($lp['status_approval_pembimbing'] == 'Disetujui'): ?>
                    <span class="badge bg-success">Disetujui</span>
                  <?php elseif ($lp['status_approval_pembimbing'] == 'Menunggu'): ?>
                    <span class="badge bg-warning text-dark">Menunggu</span>
                    <?php elseif ($lp['status_approval_pembimbing'] == 'Ditolak'): ?>
                    <!-- Klik untuk lihat catatan -->
                    <a href="#" class="badge bg-danger text-white" data-bs-toggle="modal" data-bs-target="#modalCatatan<?= $lp['id_lp'] ?>">
                    Ditolak
                    </a>

                    <!-- Modal Catatan Pembimbing -->
                    <div class="modal fade" id="modalCatatan<?= $lp['id_lp'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Catatan Pembimbing</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                            <textarea class="form-control" rows="3" readonly><?= esc($lp['catatan_pembimbing'] ?? '-') ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                        </div>
                    </div>
                    </div>

                    <?php else: ?>
                        <span class="badge bg-secondary">Draft</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($lp['status_approval_kaprodi'] == 'Disetujui'): ?>
                      <span class="badge bg-success">Disetujui</span>

                    <?php elseif ($lp['status_approval_kaprodi'] == 'Menunggu'): ?>
                      <span class="badge bg-warning text-dark">Menunggu</span>

                    <?php elseif ($lp['status_approval_kaprodi'] == 'Ditolak'): ?>
                      <!-- Klik untuk lihat catatan -->
                      <a href="#" class="badge bg-danger text-white" data-bs-toggle="modal" data-bs-target="#modalCatatanKaprodi<?= $lp['id_lp'] ?>">
                        Ditolak
                      </a>

                      <!-- Modal Catatan Kaprodi -->
                      <div class="modal fade" id="modalCatatanKaprodi<?= $lp['id_lp'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Catatan Kaprodi</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <div class="mb-3">
                                <textarea class="form-control" rows="3" readonly><?= esc($lp['catatan_kaprodi'] ?? '-') ?></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                          </div>
                        </div>
                      </div>

                    <?php else: ?>
                      <span class="badge bg-secondary">Draft</span>
                    <?php endif; ?>
                  </td>
                <td>
                  <a href="<?= base_url('dospem/kelola_learning_plan/detail/' . $lp['id_lp']) ?>" class="btn btn-sm btn-info">
                    <i class="bi bi-eye"></i> Detail
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="text-muted text-center">Belum ada Data Learning Plan</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- === JS IMPORT (Persis Seperti Admin) === -->
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script> 
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($learningPlans)): ?>
  <!-- ✅ Hanya aktif jika tabel ada datanya -->
  <script src="assets/static/js/pages/datatables.js"></script>
<?php endif; ?>

<?= $this->endSection() ?>
