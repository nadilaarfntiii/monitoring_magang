<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>
Kelola Profil Magang
<?= $this->endSection() ?>

<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<?= $this->section('content') ?>

 <!-- Page Heading & Breadcrumb -->
 <div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Kelola Profil Magang</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kelola Profil Magang</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Profil Magang</h5>
      <div class="d-flex gap-2">
          <!-- Tombol Import Excel -->
          <a href="javascript:void(0)" 
            class="btn btn-success btn-sm fw-bold d-flex align-items-center justify-content-center gap-2 rounded"
            data-bs-toggle="modal" 
            data-bs-target="#modalImport">
            <i class="bi bi-file-earmark-excel" style="font-size:16px; line-height:1; vertical-align:middle; margin-top:-1px;"></i>
             Import Excel
          </a>

          <a href="javascript:void(0)" 
              class="btn btn-primary btn-sm fw-bold d-flex align-items-center justify-content-center gap-2 rounded btn-tambah"
              data-bs-toggle="modal" 
              data-bs-target="#modalTambah">
              <i class="bi bi-person-plus"></i> Tambah Data
          </a>
      </div>
  </div>

  <div class="card-body">

    <!-- 🔔 ALERT FLASHDATA -->
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
    <!-- END ALERT -->

    <div class="table-responsive">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Dosen Pembimbing</th>
                        <th>Mitra</th>
                        <th>Program</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
              </thead>
              <tbody>
                <?php if (empty($profil)): ?>
                  <tr>
              <td colspan="8" class="text-center text-muted fw-bold">Belum ada data profil magang.</td>
            </tr>
          <?php else: ?>
            <?php $no=1; foreach ($profil as $row): ?>
              <tr>
                <td><?= $no++ ?></td>
                  <td><?= esc($row['nim'] ?? '-') ?></td>
                  <td><?= esc(mb_strimwidth($row['nama_lengkap'] ?? '-', 0, 25, '...')) ?></td>
                  <td><?= esc(mb_strimwidth($row['nama_dosen'] ?? '-', 0, 25, '...')) ?></td>
                  <td><?= esc(mb_strimwidth($row['nama_mitra'] ?? '-', 0, 25, '...')) ?></td>
                  <td><?= esc(mb_strimwidth($row['nama_program'] ?? '-', 0, 25, '...')) ?></td>
                <td>
                    <?php if ($row['status'] == 'aktif'): ?>
                      <span class="badge bg-success">Aktif</span>
                    <?php elseif ($row['status'] == 'tidak_selesai'): ?>
                      <span class="badge bg-secondary">Tidak Selesai</span>
                    <?php elseif ($row['status'] == 'selesai'): ?>
                      <span class="badge bg-primary">Selesai</span>
                    <?php elseif ($row['status'] == 'tidak_aktif'): ?>
                      <span class="badge bg-danger">Tidak Aktif</span>
                    <?php endif; ?>
                </td>
                <td>
                  <button class="btn btn-info btn-sm btn-detail" data-id="<?= $row['id_profil'] ?>">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button class="btn btn-warning btn-sm btn-edit" data-id="<?= $row['id_profil'] ?>">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button class="btn btn-danger btn-sm btn-delete" 
                        data-id="<?= $row['id_profil'] ?>" 
                        data-nama="<?= esc($row['nama_lengkap']) ?>">
                    <i class="bi bi-trash"></i>
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

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg border-0 rounded-3">
      
      <!-- HEADER -->
      <div class="modal-header bg-primary text-white fw-bold rounded-top-3">
        <h5 class="modal-title d-flex align-items-center gap-2 text-white fw-bold">
           Detail Profil Magang
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <!-- BODY -->
      <div class="modal-body p-4">

        <!-- INFORMASI MAHASISWA -->
        <div class="mb-4 pb-3 border-bottom">
          <h6 class="fw-bold text-primary mb-3">
            <i class="bi bi-person-lines-fill me-2"></i> Informasi Mahasiswa
          </h6>
          <dl class="row mb-0">
            <dt class="col-sm-4">NIM</dt>
            <dd class="col-sm-8" id="detail-nim"></dd>

            <dt class="col-sm-4">Nama Lengkap</dt>
            <dd class="col-sm-8" id="detail-nama"></dd>

            <dt class="col-sm-4">Dosen Pembimbing</dt>
            <dd class="col-sm-8" id="detail-dosen"></dd>
          </dl>
        </div>

        <!-- INFORMASI MAGANG (2 KOLM) -->
        <div class="mb-4 pb-3 border-bottom">
          <h6 class="fw-bold text-primary mb-3">
            <i class="bi bi-building-check me-2"></i> Informasi Magang
          </h6>
          <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
              <dl class="row mb-0">
                <dt class="col-sm-5">Mitra</dt>
                <dd class="col-sm-7" id="detail-mitra"></dd>

                <dt class="col-sm-5">Unit</dt>
                <dd class="col-sm-7" id="detail-unit"></dd>

                <dt class="col-sm-5">Pembimbing Mitra</dt>
                <dd class="col-sm-7" id="detail-pembimbing"></dd>

                <dt class="col-sm-5">Jabatan</dt>
                <dd class="col-sm-7" id="detail-jabatan"></dd>
              </dl>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
              <dl class="row mb-0">
                <dt class="col-sm-5">No. Handphone</dt>
                <dd class="col-sm-7" id="detail-hp"></dd>

                <dt class="col-sm-5">Email</dt>
                <dd class="col-sm-7" id="detail-email"></dd>

                <dt class="col-sm-5">Program</dt>
                <dd class="col-sm-7" id="detail-program"></dd>
              </dl>
            </div>
          </div>
        </div>

        <!-- PERIODE & STATUS (2 KOLM) -->
        <div>
          <h6 class="fw-bold text-primary mb-3">
            <i class="bi bi-calendar-event me-2"></i> Periode & Status
          </h6>
          <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
              <dl class="row mb-0">
                <dt class="col-sm-5">Tanggal Mulai</dt>
                <dd class="col-sm-7" id="detail-mulai"></dd>

                <dt class="col-sm-5">Tanggal Selesai</dt>
                <dd class="col-sm-7" id="detail-selesai"></dd>

                <dt class="col-sm-5">Status</dt>
                <dd class="col-sm-7" id="detail-status"></dd>
              </dl>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
              <dl class="row mb-0">
                <dt class="col-sm-5">Semester</dt>
                <dd class="col-sm-7" id="detail-semester"></dd>

                <dt class="col-sm-5">Tahun Ajaran</dt>
                <dd class="col-sm-7" id="detail-tahun"></dd>

                <dt class="col-sm-5">Keterangan</dt>
                <dd class="col-sm-7" id="detail-keterangan"></dd>
              </dl>
            </div>
          </div>
        </div>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i> Tutup
        </button>
      </div>
    </div>
  </div>
</div>


<!-- Modal Tambah Profil Magang -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg border-0 rounded-3">
      <form id="formTambahProfilMagang" method="post" action="<?= base_url('profilMagang/simpan') ?>">
        
        <div class="modal-header bg-primary text-white fw-bold rounded-top-3">
          <h5 class="modal-title text-white fw-bold">
             Tambah Profil Magang
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body p-4" style="max-height:70vh; overflow-y:auto;">
          <div class="row g-4">

            <!-- Mahasiswa -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-person-vcard"></i> Mahasiswa <span class="text-danger">*</span>
              </label>
              <input type="text" id="mahasiswaInput" class="form-control" placeholder="Cari Mahasiswa..." required>
              <input type="hidden" name="nim" id="nimValue">
            </div>

            <!-- Dosen Pembimbing -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-person-workspace"></i> Dosen Pembimbing <span class="text-danger">*</span>
              </label>
              <input type="text" id="dosenInput" class="form-control" placeholder="Cari Dosen..." required>
              <input type="hidden" name="nppy" id="dosenValue">
            </div>

            <!-- Mitra -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-building"></i> Mitra <span class="text-danger">*</span>
              </label>
              <input type="text" id="mitraInput" class="form-control" placeholder="Cari Mitra..." required>
              <input type="hidden" name="id_mitra" id="mitraValue">
            </div>

            <!-- Unit -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-diagram-3"></i> Unit (Opsional)
              </label>
              <input type="text" id="unitInput" class="form-control" placeholder="Cari Unit...">
              <input type="hidden" name="id_unit" id="unitValue">
            </div>

            <!-- Program -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-journal-text"></i> Program (Opsional)
              </label>
              <select name="id_program" class="form-select">
                <option value="">-- Pilih Program --</option>
                <?php foreach ($program as $p): ?>
                  <option value="<?= $p['id_program'] ?>"><?= esc($p['nama_program']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Tanggal Mulai -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-calendar-date"></i> Tanggal Mulai
              </label>
              <input type="date" name="tanggal_mulai" class="form-control">
            </div>

            <!-- Tanggal Selesai -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-calendar2-check"></i> Tanggal Selesai
              </label>
              <input type="date" name="tanggal_selesai" class="form-control">
            </div>

            <!-- Status -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-toggle-on"></i> Status
              </label>
              <select name="status" class="form-select">
                  <option value="aktif">Aktif</option>
                  <option value="tidak_aktif">Tidak Aktif</option>
                  <option value="selesai">Selesai</option>
                  <option value="tidak_selesai">Tidak Selesai</option>
              </select>
            </div>

            <!-- Keterangan -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-info-circle"></i> Keterangan
              </label>
              <select name="keterangan" class="form-select">
                <option value="Baru" selected>Baru (Pengajuan Baru)</option>
                <option value="Ulang">Ulang (Lanjutan dari semester sebelumnya)</option>
              </select>
            </div>

          </div>
        </div>

        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary fw-bold">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Profil Magang -->
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg border-0 rounded-3">
      <form id="formEditProfilMagang" method="post" action="<?= base_url('profilMagang/updateAjax') ?>">
        
        <input type="hidden" name="id_profil" id="edit-id-profil">

        <!-- HEADER -->
        <div class="modal-header bg-primary text-white fw-bold rounded-top-3">
          <h5 class="modal-title text-white fw-bold">
             Edit Profil Magang
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- BODY -->
        <div class="modal-body p-4" style="max-height:70vh; overflow-y:auto;">
          <div class="row g-4">

            <!-- Mahasiswa -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-person-vcard"></i> Mahasiswa
              </label>
              <input type="text" id="editMahasiswaInput" class="form-control bg-light" readonly>
              <input type="hidden" name="nim" id="editNimValue">
            </div>

            <!-- Dosen Pembimbing -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-person-workspace"></i> Dosen Pembimbing <span class="text-danger">*</span>
              </label>
              <input type="text" id="editDosenInput" class="form-control" placeholder="Cari Dosen..." required>
              <input type="hidden" name="nppy" id="editDosenValue">
            </div>

            <!-- Mitra -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-building"></i> Mitra <span class="text-danger">*</span>
              </label>
              <input type="text" id="editMitraInput" class="form-control" placeholder="Cari Mitra..." required>
              <input type="hidden" name="id_mitra" id="editMitraValue">
            </div>

            <!-- Unit -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-diagram-3"></i> Unit (Opsional)
              </label>
              <input type="text" id="editUnitInput" class="form-control" placeholder="Cari Unit...">
              <input type="hidden" name="id_unit" id="editUnitValue">
            </div>

            <!-- Program -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-journal-text"></i> Program (Opsional)
              </label>
              <select name="id_program" id="editProgramSelect" class="form-select">
                <option value="">-- Pilih Program --</option>
                <?php foreach ($program as $p): ?>
                  <option value="<?= $p['id_program'] ?>"><?= esc($p['nama_program']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Tanggal Mulai -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-calendar-date"></i> Tanggal Mulai
              </label>
              <input type="date" name="tanggal_mulai" id="editTanggalMulai" class="form-control">
            </div>

            <!-- Tanggal Selesai -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-calendar2-check"></i> Tanggal Selesai
              </label>
              <input type="date" name="tanggal_selesai" id="editTanggalSelesai" class="form-control">
            </div>

            <!-- Status -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-toggle-on"></i> Status
              </label>
              <select name="status" id="editStatusSelect" class="form-select">
                  <option value="aktif">Aktif</option>
                  <option value="tidak_aktif">Tidak Aktif</option>
                  <option value="selesai">Selesai</option>
                  <option value="tidak_selesai">Tidak Selesai</option>
              </select>
            </div>

            <!-- Keterangan -->
            <div class="col-md-6">
              <label class="form-label">
                <i class="bi bi-info-circle"></i> Keterangan
              </label>
              <select name="keterangan" id="editKeteranganSelect" class="form-select">
                <option value="Baru">Baru</option>
                <option value="Ulang">Ulang</option>
              </select>
            </div>

          </div>
        </div>

        <!-- FOOTER -->
        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
            Batal
          </button>
          <button type="submit" class="btn btn-primary fw-bold text-white">
             Update
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<!-- Modal Hapus Profil Magang -->
<div class="modal fade" id="modalHapus" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white fw-bold">
        <h5 class="modal-title text-white fw-bold">Konfirmasi Hapus Profil Magang</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus profil magang milik 
          <strong id="hapusNama"></strong>? <br>
          Data profil magang ini akan <b>diarsipkan</b> (soft delete) dan dapat dipulihkan kembali.</p>
        <input type="hidden" id="hapusId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary fw-bold" id="btnKonfirmasiHapus">Hapus</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal Hapus Profil Magang -->

<!-- Modal Import Excel -->
<div class="modal fade" id="modalImport" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow border-0 rounded-3">
      <form id="formImportExcel" enctype="multipart/form-data">
        <!-- HEADER -->
        <div class="modal-header bg-primary text-white fw-bold rounded-top-3">
          <h5 class="modal-title text-white fw-bold">Import Data Profil Magang</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- BODY -->
        <div class="modal-body">
          <div id="importAlert" class="alert alert-danger d-none"></div>
          <div class="input-group mb-3">
            <input type="file" class="form-control" name="file_excel" id="fileExcel" accept=".xls,.xlsx" required>
          </div>
          <small class="text-muted">
            Hanya mendukung file dengan format <strong>.xls</strong> atau <strong>.xlsx</strong>.
          </small>
        </div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary fw-bold rounded-3">Import</button>
        </div>
      </form>
    </div>
  </div>
</div>




<!-- letakkan jQuery paling atas sebelum dipakai autocomplete -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- lalu baru script lain -->
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="assets/static/js/pages/datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function(){
    $('#table1').DataTable();
});
</script>

<script>
  // === DETAIL PROFIL MAGANG ===
$(document).on('click', '.btn-detail', function() {
    var id = $(this).data('id');

    $.ajax({
        url: "<?= base_url('profilMagang/detail') ?>/" + id,
        type: "GET",
        dataType: "json",
        success: function(data) {
            if (data) {
                // Informasi Mahasiswa
                $('#detail-nim').text(data.nim);
                $('#detail-nama').text(data.nama_lengkap);
                $('#detail-dosen').text(data.nama_dosen ?? '-');

                // Informasi Magang
                $('#detail-mitra').text(data.nama_mitra);
                $('#detail-unit').text(data.nama_unit ?? '-');
                $('#detail-pembimbing').text(data.nama_pembimbing ?? '-');
                $('#detail-jabatan').text(data.jabatan ?? '-');
                $('#detail-hp').text(data.no_hp ?? '-');
                $('#detail-email').text(data.email ?? '-');
                $('#detail-program').text(data.nama_program ?? '-');

                // Periode & Status
                $('#detail-mulai').text(data.tanggal_mulai);
                $('#detail-selesai').text(data.tanggal_selesai);
                $('#detail-status').html(
                    data.status === 'aktif' ? '<span class="badge bg-success">Aktif</span>' :
                    data.status === 'selesai' ? '<span class="badge bg-primary">Selesai</span>' :
                    data.status === 'tidak selesai' ? '<span class="badge bg-warning text-dark">Tidak Selesai</span>' :
                    data.status === 'tidak aktif' ? '<span class="badge bg-danger">Tidak Aktif</span>' :
                    '<span class="badge bg-secondary">-</span>'
                );

                // Semester, Tahun Ajaran, Keterangan
                $('#detail-semester').text(data.semester ?? '-');
                $('#detail-tahun').text(data.tahun_ajaran ?? '-');
                $('#detail-keterangan').text(data.keterangan ?? '-');

                $('#modalDetail').modal('show');
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Data tidak dapat dimuat.',
                confirmButtonColor: '#d33'
            });
        }
    });
});
</script>


<script>
  // === AJAX SIMPAN PROFIL MAGANG ===
  $('#formTambahProfilMagang').on('submit', function(e){
      e.preventDefault();
      let formData = new FormData(this);

      fetch("<?= base_url('profilMagang/simpanAjax') ?>", {
          method: 'POST',
          body: formData
      })
      .then(res => res.json())
      .then(data => {
          // Hapus alert error lama di modal
          $('#modalTambah .modal-body .alert').remove();

          if (data.status === 'success') {
              location.reload(); // biar flashdata success muncul
          } else {
              let msg = data.message;
              if (typeof msg === 'object') {
                  msg = Object.values(msg).join("<br>");
              }
              $('#modalTambah .modal-body').prepend(`
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <i class="bi bi-exclamation-circle"></i> ${msg}
                      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>
              `);
          }
      })
      .catch(err => {
          console.error(err);
          $('#modalTambah .modal-body').prepend(`
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="bi bi-exclamation-circle"></i> Terjadi kesalahan server
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
          `);
      });
  });
</script>


<script>
$(function(){
  console.log("✅ Autocomplete aktif");

  // =========================================================
  // FUNGSI AUTOCOMPLETE (mendukung URL biasa & handler custom)
  // =========================================================
  function setupAutocomplete(input, hidden, sourceHandler){
      $(input).autocomplete({
          source: function(req, res){
              if (typeof sourceHandler === "function") {
                  // Handler custom (misalnya untuk filter unit berdasarkan mitra)
                  sourceHandler(req, res);
              } else {
                  // Handler default (URL string)
                  $.getJSON(sourceHandler, { q: req.term }, function(data){
                      res(data.slice(0, 5));
                  });
              }
          },
          minLength: 1,
          select: function(e, ui){
              $(input).val(ui.item.label);
              $(hidden).val(ui.item.id);
              return false;
          },
          open: function(){
              var $input = $(this);
              $(".ui-autocomplete").css({
                  "z-index": 3000,
                  "background": "#fff",
                  "border": "1px solid #ced4da",
                  "border-radius": "0.375rem",
                  "box-shadow": "0 4px 8px rgba(0,0,0,0.1)",
                  "max-height": "200px",
                  "overflow-y": "auto",
                  "width": $input.outerWidth() + "px",
                  "box-sizing": "border-box",
                  "padding": "0",
                  "margin": "0"
              });
              $(".ui-menu .ui-menu-item-wrapper").css({
                  "padding":"8px 12px",
                  "cursor":"pointer",
                  "font-size":"14px",
                  "border-bottom":"1px solid #e9ecef",
                  "box-sizing":"border-box",
                  "width":"100%"
              });
              $(".ui-menu .ui-menu-item-wrapper").hover(
                  function(){ $(this).css({"background-color":"#0d6efd","color":"#fff"}); },
                  function(){ $(this).css({"background-color":"#fff","color":"#000"}); }
              );
              $(".ui-menu .ui-menu-item-wrapper:last-child").css({"border-bottom":"none"});
          }
      });
  }

  // =========================================================
  // AUTOCOMPLETE MHS, DOSEN, MITRA (DEFAULT)
  // =========================================================
  setupAutocomplete("#mahasiswaInput", "#nimValue",
      "<?= base_url('profilMagang/searchMahasiswa') ?>");

  setupAutocomplete("#dosenInput", "#dosenValue",
      "<?= base_url('profilMagang/searchDosen') ?>");

  setupAutocomplete("#mitraInput", "#mitraValue",
      "<?= base_url('profilMagang/searchMitra') ?>");

  // =========================================================
  // RESET UNIT KETIKA MITRA BERUBAH
  // =========================================================
  $("#mitraInput").on("autocompleteselect", function (e, ui) {
      $("#mitraValue").val(ui.item.id);

      // kosongkan unit
      $("#unitInput").val("");
      $("#unitValue").val("");

      console.log("🔄 Unit di-reset karena mitra berubah");
  });

  // =========================================================
  // AUTOCOMPLETE UNIT DENGAN FILTER id_mitra
  // =========================================================
  setupAutocomplete("#unitInput", "#unitValue", function(req, res){
      $.getJSON("<?= base_url('profilMagang/searchUnit') ?>", { 
          q: req.term,
          id_mitra: $("#mitraValue").val() // kirim ke controller
      }, function(data){
          res(data.slice(0, 5));
      });
  });

});
</script>


<script>
$(document).ready(function() {

    // =========================================================
    //  FUNGSI AUTOCOMPLETE (Support URL & Custom Handler)
    // =========================================================
    function setupAutocomplete(input, hidden, sourceHandler){
        $(input).autocomplete({
            source: function(req, res){
                if (typeof sourceHandler === "function") {
                    sourceHandler(req, res); // handler custom
                } else {
                    $.getJSON(sourceHandler, { q: req.term }, function(data){
                        res(data.slice(0, 5));
                    });
                }
            },
            minLength: 1,
            select: function(e, ui){
                $(input).val(ui.item.label);
                $(hidden).val(ui.item.id);
                return false;
            },
            open: function(){
                var $input = $(this);
                $(".ui-autocomplete").css({
                    "z-index": 3000,
                    "background": "#fff",
                    "border": "1px solid #ced4da",
                    "border-radius": "0.375rem",
                    "box-shadow": "0 4px 8px rgba(0,0,0,0.1)",
                    "max-height": "200px",
                    "overflow-y": "auto",
                    "width": $input.outerWidth() + "px",
                    "box-sizing": "border-box",
                    "padding": "0",
                    "margin": "0"
                });
                $(".ui-menu .ui-menu-item-wrapper").css({
                    "padding":"8px 12px",
                    "cursor":"pointer",
                    "font-size":"14px",
                    "border-bottom":"1px solid #e9ecef",
                    "box-sizing":"border-box",
                    "width":"100%"
                });
                $(".ui-menu .ui-menu-item-wrapper").hover(
                    function(){ $(this).css({"background-color":"#0d6efd","color":"#fff"}); },
                    function(){ $(this).css({"background-color":"#fff","color":"#000"}); }
                );
                $(".ui-menu .ui-menu-item-wrapper:last-child").css({"border-bottom":"none"});
            }
        });
    }

    const baseUrl = "<?= base_url() ?>";


    // =========================================================
    //  AUTOCOMPLETE INPUT TAMBAH DATA (FORM UTAMA)
    // =========================================================
    setupAutocomplete("#mahasiswaInput", "#nimValue",
        baseUrl + "/profilMagang/searchMahasiswa");

    setupAutocomplete("#dosenInput", "#dosenValue",
        baseUrl + "/profilMagang/searchDosen");

    setupAutocomplete("#mitraInput", "#mitraValue",
        baseUrl + "/profilMagang/searchMitra");

    // RESET UNIT ketika MITRA berubah
    $("#mitraInput").on("autocompleteselect", function (e, ui) {
        $("#mitraValue").val(ui.item.id);
        $("#unitInput").val("");
        $("#unitValue").val("");
        console.log("🔄 Unit di-reset karena mitra berubah");
    });

    // Autocomplete UNIT dengan filter id_mitra
    setupAutocomplete("#unitInput", "#unitValue", function(req, res){
        $.getJSON(baseUrl + "/profilMagang/searchUnit", { 
            q: req.term,
            id_mitra: $("#mitraValue").val()
        }, function(data){
            res(data.slice(0, 5));
        });
    });



    // =========================================================
    //  TOMBOL EDIT PROFIL MAGANG
    // =========================================================
    $(document).on('click', '.btn-edit', function(){
        let id = $(this).data('id');

        $.ajax({
            url: baseUrl + "/profilMagang/detail/" + id,
            type: "GET",
            dataType: "json",
            success: function(data){
                if(data){

                    // Isi form modal EDIT
                    $('#edit-id-profil').val(data.id_profil);
                    $('#editNimValue').val(data.nim);
                    $('#editMahasiswaInput').val(data.nim + ' - ' + data.nama_lengkap);
                    $('#editDosenInput').val(data.nama_dosen);
                    $('#editMitraInput').val(data.nama_mitra);
                    $('#editUnitInput').val(data.nama_unit);
                    $('#editTanggalMulai').val(data.tanggal_mulai);
                    $('#editTanggalSelesai').val(data.tanggal_selesai);
                    $('#editStatusSelect').val(data.status);
                    $('#editProgramSelect').val(data.id_program);
                    $('#editDosenValue').val(data.nppy);
                    $('#editMitraValue').val(data.id_mitra);
                    $('#editUnitValue').val(data.id_unit);
                    $('#editKeteranganSelect').val(data.keterangan);

                    // Tampilkan modal
                    $('#modalEdit').modal('show');

                    // AUTOCOMPLETE EDIT
                    setupAutocomplete("#editDosenInput", "#editDosenValue",
                        baseUrl + "/profilMagang/searchDosen");

                    setupAutocomplete("#editMitraInput", "#editMitraValue",
                        baseUrl + "/profilMagang/searchMitra");

                    // RESET UNIT ketika MITRA EDIT berubah
                    $("#editMitraInput").on("autocompleteselect", function (e, ui) {
                        $("#editMitraValue").val(ui.item.id);
                        $("#editUnitInput").val("");
                        $("#editUnitValue").val("");
                        console.log("🔄 Unit edit di-reset karena mitra edit berubah");
                    });

                    // UNIT Autocomplete EDIT + filter id_mitra
                    setupAutocomplete("#editUnitInput", "#editUnitValue", function(req, res){
                        $.getJSON(baseUrl + "/profilMagang/searchUnit", { 
                            q: req.term,
                            id_mitra: $("#editMitraValue").val()
                        }, function(data){
                            res(data.slice(0, 5));
                        });
                    });
                }
            },
            error: function(){
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Data tidak dapat dimuat.',
                    confirmButtonColor: '#d33'
                });
            }
        });
    });



    // =========================================================
    //  AJAX UPDATE PROFIL MAGANG
    // =========================================================
    $('#formEditProfilMagang').on('submit', function(e){
        e.preventDefault();
        let formData = new FormData(this);

        fetch(baseUrl + "/profilMagang/updateAjax", {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            $('#modalEdit .modal-body .alert').remove();

            if (data.status === 'success') {
                $('#modalEdit').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1200
                });

                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1200);

            } else {
                let msg = data.message;
                if (typeof msg === 'object') {
                    msg = Object.values(msg).join("<br>");
                }
                $('#modalEdit .modal-body').prepend(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i> ${msg}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
            }
        })
        .catch(err => {
            console.error(err);
            $('#modalEdit .modal-body').prepend(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> Terjadi kesalahan server
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
        });
    });

});
</script>


<script>
$(document).ready(function() {

    // ======= TOMBOL HAPUS PROFIL MAGANG =======
    $(document).on('click', '.btn-delete', function(){
        let id = $(this).data('id');
        let nama = $(this).data('nama');

        // Tampilkan konfirmasi hapus dengan SweetAlert
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Data profil magang <strong>${nama}</strong> akan dihapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                hapusProfilMagang(id);
            }
        });
    });

    // ======= FUNGSI AJAX HAPUS PROFIL MAGANG =======
    function hapusProfilMagang(id) {
        fetch("<?= base_url('profilMagang/hapusAjax') ?>/" + id, {
            method: 'DELETE'
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                });

                // Reload halaman setelah sedikit delay
                setTimeout(() => {
                    location.reload();
                }, 1500);

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Data tidak dapat dihapus.',
                    confirmButtonColor: '#d33'
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Server!',
                text: 'Tidak dapat terhubung ke server.',
                confirmButtonColor: '#d33'
            });
        });
    }

});
</script>

<script>
$("#formImportExcel").on("submit", function (e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        url: "<?= base_url('profilMagang/importExcel') ?>",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        beforeSend: function () {
            $("#formImportExcel button[type=submit]").prop("disabled", true).text("Mengimport...");
            $("#importAlert").addClass("d-none").text("");
        },
        success: function (res) {
            if (res.status) {
                // ✅ Tutup modal import
                var modal = bootstrap.Modal.getInstance(document.getElementById("modalImport"));
                if (modal) modal.hide();

                // ✅ Tampilkan SweetAlert notifikasi singkat
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: res.message,
                    timer: 1200,
                    showConfirmButton: false
                });

                // 🔁 Reload halaman agar flashdata muncul di view
                setTimeout(() => {
                    location.reload();
                }, 1300);
            } else {
                // ❌ Jika gagal
                $("#importAlert").removeClass("d-none").text(res.message);
            }
        },
        error: function () {
            $("#importAlert").removeClass("d-none").text("Terjadi kesalahan saat mengimport data.");
        },
        complete: function () {
            $("#formImportExcel button[type=submit]").prop("disabled", false).text("Import");
        }
    });
});
</script>



<?= $this->endSection() ?>
