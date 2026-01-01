<?= $this->extend('layouts/mhs') ?>

<?= $this->section('title') ?>
Tugas Akhir Magang
<?= $this->endSection() ?>

<!-- === CSS IMPORT (samakan seperti halaman bimbingan) === -->
<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
    .form-label i {
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
    }
</style>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold">Tugas Akhir Magang</h5>
        <?php if (!empty($mataKuliahTersedia)): ?>
            <a href="javascript:void(0)" 
                class="btn btn-primary btn-sm fw-bold d-flex align-items-center justify-content-center gap-2 rounded btn-tambah"
                data-bs-toggle="modal" 
                data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle"></i> Tambah Data
            </a>
        <?php endif; ?>
    </div>


  <div class="card-body">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php elseif (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="table-responsive">
      <table class="table align-middle text-center" id="table1">
        <thead>
          <tr>
            <th style="width:5%;">No</th>
            <th style="width:25%;">Mata Kuliah</th>
            <th style="width:55%;" class="text-start text-center">Judul Tugas Akhir</th>
            <th style="width:15%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($tugasAkhir)): ?>
            <?php $no = 1; foreach ($tugasAkhir as $t): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($mkMap[$t['kode_mk']] ?? $t['kode_mk']) ?></td>
                <td style="text-align: justify;"><?= esc($t['judul_ta']) ?></td>
                <td>
                <button type="button" 
                        class="btn btn-warning btn-sm btn-edit"
                        data-id="<?= $t['id_ta'] ?>"
                        data-judul="<?= esc($t['judul_ta']) ?>"
                        data-mk="<?= esc($mkMap[$t['kode_mk']] ?? $t['kode_mk']) ?>">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center text-muted">Belum ada data tugas akhir magang.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- === MODAL TAMBAH DATA === -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-primary border-2">
      <form action="<?= base_url('tugas_akhir_magang/store') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title text-white fw-bold" id="modalTambahLabel">
            Tambah Data Tugas Akhir Magang
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="judul_ta" class="form-label fw-bold">Judul Tugas Akhir</label>
            <textarea name="judul_ta" id="judul_ta" class="form-control" placeholder="Masukkan judul tugas akhir" rows="3" required></textarea>
          </div>

          <div class="mb-3">
            <label for="kode_mk" class="form-label fw-bold">Mata Kuliah</label>
            <select name="kode_mk" id="kode_mk" class="form-select" required>
                <option value="" selected disabled>Pilih Mata Kuliah</option>
                <?php if (!empty($mataKuliahTersedia)): ?>
                    <?php foreach($mataKuliahTersedia as $mk): ?>
                        <option value="<?= esc($mk['kode_mk']) ?>"><?= esc($mk['kode_mk'] . ' - ' . $mk['nama_mk']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="bi bi-save me-1"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- === MODAL EDIT DATA === -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-primary border-2">
      <form id="formEdit" method="POST">
        <?= csrf_field() ?>
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title text-white fw-bold" id="modalEditLabel">
            Edit Tugas Akhir Magang
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="edit_judul_ta" class="form-label fw-bold">Judul Tugas Akhir</label>
            <textarea name="judul_ta" id="edit_judul_ta" class="form-control" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Mata Kuliah</label>
            <input type="text" id="edit_kode_mk" class="form-control" readonly>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="bi bi-save me-1"></i> Update
          </button>
        </div>
      </form>
    </div>
  </div>
</div>




<!-- === JS IMPORT (samakan dengan halaman bimbingan) === -->
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script>
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (!empty($tugasAkhir)): ?>
<script src="assets/static/js/pages/datatables.js"></script>
<script>
    $(document).ready(function() {
        $('#table1').DataTable();
    });
</script>
<?php endif; ?>

<script>
$(document).ready(function() {

    // Tooltip bootstrap
    $('[data-bs-toggle="tooltip"]').tooltip();

    // SweetAlert untuk flashdata
    <?php if(session()->getFlashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '<?= session()->getFlashdata("success") ?>',
            timer: 2000,
            showConfirmButton: false
        });
    <?php elseif(session()->getFlashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '<?= session()->getFlashdata("error") ?>',
            timer: 2000,
            showConfirmButton: false
        });
    <?php endif; ?>

    // Reset form saat modal ditutup
    $('#modalTambah').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
});
</script>

<script>
$(document).ready(function() {
    // Tombol edit
    $('.btn-edit').click(function() {
        var id = $(this).data('id');
        var judul = $(this).data('judul');
        var mk = $(this).data('mk');

        $('#edit_judul_ta').val(judul);
        $('#edit_kode_mk').val(mk);
        $('#formEdit').attr('action', '<?= base_url("tugas_akhir_magang/update") ?>/' + id);

        $('#modalEdit').modal('show');
    });
});
</script>


<?= $this->endSection() ?>
