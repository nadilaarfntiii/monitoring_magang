<?= $this->extend('layouts/mhs') ?>

<?= $this->section('title') ?>
Logbook Harian
<?= $this->endSection() ?>

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
    .form-label i {
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
    }

    .btn-cetak i {
        line-height: 1 !important;
        display: flex !important;
        align-items: center !important;
    }
    .btn-cetak {
        display: inline-flex !important;
        align-items: center !important;
    }
</style>

            <!-- Page Heading & Breadcrumb -->
            <div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Logbook</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('mahasiswa/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Logbook</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Logbook Harian</h5>

        <div class="d-flex gap-2">
            <a href="<?= base_url('logbook/cetak') ?>" 
                target="_blank"
                class="btn btn-success btn-sm fw-bold d-flex align-items-center gap-2 rounded btn-cetak">
                <i class="bi bi-printer"></i> Cetak
            </a>

            <a href="javascript:void(0)" 
                class="btn btn-primary btn-sm fw-bold d-flex align-items-center gap-2 rounded btn-tambah"
                data-bs-toggle="modal" 
                data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle"></i> Tambah Logbook
            </a>
        </div>
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

        <div class="table-responsive">
            <table class="table align-middle text-center" id="table1">
                <thead>
                    <tr>
                        <th style="width:5%;">No</th>
                        <th style="width:12%;">Tanggal</th>
                        <th style="width:10%;">Jam Masuk</th>
                        <th style="width:10%;">Jam Pulang</th>
                        <th style="width:35%;">Aktivitas</th>
                        <th style="width:15%;">Foto Kegiatan</th>
                        <th style="width:13%;">Approval</th>
                        <th style="width:10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($logbooks)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted fw-bold">Belum ada logbook yang dicatat.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no=1; foreach($logbooks as $lb): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $lb['tanggal'] ?></td>
                            <td><?= $lb['jam_masuk'] ?></td>
                            <td><?= $lb['jam_pulang'] ?></td>
                            <td style="text-align: justify;"><?= nl2br(esc($lb['catatan_aktivitas'])) ?></td>
                            <td>
                                <?php if (!empty($lb['foto_kegiatan'])): ?>
                                    <img src="<?= base_url('uploads/logbook/' . $lb['foto_kegiatan']) ?>" 
                                        alt="Bukti" width="50" height="50" class="rounded border img-thumbnail pointer"
                                        data-bs-toggle="modal" data-bs-target="#fotoModal" 
                                        data-src="<?= base_url('uploads/logbook/' . $lb['foto_kegiatan']) ?>">
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                              <?php if($lb['approval_pembimbing'] === 'Disetujui'): ?>
                                  <span class="badge bg-success">Disetujui</span>
                              <?php elseif($lb['approval_pembimbing'] === 'Ditolak'): ?>
                                  <span class="badge bg-danger pointer" data-bs-toggle="tooltip" 
                                        title="Klik untuk melihat catatan pembimbing"
                                        data-catatan="<?= esc($lb['catatan_pembimbing']) ?>"
                                        onclick="showCatatan(this)">
                                        Ditolak
                                  </span>
                              <?php else: ?>
                                  <span class="badge bg-warning text-dark">Pending</span>
                              <?php endif; ?>
                          </td>
                          <td>
                              <?php if ($lb['approval_pembimbing'] === 'Pending' || $lb['approval_pembimbing'] === 'Ditolak'): ?>
                                  <button 
                                      class="btn btn-warning btn-sm btn-edit fw-bold"
                                      data-id="<?= $lb['id_logbook'] ?>"
                                      data-tanggal="<?= $lb['tanggal'] ?>"
                                      data-jam_masuk="<?= $lb['jam_masuk'] ?>"
                                      data-jam_pulang="<?= $lb['jam_pulang'] ?>"
                                      data-aktivitas="<?= esc($lb['catatan_aktivitas']) ?>"
                                      data-foto="<?= $lb['foto_kegiatan'] ?>"
                                      data-bs-toggle="modal" 
                                      data-bs-target="#modalTambah">
                                      <i class="bi bi-pencil-square"></i>
                                  </button>
                              <?php else: ?>
                                  <span class="text-muted">-</span> 
                              <?php endif; ?>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah / Edit Logbook -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg border-0 rounded-3">
      <form id="formLogbook" action="<?= base_url('logbook/store') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Header Modal -->
        <div class="modal-header bg-primary text-white fw-bold rounded-top-3">
          <h5 class="modal-title text-white fw-bold">
            Tambah Logbook
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- Body Modal -->
        <div class="modal-body p-4" style="max-height:70vh; overflow-y:auto;">
          <div class="row g-4">

            <input type="hidden" name="id_logbook" id="id_logbook">

            <!-- Tanggal -->
            <div class="col-md-6">
              <label for="tanggal" class="form-label d-flex align-items-center gap-2">
                <i class="bi bi-calendar-date"></i> Tanggal <span class="text-danger">*</span>
              </label>
              <input type="date" name="tanggal" id="tanggal" class="form-control" required>
            </div>

            <!-- Jam Masuk -->
            <div class="col-md-6">
              <label for="jam_masuk" class="form-label d-flex align-items-center gap-2">
                <i class="bi bi-clock"></i> Jam Masuk <span class="text-danger">*</span>
              </label>
              <input type="time" name="jam_masuk" id="jam_masuk" class="form-control" required>
            </div>

            <!-- Jam Pulang -->
            <div class="col-md-6">
              <label for="jam_pulang" class="form-label d-flex align-items-center gap-2">
                <i class="bi bi-clock-history"></i> Jam Pulang <span class="text-danger">*</span>
              </label>
              <input type="time" name="jam_pulang" id="jam_pulang" class="form-control" required>
            </div>

            <!-- Foto Kegiatan -->
            <div class="col-md-6">
              <label for="foto_kegiatan" class="form-label d-flex align-items-center gap-2">
                <i class="bi bi-image"></i> Foto Kegiatan <span class="text-danger">*</span>
              </label>
              <input type="file" name="foto_kegiatan" id="foto_kegiatan" class="form-control">
                <div class="mt-2" id="previewContainer" style="display:none;">
                    <img id="previewImg" src="" class="img-thumbnail" style="max-width:200px;">
                </div>
            </div>

            <!-- Aktivitas -->
            <div class="col-12">
              <label for="catatan_aktivitas" class="form-label d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square"></i> Aktivitas <span class="text-danger">*</span>
              </label>
              <textarea name="catatan_aktivitas" id="catatan_aktivitas" class="form-control" rows="3" required></textarea>
            </div>

          </div>
        </div>

        <!-- Footer Modal -->
        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary fw-bold">Simpan Logbook</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal untuk menampilkan foto dari tabel -->
<div class="modal fade" id="fotoModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-3">
      <div class="modal-body p-0">
        <img src="" id="fotoModalImg" class="img-fluid rounded" alt="Foto Kegiatan">
      </div>
    </div>
  </div>
</div>

<!-- Modal Catatan Pembimbing -->
<div class="modal fade" id="modalCatatanPembimbing" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title text-white fw-bold">Catatan Pembimbing</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <textarea id="catatanPembimbingText" class="form-control" rows="6" readonly></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>




<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script>
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (!empty($logbooks)): ?>
<script src="assets/static/js/pages/datatables.js"></script>
<script>
    $(document).ready(function() {
        $('#table1').DataTable();
    });
</script>
<?php endif; ?>

<script>
    $(document).ready(function() {
        // === Modal foto dari tabel ===
        $('.pointer').click(function() {
            var src = $(this).data('src');
            $('#fotoModalImg').attr('src', src);
        });

        // === Preview foto saat pilih file ===
        $('#foto_kegiatan').change(function() {
            var file = this.files[0];
            if(file){
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#previewImg').attr('src', e.target.result);
                    $('#previewContainer').show();
                }
                reader.readAsDataURL(file);
            } else {
                $('#previewContainer').hide();
                $('#previewImg').attr('src', '');
            }
        });
    });
</script>

<script>
  // =========================
// BUTTON EDIT LOGBOOK
// =========================
$(document).on('click', '.btn-edit', function() {

// Ganti title modal
$('.modal-title').text("Edit Logbook");

// Ganti URL form ke /logbook/update
$('#formLogbook').attr('action', "<?= base_url('logbook/update') ?>");

// Set value
$('#id_logbook').val($(this).data('id'));
$('#tanggal').val($(this).data('tanggal'));
$('#jam_masuk').val($(this).data('jam_masuk'));
$('#jam_pulang').val($(this).data('jam_pulang'));
$('#catatan_aktivitas').val($(this).data('aktivitas'));

// Preview foto jika ada
let foto = $(this).data('foto');
if (foto) {
    $('#previewImg').attr('src', "<?= base_url('uploads/logbook/') ?>" + "/" + foto);
    $('#previewContainer').show();
} else {
    $('#previewContainer').hide();
}
});

// Reset modal saat tambah ditekan
$('.btn-tambah').click(function () {
$('.modal-title').text("Tambah Logbook");
$('#formLogbook').attr('action', "<?= base_url('logbook/store') ?>");
$('#formLogbook')[0].reset();
$('#previewContainer').hide();
$('#id_logbook').val('');
});

</script>

<script>
  // Enable all tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

// Function untuk menampilkan catatan
function showCatatan(el) {
    var catatan = el.getAttribute('data-catatan');
    $('#catatanPembimbingText').val(catatan);
    var modal = new bootstrap.Modal(document.getElementById('modalCatatanPembimbing'));
    modal.show();
}

</script>
<?= $this->endSection() ?>
