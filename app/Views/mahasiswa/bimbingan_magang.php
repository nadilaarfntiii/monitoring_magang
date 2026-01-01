<?= $this->extend('layouts/mhs') ?>

<?= $this->section('title') ?>
Hasil Bimbingan Magang
<?= $this->endSection() ?>

<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
  .edit-link {
      font-weight: 500;
  }

  .edit-link:hover {
      text-decoration: underline;
      opacity: 0.8;
  }
</style>

<?= $this->section('content') ?>

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
                                    <li class="breadcrumb-item"><a href="<?= base_url('mahasiswa/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Bimbingan Magang</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0 fw-bold">Hasil Bimbingan Magang</h5>
  </div>

  <div class="card-body">
    <?php if (!empty($bimbingan)): ?>
      <!-- === CARD INFO BIMBINGAN === -->
      <div class="card border-primary shadow-sm mb-4">
        <div class="card-body">

        <h5 class="card-title fw-bold text-primary mb-3 d-inline-flex align-items-center flex-wrap">
            <?php if (!empty($tugasAkhir) && !empty($tugasAkhir['judul_ta'])): ?>

                <!-- Judul sudah ada -->
                <span><?= esc($tugasAkhir['judul_ta']) ?></span>

                <!-- Tombol edit menempel di akhir judul -->
                <a href="#" 
                  class="ms-2 text-decoration-none text-primary small edit-link"
                  data-bs-toggle="modal" data-bs-target="#modalJudul"
                  title="Edit Judul">
                    <i class="bi bi-pencil-square"></i>
                </a>

            <?php else: ?>

                <!-- Judul kosong -->
                <span class="text-danger">Judul Tugas Akhir Belum Ada</span>

                <!-- Tombol tambah menempel di akhir teks -->
                <a href="#" 
                  class="ms-2 text-decoration-none text-primary small edit-link"
                  data-bs-toggle="modal" data-bs-target="#modalJudul"
                  title="Tambah Judul">
                    <i class="bi bi-plus-circle"></i>
                </a>

            <?php endif; ?>
        </h5>



        <!-- === MODAL INPUT / EDIT JUDUL === -->
        <div class="modal fade" id="modalJudul" tabindex="-1" aria-labelledby="modalJudulLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content border-primary border-2">
                <form action="<?= site_url('bimbingan/simpanJudul') ?>" method="post">
                  <?= csrf_field() ?>

                  <!-- HEADER PRIMER -->
                  <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white fw-bold" id="modalJudulLabel">
                        Judul Tugas Akhir Magang
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <input type="hidden" name="id_profil" value="<?= esc($id_profil) ?>">

                    <div class="mb-3">
                      <label for="judul_ta" class="form-label fw-bold">Judul Tugas Akhir</label>
                      <textarea 
                          name="judul_ta" 
                          id="judul_ta" 
                          class="form-control" 
                          placeholder="Masukkan judul tugas akhir magang"
                          rows="3" 
                          required><?= esc($tugasAkhir['judul_ta'] ?? '') ?></textarea>
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
