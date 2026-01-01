<?= $this->extend('layouts/mitra') ?>

<?= $this->section('title') ?>
Kelola Logbook Mahasiswa
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
                            <h3>Kelola Logbook</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('mitra/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kelola Logbook</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Validasi Logbook</h5>
    </div>

    <div class="card-body">
        <!-- ALERT FLASHDATA -->
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
                        <th style="width:4%;">No</th>
                        <th style="width:15%;">Nama Mahasiswa</th>
                        <th style="width:10%;">Tanggal</th>
                        <th style="width:8%;">Jam Masuk</th>
                        <th style="width:8%;">Jam Pulang</th>
                        <th style="width:25%;">Aktivitas</th>
                        <th style="width:10%;">Foto Kegiatan</th>
                        <th style="width:10%;">Status</th>
                        <th style="width:10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($logbooks)): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted fw-bold">Belum ada logbook yang menunggu validasi.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; ?>
                        <?php foreach($logbooks as $lb): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($lb['nama_lengkap']) ?></td>
                            <td><?= date('d-m-Y', strtotime($lb['tanggal'])) ?></td>
                            <td><?= $lb['jam_masuk'] ?></td>
                            <td><?= $lb['jam_pulang'] ?></td>
                            <td style="text-align: justify;"><?= esc($lb['catatan_aktivitas']) ?></td>
                            <td>
                                <?php if(!empty($lb['foto_kegiatan'])): ?>
                                    <img src="<?= base_url('/uploads/logbook/' . $lb['foto_kegiatan']) ?>"
                                        alt="Bukti" width="50" height="50" class="rounded border img-thumbnail img-logbook"
                                        data-src="<?= base_url('/uploads/logbook/' . $lb['foto_kegiatan']) ?>">
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
                                <?php if($lb['approval_pembimbing'] == 'Pending'): ?>
                                    <a href="<?= base_url('kelola_logbook/validasi/'.$lb['id_logbook']) ?>"
                                       class="btn btn-success btn-sm mb-1" title="Disetujui">
                                       <i class="bi bi-check-circle"></i>
                                    </a>
                                    <a href="#" 
                                       class="btn btn-danger btn-sm mb-1 btnTolak" 
                                       data-id="<?= $lb['id_logbook'] ?>" title="Tolak">
                                       <i class="bi bi-x-circle"></i>
                                    </a>
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

<!-- Modal Catatan Tolak -->
<div class="modal fade" id="modalCatatanTolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formCatatanTolak" method="post" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white fw-bold">
                    Tolak Logbook
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_logbook" id="idLogbook">
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" id="catatan_tolak" class="form-control" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </div>
        </form>
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
$(document).ready(function () {

        // === Modal foto dari tabel ===
        $('.img-logbook').click(function(e) {
            e.preventDefault();
            
            // Ambil URL gambar
            var src = $(this).attr('data-src');
            
            // Set src di modal
            $('#fotoModalImg').attr('src', src);
            
            // Tampilkan modal
            var myModal = new bootstrap.Modal(document.getElementById('fotoModal'));
            myModal.show();
        });



    // Tombol Tolak → tampilkan modal
    $('.btnTolak').click(function(e){
        e.preventDefault();
        var id = $(this).data('id');
        $('#idLogbook').val(id);
        $('#catatan_tolak').val('');
        var modal = new bootstrap.Modal(document.getElementById('modalCatatanTolak'));
        modal.show();
    });

    // Submit modal Tolak
    $('#formCatatanTolak').submit(function(e){
        e.preventDefault();
        var catatan = $('#catatan_tolak').val().trim();
        if(catatan === ''){
            alert('Catatan harus diisi!');
            return false;
        }
        $.ajax({
            url: "<?= base_url('kelola_logbook/tolak') ?>",
            type: "POST",
            data: $(this).serialize(),
            success: function(){
                location.reload();
            },
            error: function(){
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
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
