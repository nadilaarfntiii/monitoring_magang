<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>
Kelola Mitra
<?= $this->endSection() ?>

<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<?= $this->section('content') ?>

<!-- CSS -->
<style>
    .label-with-icon {
    display: flex;
    align-items: center;  /* sejajarkan icon dan teks */
    gap: 6px;             /* jarak antara icon dan teks */
    font-weight: 700;     /* Bold */
    font-size: 0.9rem;
    color: #212529;       /* warna teks dark */
    margin-bottom: 4px;   /* biar rapih sama input */
    }

    .label-with-icon i {
    font-size: 1rem;      /* ukuran icon */
    color: #212529;       /* warna icon dark */
    line-height: 1;       /* hilangkan space bawah */
    display: flex;        /* pastikan icon ikut flex tengah */
    align-items: center;
    }
</style>
            <!-- Page Heading & Breadcrumb -->
            <div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Kelola Mitra</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kelola Mitra</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Daftar Mitra</h5>
        <div class="d-flex gap-2">
          <!-- Tombol Import Excel -->
          <a href="javascript:void(0)" 
            class="btn btn-success btn-sm fw-bold d-flex align-items-center justify-content-center gap-2 rounded"
            data-bs-toggle="modal" 
            data-bs-target="#modalImport">
            <i class="bi bi-file-earmark-excel" style="font-size:16px; line-height:1; vertical-align:middle; margin-top:-1px;"></i>
             Import Excel
          </a>

          <!-- Tombol Tambah Mitra -->
          <a href="javascript:void(0)" 
              class="btn btn-primary btn-sm fw-bold d-flex align-items-center justify-content-center gap-2 rounded btn-tambah"
              data-bs-toggle="modal" 
              data-bs-target="#modalTambah">
              <i class="bi bi-building-add"></i> Tambah Mitra
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
                  <th>ID Mitra</th>
                  <th>Nama Mitra</th>
                  <th>Bidang Usaha</th>
                  <th>Kota</th>
                  <th>Status</th>
                  <th>Aksi</th>
              </tr>
          </thead>
          <tbody>
          <?php if (empty($mitra)): ?>
              <tr>
                  <td colspan="8" class="text-center text-muted fw-bold">
                      Belum ada mitra yang terdaftar.
                  </td>
              </tr>
          <?php else: ?>
              <?php $no = 1; foreach ($mitra as $m): ?>
                  <tr>
                      <td><?= $no++ ?></td>
                      <td><?= esc($m['id_mitra']) ?></td>
                      <td><?= esc(mb_strimwidth($m['nama_mitra'], 0, 25, "...")) ?></td>
                      <td><?= esc(mb_strimwidth($m['bidang_usaha'], 0, 25, "...")) ?></td>
                      <td><?= esc(mb_strimwidth($m['kota'], 0, 25, "...")) ?></td>
                      <td>
                          <?php if ($m['status_mitra'] === 'Aktif'): ?>
                              <span class="badge bg-success">Aktif</span>
                          <?php else: ?>
                              <span class="badge bg-danger">Nonaktif</span>
                          <?php endif; ?>
                      </td>
                      <td>
                        <!-- Tombol Detail -->
                        <a href="javascript:void(0)" 
                            class="btn btn-info btn-sm btn-detail" 
                            data-id="<?= $m['id_mitra'] ?>">
                            <i class="bi bi-eye"></i>
                        </a>
                        <!-- Tombol Edit -->
                        <a href="javascript:void(0)" 
                            class="btn btn-warning btn-sm btn-edit" 
                            data-id="<?= $m['id_mitra'] ?>">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <!-- Tombol Hapus -->
                        <a href="javascript:void(0)" 
                        class="btn btn-danger btn-sm btn-hapus" 
                        data-id="<?= $m['id_mitra'] ?>" 
                        data-nama="<?= $m['nama_mitra'] ?>">
                        <i class="bi bi-trash"></i>
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

<!-- ===================== MODALS ===================== -->
<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      
      <!-- HEADER -->
      <div class="modal-header bg-primary text-white fw-bold">
        <h5 class="modal-title text-white fw-bold">
          Detail Mitra
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body">

        <!-- Foto Mitra (persegi penuh lebar modal) -->
        <div class="mb-3">
          <img id="detailFotoMitra" 
               src="<?= base_url('assets/images/default.jpg') ?>" 
               alt="Foto Mitra" 
               class="w-100" 
               style="height: 275px; object-fit: cover; border:1px solid #0d6efd;">
        </div>

        <!-- Nama Mitra -->
        <h4 id="detailNamaMitra" class="fw-bold text-dark mb-1 text-center"></h4>
        <p class="text-muted fw-semibold text-center mb-3">
          Detail Mitra - <span id="detailNamaMitraText"></span>
        </p>

        <!-- Tabel Detail -->
        <div class="table-responsive mt-3">
          <table class="table table-bordered">
            <tr><th width="30%">ID Mitra</th><td id="detailIdMitra"></td></tr>
            <tr><th>Nama Mitra</th><td id="detailNamaMitraTable"></td></tr>
            <tr><th>Bidang Usaha</th><td id="detailBidangUsaha"></td></tr>
            <tr><th>Alamat</th><td id="detailAlamat"></td></tr>
            <tr><th>Kota</th><td id="detailKota"></td></tr>
            <tr><th>Kode Pos</th><td id="detailKodePos"></td></tr>
            <tr><th>Provinsi</th><td id="detailProvinsi"></td></tr>
            <tr><th>Negara</th><td id="detailNegara"></td></tr>
            <tr><th>No. Telepon</th><td id="detailNoTelp"></td></tr>
            <tr><th>Email</th><td id="detailEmail"></td></tr>
            <tr><th>Status</th><td id="detailStatus"></td></tr>
          </table>
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
<!-- End Modal Detail -->

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg border-0 rounded-3">
      <form id="formTambahMitra">
        <!-- HEADER -->
        <div class="modal-header bg-primary text-white fw-bold rounded-top-3">
          <h5 class="modal-title text-white fw-bold">Tambah Mitra</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- BODY -->
        <div class="modal-body p-4" style="max-height:70vh; overflow-y:auto;">
          <div class="row g-4">

            <!-- Nama Mitra -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-person-badge"></i>
                <span>Nama Mitra</span>
              </label>
              <input type="text" name="nama_mitra" class="form-control rounded-3 shadow-sm" required>
            </div>

            <!-- Bidang Usaha (Tambah) -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-briefcase"></i>
                <span>Bidang Usaha</span>
              </label>
              <select name="bidang_usaha" class="form-select rounded-3 shadow-sm" required>
                    <option value="">-- Pilih --</option>
                    <option value="Perseroan Terbatas (PT)">Perseroan Terbatas (PT)</option>
                    <option value="Perusahaan Umum (Perum)">Perusahaan Umum (Perum)</option>
                    <option value="Perusahaan Perseroan (Persero)">Perusahaan Perseroan (Persero)</option>
                    <option value="Perusahaan Daerah (Prusda)">Perusahaan Daerah (Prusda)</option>
                    <option value="Firma (Fa)">Firma (Fa)</option>
                    <option value="Perseroan Komanditer (CV)">Perseroan Komanditer/Commanditaire Vennootschap (CV)</option>
                    <option value="Koperasi">Koperasi</option>
                    <option value="Yayasan">Yayasan</option>
                    <option value="Sekolah">Sekolah</option>
                    <option value="Perguruan Tinggi">Perguruan Tinggi</option>
                    <option value="Instansi Pemerintah">Instansi Pemerintah</option>
                    <option value="Instansi dari Kampus">Instansi dari Kampus</option>
              </select>
            </div>

            <!-- Alamat -->
            <div class="col-12">
              <label class="form-label label-with-icon">
                <i class="bi bi-geo-alt"></i>
                <span>Alamat</span>
              </label>
              <textarea name="alamat" class="form-control rounded-3 shadow-sm" rows="2"></textarea>
            </div>

            <!-- Kota -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-buildings"></i>
                <span>Kota</span>
              </label>
              <input type="text" name="kota" class="form-control rounded-3 shadow-sm">
            </div>

            <!-- Kode Pos -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-mailbox"></i>
                <span>Kode Pos</span>
              </label>
              <input type="text" name="kode_pos" class="form-control rounded-3 shadow-sm">
            </div>

            <!-- Provinsi -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-map"></i>
                <span>Provinsi</span>
              </label>
              <input type="text" name="provinsi" class="form-control rounded-3 shadow-sm">
            </div>

            <!-- Negara -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-globe"></i>
                <span>Negara</span>
              </label>
              <input type="text" name="negara" class="form-control rounded-3 shadow-sm" value="Indonesia">
            </div>

            <!-- No. Telepon -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-telephone"></i>
                <span>No. Telepon</span>
              </label>
              <input type="text" name="no_telp" class="form-control rounded-3 shadow-sm">
            </div>

            <!-- Email -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-envelope"></i>
                <span>Email</span>
              </label>
              <input type="email" name="email" class="form-control rounded-3 shadow-sm">
            </div>

            <!-- Status Mitra -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <span>Status Aktif</span>
              </label>
              <select name="status_mitra" class="form-select rounded-3 shadow-sm">
                <option value="Aktif">Aktif</option>
                <option value="Nonaktif">Nonaktif</option>
              </select>
            </div>

          </div>
        </div>

        <!-- FOOTER -->
        <div class="modal-footer border-top-0 d-flex justify-content-end p-3">
          <button type="button" class="btn btn-light border rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary fw-bold rounded-3 px-4 shadow-sm">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Tambah -->

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg border-0 rounded-3">
      <form id="formEditMitra">
        <!-- HEADER -->
        <div class="modal-header bg-primary text-white fw-bold rounded-top-3">
          <h5 class="modal-title text-white fw-bold">Edit Mitra</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- BODY -->
        <div class="modal-body p-4" style="max-height:70vh; overflow-y:auto;">
          <input type="hidden" name="id_mitra" id="editIdMitra">
          <div class="row g-4">

            <!-- Nama Mitra -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-person-badge"></i>
                <span>Nama Mitra</span>
              </label>
              <input type="text" name="nama_mitra" id="editNamaMitra" class="form-control rounded-3 shadow-sm" required>
            </div>

            <!-- Bidang Usaha (Edit) -->
              <div class="col-md-6">
                <label class="form-label label-with-icon">
                  <i class="bi bi-briefcase"></i>
                  <span>Bidang Usaha</span>
                </label>
                <select name="bidang_usaha" id="editBidangUsaha" class="form-select rounded-3 shadow-sm" required>
                    <option value="">-- Pilih --</option>
                    <option value="Perseroan Terbatas (PT)">Perseroan Terbatas (PT)</option>
                    <option value="Perusahaan Umum (Perum)">Perusahaan Umum (Perum)</option>
                    <option value="Perusahaan Perseroan (Persero)">Perusahaan Perseroan (Persero)</option>
                    <option value="Perusahaan Daerah (Prusda)">Perusahaan Daerah (Prusda)</option>
                    <option value="Firma (Fa)">Firma (Fa)</option>
                    <option value="Perseroan Komanditer (CV)">Perseroan Komanditer/Commanditaire Vennootschap (CV)</option>
                    <option value="Koperasi">Koperasi</option>
                    <option value="Yayasan">Yayasan</option>
                    <option value="Sekolah">Sekolah</option>
                    <option value="Perguruan Tinggi">Perguruan Tinggi</option>
                    <option value="Instansi Pemerintah">Instansi Pemerintah</option>
                    <option value="Instansi dari Kampus">Instansi dari Kampus</option>
                </select>
              </div>

            <!-- Alamat -->
            <div class="col-12">
              <label class="form-label label-with-icon">
                <i class="bi bi-geo-alt"></i>
                <span>Alamat</span>
              </label>
              <textarea name="alamat" id="editAlamat" class="form-control rounded-3 shadow-sm" rows="2"></textarea>
            </div>

            <!-- Kota -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-buildings"></i>
                <span>Kota</span>
              </label>
              <input type="text" name="kota" id="editKota" class="form-control rounded-3 shadow-sm">
            </div>

            <!-- Kode Pos -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-mailbox"></i>
                <span>Kode Pos</span>
              </label>
              <input type="text" name="kode_pos" id="editKodePos" class="form-control rounded-3 shadow-sm">
            </div>

            <!-- Provinsi -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-map"></i>
                <span>Provinsi</span>
              </label>
              <input type="text" name="provinsi" id="editProvinsi" class="form-control rounded-3 shadow-sm">
            </div>

            <!-- Negara -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-globe"></i>
                <span>Negara</span>
              </label>
              <input type="text" name="negara" id="editNegara" class="form-control rounded-3 shadow-sm">
            </div>

            <!-- No. Telepon -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-telephone"></i>
                <span>No. Telepon</span>
              </label>
              <input type="text" name="no_telp" id="editNoTelp" class="form-control rounded-3 shadow-sm">
            </div>

            <!-- Email -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-envelope"></i>
                <span>Email</span>
              </label>
              <input type="email" name="email" id="editEmail" class="form-control rounded-3 shadow-sm">
            </div>

            <!-- Status Mitra -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <span>Status Aktif</span>
              </label>
              <select name="status_mitra" id="editStatusMitra" class="form-select rounded-3 shadow-sm">
                <option value="Aktif">Aktif</option>
                <option value="Nonaktif">Nonaktif</option>
              </select>
            </div>

          </div>
        </div>

        <!-- FOOTER -->
        <div class="modal-footer border-top-0 d-flex justify-content-end p-3">
          <button type="button" class="btn btn-light border rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary fw-bold rounded-3 px-4 shadow-sm">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Edit -->

<!-- Modal Hapus Mitra -->
<div class="modal fade" id="modalHapus" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white fw-bold">
        <h5 class="modal-title text-white fw-bold">Konfirmasi Hapus Mitra</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus mitra <strong id="hapusNama"></strong>? 
        Data mitra akan diarsipkan dan dapat dipulihkan kembali.</p>
        <input type="hidden" id="hapusId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary fw-bold" id="btnKonfirmasiHapus">Hapus</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal Hapus -->

<!-- Modal Import Excel -->
<div class="modal fade" id="modalImport" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow border-0 rounded-3">
      <form id="formImportExcel" enctype="multipart/form-data">
        <!-- HEADER -->
        <div class="modal-header bg-primary text-white fw-bold rounded-top-3">
          <h5 class="modal-title text-white fw-bold">Import Data Mitra</h5>
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

<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script> 
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="assets/static/js/pages/datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    document.addEventListener("click", function(e) {

        // Jika tombol diklik adalah .btn-detail (pakai event delegation)
        let btn = e.target.closest(".btn-detail");
        if (!btn) return; // kalau yang diklik bukan tombol detail → stop

        let id = btn.dataset.id; // ambil data-id dari tombol

        // Fetch data mitra
        fetch("<?= base_url('kelola_mitra/detail/') ?>/" + id)
            .then(response => response.json())
            .then(res => {
                if (res.status) {
                    let d = res.data;

                    // Isi field modal detail
                    document.getElementById("detailIdMitra").textContent = d.id_mitra;
                    document.getElementById("detailNamaMitra").textContent = d.nama_mitra;
                    document.getElementById("detailNamaMitraText").textContent = d.nama_mitra;
                    document.getElementById("detailNamaMitraTable").textContent = d.nama_mitra;

                    document.getElementById("detailBidangUsaha").textContent = d.bidang_usaha ?? "-";
                    document.getElementById("detailAlamat").textContent = d.alamat ?? "-";
                    document.getElementById("detailKota").textContent = d.kota ?? "-";
                    document.getElementById("detailKodePos").textContent = d.kode_pos ?? "-";
                    document.getElementById("detailProvinsi").textContent = d.provinsi ?? "-";
                    document.getElementById("detailNegara").textContent = d.negara ?? "-";
                    document.getElementById("detailNoTelp").textContent = d.no_telp ?? "-";
                    document.getElementById("detailEmail").textContent = d.email ?? "-";

                    // Status badge
                    document.getElementById("detailStatus").innerHTML =
                        (d.status_mitra === "Aktif")
                            ? '<span class="badge bg-success">Aktif</span>'
                            : '<span class="badge bg-danger">Nonaktif</span>';

                    // Foto (fallback default)
                    document.getElementById("detailFotoMitra").src =
                        d.foto
                            ? "<?= base_url('uploads/mitra/') ?>/" + d.foto
                            : "<?= base_url('assets/images/default.jpg') ?>";

                    // Tampilkan modal
                    new bootstrap.Modal(document.getElementById("modalDetail")).show();
                } else {
                    alert(res.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert("Terjadi kesalahan.");
            });

    });

});
</script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formTambahMitra").addEventListener("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("<?= base_url('kelola_mitra/simpanAjax') ?>", {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.status) {
                // ✅ berhasil → reload halaman agar flashdata tampil
                location.reload();
            } else {
                // ❌ gagal → tampilkan pesan error
                alert(res.message);
                if (res.errors) {
                    console.error("Validation errors:", res.errors);
                }
            }
        })
        .catch(err => {
            console.error("Fetch error:", err);
            alert("Terjadi kesalahan saat menyimpan data");
        });
    });
});
</script>

<!-- Js Mengambil value data yang diedit -->
<script>
document.addEventListener("DOMContentLoaded", function() {

    document.addEventListener("click", function(e) {

        // Cek apakah tombol yang diklik adalah tombol Edit
        let btn = e.target.closest(".btn-edit");
        if (!btn) return; // kalau bukan btn-edit → stop

        let id = btn.dataset.id; // ambil data-id

        // Fetch data mitra
        fetch("<?= base_url('kelola_mitra/detail/') ?>/" + id)
            .then(response => response.json())
            .then(res => {
                if (res.status) {
                    let d = res.data;

                    // Isi field modal edit
                    document.getElementById("editIdMitra").value = d.id_mitra;
                    document.getElementById("editNamaMitra").value = d.nama_mitra;
                    document.getElementById("editBidangUsaha").value = d.bidang_usaha ?? "";
                    document.getElementById("editAlamat").value = d.alamat ?? "";
                    document.getElementById("editKota").value = d.kota ?? "";
                    document.getElementById("editKodePos").value = d.kode_pos ?? "";
                    document.getElementById("editProvinsi").value = d.provinsi ?? "";
                    document.getElementById("editNegara").value = d.negara ?? "Indonesia";
                    document.getElementById("editNoTelp").value = d.no_telp ?? "";
                    document.getElementById("editEmail").value = d.email ?? "";
                    document.getElementById("editStatusMitra").value = d.status_mitra;

                    // Tampilkan modal edit
                    new bootstrap.Modal(document.getElementById("modalEdit")).show();

                } else {
                    alert("Data mitra tidak ditemukan");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Terjadi kesalahan saat mengambil data.");
            });

    });

});
</script>

<!-- Js submit edit -->
<script>
document.getElementById("formEditMitra").addEventListener("submit", function(e) {
  e.preventDefault();

  let formData = new FormData(this);

  fetch("<?= base_url('kelola_mitra/updateAjax') ?>", {
    method: "POST",
    body: formData,
    headers: { "X-Requested-With": "XMLHttpRequest" }
  })
  .then(res => res.json())
  .then(res => {
    if (res.status) {
      // Tutup modal
      bootstrap.Modal.getInstance(document.getElementById("modalEdit")).hide();

      // ✅ reload halaman agar flashdata tampil
      location.reload();
    } else {
      alert(res.message || "Update gagal.");
      console.error("Errors:", res.errors);
    }
  })
  .catch(err => {
    console.error("Fetch error:", err);
    alert("Terjadi kesalahan saat update data.");
  });
});

</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Event delegation untuk tombol hapus
    document.addEventListener("click", function(e) {

        let btn = e.target.closest(".btn-hapus");
        if (!btn) return; // kalau yang diklik bukan tombol hapus → stop

        let id = btn.dataset.id;
        let nama = btn.dataset.nama;

        // Isi modal hapus
        document.getElementById("hapusId").value = id;
        document.getElementById("hapusNama").textContent = nama;

        // Tampilkan modal hapus
        new bootstrap.Modal(document.getElementById("modalHapus")).show();
    });

    // Tombol konfirmasi hapus
    document.getElementById("btnKonfirmasiHapus").addEventListener("click", function () {

        let hapusId = document.getElementById("hapusId").value;

        fetch("<?= base_url('kelola_mitra/hapusAjax') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: "id_mitra=" + hapusId
        })
        .then(res => res.json())
        .then(response => {
            if (response.status === "success") {
                location.reload();
            } else {
                alert(response.message);
            }
        })
        .catch(() => {
            alert("Terjadi kesalahan pada server.");
        });

    });

});
</script>



<script>
$("#formImportExcel").on("submit", function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: "<?= base_url('kelola_mitra/importExcel') ?>",
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
                    var modal = bootstrap.Modal.getInstance(document.getElementById("modalImport"));
                    if (modal) modal.hide();

                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    setTimeout(() => location.reload(), 1600);
                } else {
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
