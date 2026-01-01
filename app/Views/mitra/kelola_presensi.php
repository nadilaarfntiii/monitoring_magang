<?= $this->extend('layouts/mitra') ?>

<?= $this->section('title') ?>
Kelola Presensi
<?= $this->endSection() ?>

<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<?= $this->section('content') ?>

<!-- Page Heading & Breadcrumb -->
<div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Kelola Presensi</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('mitra/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kelola Presensi</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Validasi Presensi</h5>
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
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Hari</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Pulang</th>
                        <th>Status Kehadiran</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($presensi)): ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted fw-bold">Belum ada data presensi yang menunggu validasi.</td>
                        </tr>
                    <?php else: ?>
                        <?php
                        $no = 1;
                        $hariIndo = [
                            'Sunday' => 'Minggu','Monday' => 'Senin','Tuesday' => 'Selasa',
                            'Wednesday' => 'Rabu','Thursday' => 'Kamis','Friday' => 'Jumat','Saturday' => 'Sabtu'
                        ];
                        ?>
                        <?php foreach ($presensi as $p): ?>
                            <?php $hari = $hariIndo[date('l', strtotime($p['tanggal']))] ?? '-'; ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($p['nama_lengkap']) ?></td>
                                <td><?= esc($hari) ?></td>
                                <td><?= date('d-m-Y', strtotime($p['tanggal'])) ?></td>
                                <td><?= esc($p['keterangan']) ?></td>
                                <td><?= !empty($p['waktu_masuk']) ? date('H:i:s', strtotime($p['waktu_masuk'])) : '-' ?></td>
                                <td><?= !empty($p['waktu_keluar']) ? date('H:i:s', strtotime($p['waktu_keluar'])) : '-' ?></td>
                                <td>
                                    <?php if ($p['status_kehadiran'] == 'Tepat Waktu'): ?>
                                        <span class="badge bg-success">Tepat Waktu</span>
                                    <?php elseif ($p['status_kehadiran'] == 'Telat'): ?>
                                        <span class="badge bg-warning text-dark">Telat</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Tidak Hadir</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($p['foto_bukti'])): ?>
                                        <img src="<?= base_url('uploads/presensi/' . $p['foto_bukti']) ?>"
                                             alt="Bukti" width="50" height="50" class="rounded border img-thumbnail pointer"
                                             data-bs-toggle="modal" data-bs-target="#fotoModal"
                                             data-src="<?= base_url('uploads/presensi/' . $p['foto_bukti']) ?>">
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('kelola_presensi/validasi/'.$p['id_presensi']) ?>"
                                       class="btn btn-success btn-sm mb-1"
                                       data-bs-toggle="tooltip"
                                       title="Setujui">
                                       <i class="bi bi-check-circle"></i>
                                    </a>
                                    <a href="#"
                                       class="btn btn-danger btn-sm mb-1 btnTolak"
                                       data-bs-toggle="tooltip"
                                       title="Tolak"
                                       data-id="<?= $p['id_presensi'] ?>">
                                       <i class="bi bi-x-circle"></i>
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

<!-- Modal Catatan Validasi -->
<div class="modal fade" id="modalCatatanValidasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formCatatanValidasi" method="post" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white fw-bold">Tolak Presensi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_presensi" id="idPresensi">
                <div class="mb-3">
                    <label class="form-label">Catatan Validasi</label>
                    <textarea name="catatan_validasi" id="catatan_validasi" class="form-control" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </div>
        </form>
    </div>
</div>


<!-- Modal Preview Gambar -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body p-0">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                <img src="" id="modalGambar" class="img-fluid rounded" alt="Preview Gambar">
            </div>
        </div>
    </div>
</div>

<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="assets/static/js/app.js"></script>
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($presensi)): ?>
    <script src="<?= base_url('assets/static/js/pages/datatables.js') ?>"></script>
<?php endif; ?>


<script>
$(document).ready(function () {
    <?php if (!empty($presensi)): ?>
        $('#table1').DataTable();
    <?php endif; ?>

    // Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Preview gambar
    $('#fotoModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var src = button.data('src');
        $('#modalGambar').attr('src', src);
    });

    // Tombol Tolak → tampilkan modal
    $('.btnTolak').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#idPresensi').val(id);
        $('#catatan_validasi').val('');
        var modal = new bootstrap.Modal(document.getElementById('modalCatatanValidasi'));
        modal.show();
    });

    // Submit modal Tolak
    $('#formCatatanValidasi').submit(function(e) {
        e.preventDefault();
        var catatan = $('#catatan_validasi').val().trim();
        if(catatan === '') {
            alert('Catatan harus diisi!');
            return false;
        }
        var form = $(this);
        $.ajax({
            url: "<?= base_url('kelola_presensi/tolak') ?>",
            type: "POST",
            data: form.serialize(),
            success: function(response) {
                // reload ke halaman kelola presensi
                window.location.href = "<?= base_url('mitra/kelola_presensi') ?>";
            },
            error: function() {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
