<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>
Arsip Unit
<?= $this->endSection() ?>

<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<?= $this->section('content') ?>

<!-- Page Heading & Breadcrumb -->
<div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Arsip Unit</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Arsip Unit</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>


<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Arsip Unit</h5>
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
                    <?php $no=1; foreach($unit as $u): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($u['nama_unit']) ?></td>
                            <td><?= esc($u['nama_mitra'] ?? '-') ?></td>
                            <td><?= esc($u['nama_pembimbing']) ?></td>
                            <td><?= esc($u['jabatan']) ?></td>
                            <td><span class="badge bg-danger">Tidak Aktif</span></td>
                            <td>
                                <a href="javascript:void(0)" 
                                    class="btn btn-info btn-sm btn-detail" 
                                    data-id="<?= $u['id_unit'] ?>">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-primary btn-sm btn-restore"
                                    data-id="<?= $u['id_unit'] ?>"
                                    data-nama="<?= $u['nama_unit'] ?>">
                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
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


<!-- Modal Restore -->
<div class="modal fade" id="modalRestore" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white fw-bold">
        <h5 class="modal-title text-white fw-bold">Konfirmasi Restore Unit</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin merestore unit <strong id="restoreNama"></strong>?</p>
        <input type="hidden" id="restoreId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary fw-bold" id="btnKonfirmasiRestore">Restore</button>
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
$(document).ready(function(){

$('#table1').DataTable();

// Modal detail
$(document).on("click", ".btn-detail", function(){
    let id = $(this).data("id");

    fetch("<?= base_url('kelola_unit/detail') ?>/" + id)
    .then(res => res.json())
    .then(res => {
        if(res.status){
            let d = res.data;
            $("#detailIdUnit").text(d.id_unit);
            $("#detailNamaUnit").text(d.nama_unit);
            $("#detailMitra").text(d.nama_mitra ?? "-");
            $("#detailNamaPembimbing").text(d.nama_pembimbing ?? "-");
            $("#detailJabatan").text(d.jabatan ?? "-");
            $("#detailNoHp").text(d.no_hp ?? "-");
            $("#detailEmail").text(d.email ?? "-");

            var modal = new bootstrap.Modal(document.getElementById('modalDetail'));
            modal.show();
        } else {
            alert(res.message);
        }
    })
    .catch(() => alert("Gagal mengambil detail unit"));
});



    // Tombol restore
    $(document).on("click", ".btn-restore", function(){
        $("#restoreId").val($(this).data("id"));
        $("#restoreNama").text($(this).data("nama"));
        $("#modalRestore").modal("show");
    });

    // Konfirmasi restore
    $("#btnKonfirmasiRestore").on("click", function(){
        var id = $("#restoreId").val();
        $.ajax({
            url: "<?= base_url('kelola_unit/restoreAjax') ?>",
            type: "POST",
            dataType: "json",
            data: {id_unit: id},
            success: function(response){
                console.log("Response dari server:", response); // <---- Tambahkan log ke console

                if(response.status === "success"){
                    location.reload();
                } else if(response.status === "warning") {
                    // tampilkan pesan warning dari server
                    alert(response.message);
                    $("#modalRestore").modal("hide");
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr){
                console.log("Error response:", xhr.responseText); // <---- Tambahkan log error ke console
                alert("Terjadi kesalahan server.");
            }
        });
    });



});
</script>

<?= $this->endSection() ?>
