<?= $this->extend('layouts/dospem') ?>

<?= $this->section('title') ?>
Data Bimbingan Magang
<?= $this->endSection() ?>

<!-- === CSS IMPORT === -->
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

/* ✅ Pastikan tombol pakai flex dan ikon sejajar vertikal */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 4px; /* jarak antara ikon dan teks */
  vertical-align: middle;
}

/* ✅ Samakan tinggi ikon dengan teks */
.btn i {
  font-size: 1rem;
  line-height: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: -1px; /* geser sedikit agar pas */
}

</style>

<!-- Page Heading & Breadcrumb -->
<div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Bimbingan Magang</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('dospem/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Bimbingan Magang</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0 fw-bold">Data Bimbingan Magang</h5>
    <div class="text-muted small">
      <strong>Semester:</strong> <?= esc($semester ?? '-') ?> |
      <strong>Tahun Ajaran:</strong> <?= esc($tahun_ajaran ?? '-') ?>
    </div>
  </div>

  <!-- 🔔 ALERT FLASHDATA -->
  <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-light-success color-success d-flex align-items-center justify-content-between mx-3 mt-3">
          <div><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success'); ?></div>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
  <?php elseif (session()->getFlashdata('error')): ?>
      <div class="alert alert-light-danger color-danger d-flex align-items-center justify-content-between mx-3 mt-3">
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
            <th style="width:5%;">No</th>
            <th style="width:10%;">NIM</th>
            <th style="width:20%;">Nama Mahasiswa</th>
            <th style="width:40%;">Judul Tugas Akhir Magang</th>
            <th style="width:15%;">Frekuensi Bimbingan</th>
            <th style="width:10%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($mahasiswa)): ?>
            <?php $no = 1; foreach ($mahasiswa as $mhs): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($mhs['nim']) ?></td>
                <td><?= esc($mhs['nama_lengkap']) ?></td>
                <td style="text-align: justify;">
                  <?php if (!empty($mhs['judul_ta'])): ?>
                    <?= esc($mhs['judul_ta']) ?>
                  <?php else: ?>
                    <em>Belum ada judul TA Magang</em>
                  <?php endif; ?>
                </td>
                <td><?= esc($mhs['frekuensi_bimbingan']) ?> kali</td>
                <td>
                <a href="javascript:void(0)"
                    class="btn btn-sm btn-primary btnTambahBimbingan"
                    data-id="<?= esc($mhs['id_profil']) ?>"
                    data-nim="<?= esc($mhs['nim']) ?>"
                    data-nama="<?= esc($mhs['nama_lengkap']) ?>"
                    data-judul="<?= esc($mhs['judul_ta'] ?? 'Belum ada judul TA Magang') ?>"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Tambah catatan bimbingan">
                    <i class="bi bi-plus-circle"></i>
                </a>

                <a href="<?= base_url('dospem/bimbingan/detail/' . $mhs['id_profil'] . '/BB010') ?>"
                  class="btn btn-sm btn-info"
                  data-bs-toggle="tooltip"
                  data-bs-placement="top"
                  title="Lihat riwayat bimbingan">
                  <i class="bi bi-eye"></i>
                </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-muted text-center">Tidak ada data mahasiswa bimbingan magang.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- === MODAL TAMBAH BIMBINGAN === -->
<div class="modal fade" id="modalTambahBimbingan" tabindex="-1" aria-labelledby="modalTambahBimbinganLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="<?= base_url('dospem/bimbingan/simpan') ?>" method="post">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title text-white fw-bold" id="modalTambahBimbinganLabel">
           Tambah Data Bimbingan
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- Data Mahasiswa -->
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="label-with-icon"><i class="bi bi-person-badge"></i> NIM</label>
              <input type="text" id="nim_mhs" class="form-control" readonly>
            </div>
            <div class="col-md-8">
              <label class="label-with-icon"><i class="bi bi-person"></i> Nama Mahasiswa</label>
              <input type="text" id="nama_mhs" class="form-control" readonly>
            </div>
          </div>

          <div class="mb-3">
            <label class="label-with-icon"><i class="bi bi-journal-text"></i> Judul Tugas Akhir</label>
            <input type="text" id="judul_ta_mhs" class="form-control" readonly>
          </div>

          <!-- Form Bimbingan -->
          <input type="hidden" name="id_profil" id="id_profil_mhs">

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="label-with-icon"><i class="bi bi-calendar-event"></i> Tanggal Bimbingan</label>
              <input type="date" name="tanggal_bimbingan" class="form-control" 
                    required max="<?= (new DateTime('now', new DateTimeZone('Asia/Jakarta')))->format('Y-m-d') ?>">
            </div>

            <div class="col-md-6">
              <label class="label-with-icon"><i class="bi bi-bar-chart"></i> Progress Tugas Akhir</label>
              <select name="progress_ta" class="form-select" required>
                <option value="">Pilih Bab...</option>
                <option value="Bab 1">Bab 1</option>
                <option value="Bab 2">Bab 2</option>
                <option value="Bab 3">Bab 3</option>
                <option value="Bab 4">Bab 4</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="label-with-icon"><i class="bi bi-check2-square"></i> Catatan Singkat</label>
            <select name="status_bimbingan" class="form-select" required>
              <option value="">-- Pilih Status --</option>
              <option value="ACC">ACC</option>
              <option value="Revisi">Revisi</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="label-with-icon"><i class="bi bi-pencil-square"></i> Catatan Detail</label>
            <textarea name="catatan_detail" rows="4" class="form-control" placeholder="Tuliskan catatan hasil bimbingan..." required></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- === JS IMPORT (Sama seperti Learning Plan) === -->
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script> 
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (!empty($mahasiswa)): ?>
<script src="assets/static/js/pages/datatables.js"></script>

<script>
  $(document).ready(function() {
      $('#table1').DataTable();
  });
</script>
<?php endif; ?>

<script>
    // Aktifkan semua tooltip di halaman
$(function () {
  $('[data-bs-toggle="tooltip"]').tooltip();
});

</script>

<script>
$(document).on('click', '.btnTambahBimbingan', function() {
    const id = $(this).data('id');
    const nim = $(this).data('nim');
    const nama = $(this).data('nama');
    const judul = $(this).data('judul');

    $('#id_profil_mhs').val(id);
    $('#nim_mhs').val(nim);
    $('#nama_mhs').val(nama);
    $('#judul_ta_mhs').val(judul);

    $('#modalTambahBimbingan').modal('show');
});
</script>


<?= $this->endSection() ?>
