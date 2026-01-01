<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>
Program Magang
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
</style>

            <!-- Page Heading & Breadcrumb -->
            <div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Kelola Program Magang</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kelola Program Magang</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Daftar Program Magang</h5>
        <a href="javascript:void(0)" 
            class="btn btn-primary btn-sm fw-bold d-flex align-items-center justify-content-center gap-2 rounded btn-tambah"
            data-bs-toggle="modal" 
            data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Program
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
                    <th>Nama Program</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($program_magang)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted fw-bold">
                        Belum ada program magang yang terdaftar.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($program_magang as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($p['nama_program']) ?></td>
                        <td>
                            <?php if ($p['status'] === 'aktif'): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- Tombol Edit -->
                            <a href="javascript:void(0)" 
                              class="btn btn-warning btn-sm btn-edit" 
                              data-id="<?= $p['id_program'] ?>">
                              <i class="bi bi-pencil-square"></i>
                            </a>
                            <!-- Tombol Hapus -->
                            <a href="javascript:void(0)" 
                                class="btn btn-danger btn-sm btn-hapus" 
                                data-id="<?= $p['id_program'] ?>" 
                                data-nama="<?= $p['nama_program'] ?>">
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
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0 rounded-3">
      <form id="formTambah">
        <div class="modal-header bg-primary text-white fw-bold rounded-top-3">
          <h5 class="modal-title text-white fw-bold">Tambah Program Magang</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label label-with-icon">
                <i class="bi bi-diagram-3"></i> Nama Program
            </label>
            <input type="text" name="nama_program" class="form-control rounded-3 shadow-sm" required>
          </div>
          <div class="mb-3">
            <label class="form-label label-with-icon">
                <i class="bi bi-toggle-on"></i> Status
            </label>
            <select name="status" class="form-select rounded-3 shadow-sm" required>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
          </div>
        </div>
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
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0 rounded-3">
      <form id="formEdit">
        <div class="modal-header bg-primary text-white fw-bold rounded-top-3">
          <h5 class="modal-title text-white fw-bold">Edit Program Magang</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <input type="hidden" name="id_program" id="editId">
          <div class="mb-3">
            <label class="form-label label-with-icon">
                <i class="bi bi-diagram-3"></i> Nama Program
            </label>
            <input type="text" name="nama_program" id="editNama" class="form-control rounded-3 shadow-sm" required>
          </div>
          <div class="mb-3">
            <label class="form-label label-with-icon">
                <i class="bi bi-toggle-on"></i> Status
            </label>
            <select name="status" id="editStatus" class="form-select rounded-3 shadow-sm" required>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
          </div>
        </div>
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
        <h5 class="modal-title text-white fw-bold">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus <strong id="hapusNama"></strong>?</p>
        <input type="hidden" id="hapusId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary fw-bold" id="btnKonfirmasiHapus">Hapus</button>
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
    document.getElementById("formTambah").addEventListener("submit", function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        fetch("<?= base_url('admin/program_magang/simpanAjax') ?>", {
            method: "POST", body: formData, headers: {"X-Requested-With": "XMLHttpRequest"}
        })
        .then(res => res.json())
        .then(res => { if(res.status){ location.reload(); } else { alert(res.message); }})
        .catch(() => alert("Gagal simpan data"));
    });

    // Edit load
        document.addEventListener("click", function(e) {
        if (e.target.closest(".btn-edit")) {
            let id = e.target.closest(".btn-edit").getAttribute("data-id");
            fetch("<?= base_url('admin/program_magang/detail/') ?>/" + id, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(res => {
                if(res.status){
                    let d = res.data;
                    document.getElementById("editId").value = d.id_program;
                    document.getElementById("editNama").value = d.nama_program;
                    document.getElementById("editStatus").value = d.status;
                    new bootstrap.Modal(document.getElementById("modalEdit")).show();
                }
            });
        }
    });


    // Submit edit
    document.getElementById("formEdit").addEventListener("submit", function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        fetch("<?= base_url('admin/program_magang/updateAjax') ?>", {
            method: "POST", 
            body: formData, 
            headers: {"X-Requested-With": "XMLHttpRequest"}
        })
        .then(res => res.json())
        .then(res => { 
            if(res.status){ 
                // Reload halaman agar flashdata muncul
                location.reload();
            } else { 
                alert(res.message); 
            }
        })
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
        fetch("<?= base_url('admin/program_magang/hapusAjax') ?>", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded","X-Requested-With": "XMLHttpRequest"},
            body: "id_program=" + hapusId
        })
        .then(res => res.json())
        .then(res => { 
            if(res.status){ 
                location.reload(); // Flashdata akan muncul setelah reload
            } else { 
                alert(res.message); 
            }
        })
        .catch(() => alert("Gagal hapus data"));
    });

});
</script>

<?= $this->endSection() ?>
