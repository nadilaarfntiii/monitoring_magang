<?= $this->extend('layouts/dospem') ?>

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
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Logbook Harian</h5>
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
                            <td style="text-align: justify;"><?= $lb['catatan_aktivitas'] ?></td>
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
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
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
