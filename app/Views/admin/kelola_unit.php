<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>
Kelola Unit
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
                            <h3>Kelola Unit</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kelola Unit</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Daftar Unit</h5>
        <a href="javascript:void(0)" 
            class="btn btn-primary btn-sm fw-bold d-flex align-items-center justify-content-center gap-2 rounded btn-tambah"
            data-bs-toggle="modal" 
            data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Unit
        </a>
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
                    <th>Nama Unit</th>
                    <th>Mitra</th>
                    <th>Nama Pembimbing</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($unit)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted fw-bold">
                        Belum ada unit yang terdaftar.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($unit as $u): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($u['nama_unit']) ?></td>
                        <td><?= esc($u['nama_mitra'] ?? '-') ?></td>
                        <td><?= esc($u['nama_pembimbing']) ?></td>
                        <td><?= esc($u['jabatan']) ?></td>
                        <td>
                            <?php if ($u['status_unit'] === 'Aktif'): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- Tombol Detail -->
                            <a href="javascript:void(0)" 
                                class="btn btn-info btn-sm btn-detail" 
                                data-id="<?= $u['id_unit'] ?>">
                                <i class="bi bi-eye"></i>
                            </a>
                            <!-- Tombol Edit -->
                            <a href="javascript:void(0)" 
                                class="btn btn-warning btn-sm btn-edit" 
                                data-id="<?= $u['id_unit'] ?>">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <!-- Tombol Hapus -->
                            <a href="javascript:void(0)" 
                                class="btn btn-danger btn-sm btn-hapus" 
                                data-id="<?= $u['id_unit'] ?>" 
                                data-nama="<?= $u['nama_unit'] ?>">
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
<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg border-0 rounded-3">
      <form id="formTambahUnit">
        <!-- HEADER -->
        <div class="modal-header bg-primary text-white fw-bold rounded-top-3">
          <h5 class="modal-title text-white fw-bold">Tambah Unit</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- BODY -->
        <div class="modal-body p-4" style="max-height:70vh; overflow-y:auto;">
          <div class="row g-4">

            <!-- Nama Unit -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-diagram-3"></i>
                <span>Nama Unit</span>
              </label>
              <input type="text" name="nama_unit" class="form-control rounded-3 shadow-sm" required>
            </div>

            <!-- Mitra -->
            <div class="col-md-6">
            <label class="form-label label-with-icon">
                <i class="bi bi-building"></i>
                <span>Mitra</span>
            </label>
            <select name="id_mitra" class="form-select rounded-3 shadow-sm" required>
                <option value="" disabled selected>Pilih Mitra</option>
                <?php foreach($mitra as $m): ?>
                <option value="<?= $m['id_mitra'] ?>"><?= $m['nama_mitra'] ?></option>
                <?php endforeach; ?>
            </select>
            </div>

            <!-- Nama Pembimbing -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-person"></i>
                <span>Nama Pembimbing</span>
              </label>
              <input type="text" name="nama_pembimbing" class="form-control rounded-3 shadow-sm">
            </div>

            <!-- Jabatan -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-briefcase"></i>
                <span>Jabatan</span>
              </label>
              <input type="text" name="jabatan" class="form-control rounded-3 shadow-sm">
            </div>

            <!-- No HP -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-telephone"></i>
                <span>No. HP Pembimbing</span>
              </label>
              <input type="text" name="no_hp" class="form-control rounded-3 shadow-sm">
            </div>

            <!-- Email -->
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-envelope"></i>
                <span>Email Pembimbing</span>
              </label>
              <input type="email" name="email" class="form-control rounded-3 shadow-sm">
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


<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg border-0 rounded-3">
      <form id="formEditUnit">
        <!-- HEADER sama seperti tambah -->
        <div class="modal-header bg-primary text-white fw-bold rounded-top-3">
          <h5 class="modal-title text-white fw-bold">Edit Unit</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- BODY sama style -->
        <div class="modal-body p-4" style="max-height:70vh; overflow-y:auto;">
          <input type="hidden" name="id_unit" id="editIdUnit">
          <div class="row g-4">
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-diagram-3"></i>
                <span>Nama Unit</span>
              </label>
              <input type="text" name="nama_unit" id="editNamaUnit" class="form-control rounded-3 shadow-sm" required>
            </div>
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-building"></i>
                <span>Mitra</span>
              </label>
              <select name="id_mitra" id="editIdMitra" class="form-select rounded-3 shadow-sm">
                <?php foreach($mitra as $m): ?>
                  <option value="<?= $m['id_mitra'] ?>"><?= $m['nama_mitra'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-person"></i>
                <span>Nama Pembimbing</span>
              </label>
              <input type="text" name="nama_pembimbing" id="editNamaPembimbing" class="form-control rounded-3 shadow-sm">
            </div>
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-briefcase"></i>
                <span>Jabatan</span>
              </label>
              <input type="text" name="jabatan" id="editJabatan" class="form-control rounded-3 shadow-sm">
            </div>
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-telephone"></i>
                <span>No. HP</span>
              </label>
              <input type="text" name="no_hp" id="editNoHp" class="form-control rounded-3 shadow-sm">
            </div>
            <div class="col-md-6">
              <label class="form-label label-with-icon">
                <i class="bi bi-envelope"></i>
                <span>Email</span>
              </label>
              <input type="email" name="email" id="editEmail" class="form-control rounded-3 shadow-sm">
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


<!-- Modal Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white fw-bold">
        <h5 class="modal-title text-white fw-bold">Konfirmasi Hapus Unit</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus unit <strong id="hapusNama"></strong>?</p>
        <input type="hidden" id="hapusId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary fw-bold" id="btnKonfirmasiHapus">Hapus</button>
      </div>
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
          Detail Unit
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <!-- BODY -->
      <div class="modal-body p-4">
        
        <!-- Info Unit -->
        <div class="mb-4 pb-3 border-bottom">
          <h6 class="fw-bold text-primary mb-3">
            <i class="bi bi-diagram-3 me-2"></i> Informasi Unit
          </h6>
          <dl class="row mb-0">
            <dt class="col-sm-4">ID Unit</dt>
            <dd class="col-sm-8" id="detailIdUnit"></dd>

            <dt class="col-sm-4">Nama Unit</dt>
            <dd class="col-sm-8" id="detailNamaUnit"></dd>
          </dl>
        </div>

        <!-- Info Mitra -->
        <div class="mb-4 pb-3 border-bottom">
          <h6 class="fw-bold text-primary mb-3">
            <i class="bi bi-building me-2"></i> Informasi Mitra
          </h6>
          <dl class="row mb-0">
            <dt class="col-sm-4">Nama Mitra</dt>
            <dd class="col-sm-8" id="detailMitra"></dd>

            <dt class="col-sm-4">Nama Pembimbing</dt>
            <dd class="col-sm-8" id="detailNamaPembimbing"></dd>

            <dt class="col-sm-4">Jabatan</dt>
            <dd class="col-sm-8" id="detailJabatan"></dd>
          </dl>
        </div>

        <!-- Kontak -->
        <div>
          <h6 class="fw-bold text-primary mb-3">
            <i class="bi bi-telephone me-2"></i> Kontak Pembimbing
          </h6>
          <dl class="row mb-0">
            <dt class="col-sm-4">No. HP</dt>
            <dd class="col-sm-8" id="detailNoHp"></dd>

            <dt class="col-sm-4">Email</dt>
            <dd class="col-sm-8" id="detailEmail"></dd>
          </dl>
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



<!-- JS -->
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script> 
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="assets/static/js/pages/datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Tambah
    document.getElementById("formTambahUnit").addEventListener("submit", function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        fetch("<?= base_url('kelola_unit/simpanAjax') ?>", {
            method: "POST", body: formData, headers: {"X-Requested-With": "XMLHttpRequest"}
        })
        .then(res => res.json())
        .then(res => { if(res.status){ location.reload(); } else { alert(res.message); }})
        .catch(() => alert("Gagal simpan data"));
    });

    // Edit ambil data
    document.addEventListener("click", function(e) {
        if (e.target.closest(".btn-edit")) {
            let id = e.target.closest(".btn-edit").getAttribute("data-id");
            fetch("<?= base_url('kelola_unit/detail/') ?>/" + id)
            .then(res => res.json())
            .then(res => {
                if(res.status){
                    let d = res.data;
                    document.getElementById("editIdUnit").value = d.id_unit;
                    document.getElementById("editNamaUnit").value = d.nama_unit;
                    document.getElementById("editIdMitra").value = d.id_mitra;
                    document.getElementById("editNamaPembimbing").value = d.nama_pembimbing ?? "";
                    document.getElementById("editJabatan").value = d.jabatan ?? "";
                    document.getElementById("editNoHp").value = d.no_hp ?? "";
                    document.getElementById("editEmail").value = d.email ?? "";
                    new bootstrap.Modal(document.getElementById("modalEdit")).show();
                }
            })
            .catch(() => alert("Gagal mengambil data unit"));
        }
    });

    // Submit edit
    document.getElementById("formEditUnit").addEventListener("submit", function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        fetch("<?= base_url('kelola_unit/updateAjax') ?>", {
            method: "POST", body: formData, headers: {"X-Requested-With": "XMLHttpRequest"}
        })
        .then(res => res.json())
        .then(res => { if(res.status){ location.reload(); } else { alert(res.message); }})
        .catch(() => alert("Gagal update data"));
    });

    // Hapus
    document.querySelectorAll(".btn-hapus").forEach(btn => {
        btn.addEventListener("click", function() {
            var id = this.getAttribute("data-id");
            var nama = this.getAttribute("data-nama");
            document.getElementById("hapusId").value = id;
            document.getElementById("hapusNama").textContent = nama;
            new bootstrap.Modal(document.getElementById("modalHapus")).show();
        });
    });
    document.getElementById("btnKonfirmasiHapus").addEventListener("click", function() {
        var hapusId = document.getElementById("hapusId").value;
        fetch("<?= base_url('kelola_unit/hapusAjax') ?>", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded","X-Requested-With": "XMLHttpRequest"},
            body: "id_unit=" + hapusId
        })
        .then(res => res.json())
        .then(res => { if(res.status){ location.reload(); } else { alert(res.message); }})
        .catch(() => alert("Gagal hapus data"));
    });
});


/* Modal detail */
document.addEventListener("click", function(e) {
    if (e.target.closest(".btn-detail")) {
        let id = e.target.closest(".btn-detail").getAttribute("data-id");
        fetch("<?= base_url('kelola_unit/detail/') ?>/" + id)
        .then(res => res.json())
        .then(res => {
            if(res.status){
                let d = res.data;
                document.getElementById("detailIdUnit").textContent = d.id_unit;
                document.getElementById("detailNamaUnit").textContent = d.nama_unit;
                document.getElementById("detailMitra").textContent = d.nama_mitra ?? "-";
                document.getElementById("detailNamaPembimbing").textContent = d.nama_pembimbing ?? "-";
                document.getElementById("detailJabatan").textContent = d.jabatan ?? "-";
                document.getElementById("detailNoHp").textContent = d.no_hp ?? "-";
                document.getElementById("detailEmail").textContent = d.email ?? "-";
                new bootstrap.Modal(document.getElementById("modalDetail")).show();
            }
        })
        .catch(() => alert("Gagal mengambil detail unit"));
    }
});

</script>

<?= $this->endSection() ?>
