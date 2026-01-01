<?= $this->extend('layouts/dospem') ?>

<?= $this->section('title') ?>
Detail Bimbingan Mahasiswa
<?= $this->endSection() ?>

<!-- === CSS IMPORT (disamakan dengan Data Bimbingan Magang) === -->
<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<?= $this->section('content') ?>

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

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  vertical-align: middle;
}

.btn i {
  font-size: 1rem;
  line-height: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: -1px;
}
</style>

<!-- Page Heading & Breadcrumb -->
<div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Detail Bimbingan Magang</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('dospem/bimbingan_magang') ?>">Bimbingan Magang</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Detail Bimbingan Magang</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0 fw-bold">Detail Bimbingan</h5>
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


      <div class="table-responsive">
        <table class="table align-middle text-center" id="table1">
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>Progress</th>
              <th>Status</th>
              <th>Catatan Detail</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach ($bimbingan as $b): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d-m-Y', strtotime($b['tanggal_bimbingan'])) ?></td>
                <td><?= esc($b['progress_ta']) ?></td>
                <td>
                  <?php if ($b['status_bimbingan'] == 'Acc'): ?>
                    <span class="badge bg-success">ACC</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark">Revisi</span>
                  <?php endif; ?>
                </td>
                <td style="text-align: justify; white-space: pre-line;"><?= esc($b['catatan_detail']) ?></td>
                <td>
                  <button class="btn btn-sm btn-warning btnEditBimbingan"
                          data-id="<?= esc($b['id_bimbingan']) ?>"
                          data-tanggal="<?= esc($b['tanggal_bimbingan']) ?>"
                          data-progress="<?= esc($b['progress_ta']) ?>"
                          data-status="<?= esc($b['status_bimbingan']) ?>"
                          data-catatan="<?= esc($b['catatan_detail']) ?>"
                          data-bs-toggle="tooltip" title="Edit bimbingan">
                    <i class="bi bi-pencil-square"></i> Edit
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-light-warning">
        <i class="bi bi-info-circle me-2"></i> Belum ada data bimbingan untuk mahasiswa ini.
      </div>
    <?php endif; ?>
  </div>
</div>



<!-- === MODAL EDIT BIMBINGAN === -->
<div class="modal fade" id="modalEditBimbingan" tabindex="-1" aria-labelledby="modalEditBimbinganLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="<?= base_url('dospem/bimbingan/update') ?>" method="post">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title text-white fw-bold" id="modalEditBimbinganLabel">
            Edit Data Bimbingan
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id_bimbingan" id="edit_id_bimbingan">

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="label-with-icon text-dark"><i class="bi bi-calendar-event"></i> Tanggal Bimbingan</label>
              <input type="date" name="tanggal_bimbingan" id="edit_tanggal_bimbingan"
                      class="form-control" required max="<?= (new DateTime('now', new DateTimeZone('Asia/Jakarta')))->format('Y-m-d') ?>">
            </div>

            <div class="col-md-6">
              <label class="label-with-icon text-dark"><i class="bi bi-bar-chart"></i> Progress Tugas Akhir</label>
              <select name="progress_ta" id="edit_progress_ta" class="form-select" required>
                <option value="">Pilih Bab...</option>
                <option value="Bab 1">Bab 1</option>
                <option value="Bab 2">Bab 2</option>
                <option value="Bab 3">Bab 3</option>
                <option value="Bab 4">Bab 4</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="label-with-icon text-dark"><i class="bi bi-check2-square"></i> Status Bimbingan</label>
            <select name="status_bimbingan" id="edit_status_bimbingan" class="form-select" required>
              <option value="">-- Pilih Status --</option>
              <option value="Acc">ACC</option>
              <option value="Revisi">Revisi</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="label-with-icon text-dark"><i class="bi bi-pencil"></i> Catatan Detail</label>
            <textarea name="catatan_detail" id="edit_catatan_detail" rows="4" class="form-control" required></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
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

<script>
$(document).ready(function() {

  $('[data-bs-toggle="tooltip"]').tooltip();
});

// === Tombol Edit ===
$(document).on('click', '.btnEditBimbingan', function() {
  const id = $(this).data('id');
  const tanggal = $(this).data('tanggal');
  const progress = $(this).data('progress');
  const status = $(this).data('status');
  const catatan = $(this).data('catatan');

  $('#edit_id_bimbingan').val(id);
  $('#edit_tanggal_bimbingan').val(tanggal);
  $('#edit_progress_ta').val(progress);
  $('#edit_status_bimbingan').val(status);
  $('#edit_catatan_detail').val(catatan);

  $('#modalEditBimbingan').modal('show');
});
</script>

<?= $this->endSection() ?>
