<?= $this->extend('layouts/mitra') ?>

<?= $this->section('title') ?>
Kelola Jam Kerja Unit
<?= $this->endSection() ?>

<!-- CSS -->
<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

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
.table-responsive { overflow: visible !important; }
.dropdown-menu {
    z-index: 2000 !important;
    position: absolute !important;
    border-radius: 0.6rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.dropdown-toggle::after { margin-left: 0.4rem; }
</style>

<!-- Page Heading & Breadcrumb -->
<div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Kelola Jam Kerja</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('mitra/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kelola Jam Kerja</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold">Data Jam Kerja</h5>
        <?php if (empty($sudahLengkap) || !$sudahLengkap): ?>
            <button class="btn btn-primary btn-sm fw-bold d-flex align-items-center justify-content-center gap-2 rounded btn-tambah" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle"></i> Tambah Jam Kerja
            </button>
        <?php endif; ?>
    </div>

    <div class="card-body">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-light-success color-success d-flex align-items-center justify-content-between">
                <div><i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success'); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif(session()->getFlashdata('error')): ?>
            <div class="alert alert-light-danger color-danger d-flex align-items-center justify-content-between">
                <div><i class="bi bi-exclamation-circle"></i> <?= session()->getFlashdata('error'); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive mt-3">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Hari</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jamKerja)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted fw-bold">
                                Belum ada data jam kerja.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($jamKerja as $jk): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($jk['hari']) ?></td>
                                <td><?= esc($jk['jam_masuk'] ?? '-') ?></td>
                                <td><?= esc($jk['jam_pulang'] ?? '-') ?></td>
                                <td>
                                    <span class="badge bg-<?= $jk['status_hari'] == 'Kerja' ? 'success' : 'secondary' ?>">
                                        <?= esc($jk['status_hari']) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm btn-edit"
                                            data-id="<?= $jk['id_jam_kerja'] ?>"
                                            data-hari="<?= $jk['hari'] ?>"
                                            data-masuk="<?= $jk['jam_masuk'] ?>"
                                            data-pulang="<?= $jk['jam_pulang'] ?>"
                                            data-status="<?= $jk['status_hari'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Jam Kerja -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-3 shadow-lg border-0">
      <div class="modal-header bg-primary text-white rounded-top-3">
        <h5 class="modal-title text-white fw-bold" id="modalTambahLabel">Tambah Jam Kerja Unit</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form id="formTambahJamKerja" action="<?= base_url('mitra/store') ?>" method="post">
        <?= csrf_field() ?>
        <div class="modal-body p-4" style="max-height:70vh; overflow-y:auto;">
          <div class="mb-3">
            <label class="form-label fw-semibold">Pilih Hari</label>
            <div class="d-flex flex-wrap gap-3">
              <?php 
                $hari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                foreach ($hari as $h): 
              ?>
                <div class="form-check">
                <input 
                        class="form-check-input" 
                        type="checkbox" 
                        name="hari[]" 
                        value="<?= $h ?>" 
                        id="hari-<?= $h ?>"
                        <?= in_array($h, $hariAda) ? 'checked disabled' : '' ?>
                    >
                  <label class="form-check-label" for="hari-<?= $h ?>"><?= $h ?></label>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label for="jam_masuk" class="form-label fw-semibold">Jam Masuk</label>
              <input type="time" class="form-control rounded-3" name="jam_masuk" id="jam_masuk">
            </div>
            <div class="col-md-6">
              <label for="jam_pulang" class="form-label fw-semibold">Jam Pulang</label>
              <input type="time" class="form-control rounded-3" name="jam_pulang" id="jam_pulang">
            </div>
          </div>

          <div class="mt-3">
            <label for="status" class="form-label fw-semibold">Status</label>
            <select name="status" id="status" class="form-select rounded-3" required>
              <option value="">Pilih Status</option>
              <option value="Kerja">Kerja</option>
              <option value="Libur">Libur</option>
            </select>
          </div>
        </div>
        <div class="modal-footer border-top-0 px-5">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary shadow-sm">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Jam Kerja -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-3 shadow-lg border-0">
      <div class="modal-header bg-primary text-white rounded-top-3">
        <h5 class="modal-title text-white fw-bold" id="modalEditLabel">Edit Jam Kerja Unit</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form id="formEditJamKerja" action="<?= base_url('mitra/update') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="edit-id">
        <div class="modal-body p-4" style="max-height:70vh; overflow-y:auto;">
          <div class="mb-3">
            <label for="edit-hari" class="form-label fw-semibold">Hari</label>
            <input type="text" class="form-control rounded-3" name="hari" id="edit-hari" readonly>
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="edit-masuk" class="form-label fw-semibold">Jam Masuk</label>
              <input type="time" class="form-control rounded-3" name="jam_masuk" id="edit-masuk">
            </div>
            <div class="col-md-6">
              <label for="edit-pulang" class="form-label fw-semibold">Jam Pulang</label>
              <input type="time" class="form-control rounded-3" name="jam_pulang" id="edit-pulang">
            </div>
          </div>
          <div class="mt-3">
            <label for="edit-status" class="form-label fw-semibold">Status</label>
            <select name="status" id="edit-status" class="form-select rounded-3" required>
                <option value="Kerja">Kerja</option>
                <option value="Libur">Libur</option>
            </select>
          </div>
        </div>
        <div class="modal-footer border-top-0 px-5">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary shadow-sm">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JS -->
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="assets/static/js/app.js"></script>
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($jamKerja)): ?>
<script src="assets/static/js/pages/datatables.js"></script>
<?php endif; ?>

<script>
$(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';
    if (!$.fn.DataTable.isDataTable('#table1')) {
        $('#table1').DataTable({
            responsive: true,
            autoWidth: false,
            drawCallback: function() {
                $('.dropdown-toggle').dropdown();
            }
        });
    }

    function toggleFields(status, isEdit = false) {
        const masuk = isEdit ? '#edit-masuk' : '#jam_masuk';
        const pulang = isEdit ? '#edit-pulang' : '#jam_pulang';

        if (status === 'Libur') {
            $(masuk).val('').prop('disabled', true).prop('required', false);
            $(pulang).val('').prop('disabled', true).prop('required', false);
        } else {
            $(masuk).prop('disabled', false).prop('required', true);
            $(pulang).prop('disabled', false).prop('required', true);
        }
    }

    $('#status').on('change', function() {
        toggleFields($(this).val());
    });

    $('#formTambahJamKerja').on('submit', function(e) {
        if ($('input[name="hari[]"]:checked').length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: 'Pilih minimal satu hari sebelum menyimpan.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#0d6efd'
            });
        }
    });

    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        const hari = $(this).data('hari');
        const masuk = $(this).data('masuk');
        const pulang = $(this).data('pulang');
        const status = $(this).data('status');

        $('#edit-id').val(id);
        $('#edit-hari').val(hari);
        $('#edit-masuk').val(masuk);
        $('#edit-pulang').val(pulang);
        $('#edit-status').val(status);

        toggleFields(status, true);
        $('#modalEdit').modal('show');
    });

    $('#edit-status').on('change', function() {
        toggleFields($(this).val(), true);
    });
});
</script>

<?= $this->endSection() ?>
