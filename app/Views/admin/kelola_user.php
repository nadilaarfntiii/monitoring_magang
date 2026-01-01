<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>
Kelola Pengguna
<?= $this->endSection() ?>

<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery UI CSS -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">


<?= $this->section('content') ?>

            <!-- Page Heading & Breadcrumb -->
            <div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Kelola Pengguna</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kelola Pengguna</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>


<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Daftar Pengguna</h5>
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
                  <i class="bi bi-person-plus"></i> Tambah Pengguna
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
                  <th>Username</th>
                  <th>Nama Lengkap</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Aksi</th>
              </tr>
          </thead>
          <tbody id="userTableBody">
              <?php $no = 1; foreach ($user as $us): ?>
                  <tr>
                      <td><?= $no++ ?></td>
                      <td><?= esc($us['username']) ?></td>
                      <td><?= esc($us['nama_lengkap']) ?></td>
                      <td><?= ucfirst(esc($us['role'])) ?></td>
                      <td>
                          <?php if ($us['status'] === 'aktif'): ?>
                              <span class="badge bg-success">Aktif</span>
                          <?php else: ?>
                              <span class="badge bg-danger">Tidak Aktif</span>
                          <?php endif; ?>
                      </td>
                      <td>
                          <a href="javascript:void(0)" 
                              class="btn btn-warning btn-sm btn-edit" 
                              data-id="<?= $us['id_user'] ?>">
                              <i class="bi bi-pencil-square"></i>
                          </a>
                          <a href="javascript:void(0)" 
                            class="btn btn-danger btn-sm btn-hapus" 
                            data-id="<?= $us['id_user'] ?>" 
                            data-nama="<?= $us['nama_lengkap'] ?>">
                            <i class="bi bi-trash"></i>
                          </a>
                      </td>
                  </tr>
              <?php endforeach; ?>
          </tbody>
      </table>
  </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- lebih lebar & di tengah -->
    <div class="modal-content">
      <form id="formTambahUser">
      <div class="modal-header bg-primary text-white fw-bold">
          <h5 class="modal-title text-white fw-bold">Tambah Pengguna</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
        <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
          <div class="mb-2">
            <label>Role</label>
            <select name="role" id="roleSelect" class="form-control" required>
              <option value="">Pilih Role Pengguna</option>
              <option value="mahasiswa">Mahasiswa</option>
              <option value="dospem">Dosen</option>
              <option value="mitra">Perusahaan Mitra</option> 
              <option value="kaprodi">Kepala Program Studi</option>
              <option value="admin">Admin</option>
            </select>
          </div>

          <!-- Mahasiswa -->
          <div class="mb-2 mahasiswa-field d-none">
            <label>Pilih Mahasiswa</label>
            <input type="text" id="mahasiswaInput" class="form-control" placeholder="-- Pilih Mahasiswa --">
            <input type="hidden" name="nim" id="nimValue">
          </div>

          <!-- Dosen -->
          <div class="mb-2 dosen-field d-none">
            <label>Pilih Dosen</label>
            <input type="text" id="dosenInput" class="form-control" placeholder="-- Pilih Dosen --">
            <input type="hidden" name="nppy_dosen" id="dosenNppyValue">
          </div>

          <!-- Kaprodi -->
          <div class="mb-2 kaprodi-field d-none">
            <label>Pilih Kaprodi</label>
            <input type="text" id="kaprodiInput" class="form-control" placeholder="-- Pilih Kaprodi --">
            <input type="hidden" name="nppy_kaprodi" id="kaprodiNppyValue">
          </div>

          <!-- Unit / Mitra -->
          <div class="mb-2 unit-field d-none">
            <label>Pilih Unit Perusahaan</label>
            <input type="text" id="unitInput" class="form-control" placeholder="-- Pilih Unit --">
            <input type="hidden" name="id_unit" id="idUnitValue">
          </div>

          <!-- Manual username & password -->
          <div class="mb-2 manual-field d-none">
            <label>Username</label>
            <input type="text" name="username" class="form-control">
          </div>
          <div class="mb-2 manual-field d-none">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
          </div>

          <div class="mb-2">
            <label>Status</label>
            <select name="status" class="form-control" required>
              <option value="aktif">Aktif</option>
              <option value="tidak aktif">Tidak Aktif</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary fw-bold">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Tambah -->

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form id="formEditUser">
        <div class="modal-header bg-primary text-white fw-bold">
          <h5 class="modal-title text-white fw-bold">Edit Pengguna</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="max-height:70vh; overflow-y:auto;">

          <input type="hidden" name="id_user" id="editIdUser">

          <div class="mb-2">
            <label>Nama Lengkap</label>
            <input type="text" class="form-control" id="editNamaLengkap" readonly>
          </div>

          <div class="mb-2">
            <label>Role</label>
            <input type="text" class="form-control" id="editRole" readonly>
          </div>

          <div class="mb-2">
            <label>Username</label>
            <input type="text" name="username" class="form-control" id="editUsername" required>
          </div>

          <div class="mb-2">
            <label>Password</label>
            <input type="password" name="password" class="form-control" id="editPassword" placeholder="Kosongkan jika tidak ingin diubah">
          </div>

          <div class="mb-2">
            <label>Status</label>
            <select name="status" class="form-control" id="editStatus" required>
              <option value="aktif">Aktif</option>
              <option value="tidak aktif">Tidak Aktif</option>
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- End Modal Edit -->

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white fw-bold">
        <h5 class="modal-title text-white fw-bold">Konfirmasi Hapus Pengguna</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus pengguna <strong id="hapusNama"></strong>? Data pengguna akan diarsipkan dan dapat dipulihkan kembali.</p>
        <!-- tambahin hidden input buat ID -->
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
          <h5 class="modal-title text-white fw-bold">Import Data Pengguna</h5>
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
$(function(){

  // Toggle input field
  $('#roleSelect').on('change', function(){
    let role = $(this).val();
    $('.mahasiswa-field, .dosen-field, .kaprodi-field, .unit-field, .manual-field').addClass('d-none');

    if(role=='mahasiswa') $('.mahasiswa-field').removeClass('d-none');
    else if(role=='dospem') $('.dosen-field').removeClass('d-none');
    else if(role=='kaprodi') $('.kaprodi-field').removeClass('d-none');
    else if(role=='mitra') $('.unit-field').removeClass('d-none');
    else if(role=='admin') $('.manual-field').removeClass('d-none');

    // Reset semua input ketika role berubah
    $('#mahasiswaInput,#dosenInput,#kaprodiInput,#unitInput').val('');
    $('#nimValue,#dosenNppyValue,#kaprodiNppyValue,#idUnitValue').val('');
  });

  // AJAX submit
  $('#formTambahUser').on('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);

    fetch("<?= base_url('kelola_user/simpanAjax') ?>", {
        method:'POST',
        body:formData
    })
    .then(res=>res.json())
    .then(data=>{
        // Hapus alert error lama di modal
        $('#modalTambah .modal-body .alert').remove();

        if(data.status === 'success'){
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
    .catch(err=>{
        console.error(err);
        $('#modalTambah .modal-body').prepend(`
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> Terjadi kesalahan server
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        `);
    });
  });

  // Fungsi autocomplete
function setupAutocomplete(input, hidden, url){
  $(input).autocomplete({
    source: function(req, res){
      if(!req.term) return res([]);
      $.getJSON(url, {q: req.term})
        .done(function(data){
          // Batasi hanya 4 item awal
          res(data.slice(0, 4));
        })
        .fail(function(xhr, status, error){
          console.error("Gagal ambil data:", error);
          res([]);
        });
    },
    minLength: 1,
    select: function(e, ui){
      $(input).val(ui.item.label);   // tampilkan label
      $(hidden).val(ui.item.id);     // simpan ID
    },
    open: function(){
      var $input = $(this);
      $(".ui-autocomplete").css({
        "z-index": 3000,
        "background": "#fff",
        "border": "1px solid #ced4da",
        "border-radius": "0.375rem",
        "box-shadow": "0 4px 8px rgba(0,0,0,0.1)",
        "max-height": "200px",          // maksimal tinggi dropdown
        "overflow-y": "auto",           // scroll kalau lebih
        "width": $input.outerWidth()+"px",
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

  // Validasi kalau user tidak pilih suggestion
  $(input).on("blur", function(){
    if(!$(hidden).val()){   
      $(this).val('');
    }
  });
}

// Inisialisasi autocomplete
setupAutocomplete("#mahasiswaInput","#nimValue","<?= base_url('kelola_user/cariMahasiswa') ?>");
setupAutocomplete("#dosenInput","#dosenNppyValue","<?= base_url('kelola_user/cariDosen') ?>");
setupAutocomplete("#kaprodiInput","#kaprodiNppyValue","<?= base_url('kelola_user/cariKaprodi') ?>");
setupAutocomplete("#unitInput","#idUnitValue","<?= base_url('kelola_user/cariUnit') ?>");

  
});
</script>

<!-- Script Edit -->

<script>
$(document).ready(function(){

// ==============================
// Tombol Edit User
// ==============================
$('#table1').on('click', '.btn-edit', function(){
    const tr = $(this).closest('tr');
    const id = $(this).data('id');
    const username = tr.find('td:eq(1)').text().trim();
    const nama = tr.find('td:eq(2)').text().trim();
    const role = tr.find('td:eq(3)').text().trim();
    const statusText = tr.find('td:eq(4) span').text().trim().toLowerCase(); // aktif / tidak aktif

    // Isi modal
    $('#editIdUser').val(id);
    $('#editUsername').val(username);
    $('#editNamaLengkap').val(nama);
    $('#editRole').val(role);
    $('#editStatus').val(statusText);

    // Kosongkan password
    $('#editPassword').val('');

    // Hapus alert lama
    $('#modalEdit .modal-body .alert').remove();

    // Tampilkan modal
    $('#modalEdit').modal('show');
});

// ==============================
// Submit Edit User via AJAX
// ==============================
$('#formEditUser').on('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);

    // Hapus alert lama
    $('#modalEdit .modal-body .alert').remove();

    fetch("<?= base_url('kelola_user/updateAjax') ?>", {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success'){
            location.reload(); // reload biar flashdata muncul
        } else {
            let msg = data.message;
            if(typeof msg === 'object') msg = Object.values(msg).join("<br>");
            $('#modalEdit .modal-body').prepend(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
        }
    })
    .catch(err=>{
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

<!-- Script Hapus -->
<script>
$(document).on("click", ".btn-hapus", function () {
    var id = $(this).data("id");
    var nama = $(this).data("nama");

    $("#hapusId").val(id);
    $("#hapusNama").text(nama);
    $("#modalHapus").modal("show");
});

$("#btnKonfirmasiHapus").on("click", function () {
    var hapusId = $("#hapusId").val();

    $.ajax({
        url: "<?= base_url('kelola_user/hapusAjax') ?>",
        type: "POST",
        data: {id_user: hapusId},
        success: function (response) {
            if (response.status === "success") {
                // langsung reload agar flashdata success tampil
                location.reload();
            } else {
                // kalau gagal, tetap munculkan error (optional)
                alert(response.message);
            }
        },
        error: function () {
            alert("Terjadi kesalahan pada server.");
        }
    });
});
</script>

<script>
$("#formImportExcel").on("submit", function (e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        url: "<?= base_url('user/importExcel') ?>",
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
