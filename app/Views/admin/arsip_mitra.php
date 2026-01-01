<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>
Arsip Mitra
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
                            <h3>Arsip Mitra</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Arsip Mitra</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Arsip Mitra</h5>
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
                    <?php $no=1; foreach($mitra as $m): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($m['id_mitra']) ?></td>
                            <td><?= esc($m['nama_mitra']) ?></td>
                            <td><?= esc($m['bidang_usaha']) ?></td>
                            <td><?= esc($m['kota']) ?></td>
                            <td><span class="badge bg-danger">Tidak Aktif</span></td>
                            <td>
                                <button class="btn btn-info btn-sm btn-detail" data-id="<?= $m['id_mitra'] ?>">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-primary btn-sm btn-restore"
                                    data-id="<?= $m['id_mitra'] ?>"
                                    data-nama="<?= $m['nama_mitra'] ?>">
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
    <div class="modal-content">
      <div class="modal-header bg-primary text-white fw-bold">
        <h5 class="modal-title text-white fw-bold">Detail Mitra</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <img id="detailFotoMitra" src="<?= base_url('assets/images/default.jpg') ?>" 
               class="w-100" style="height:250px;object-fit:cover;border:1px solid #0d6efd;">
        </div>
        <h4 id="detailNamaMitra" class="fw-bold text-center mb-1"></h4>
        <p class="text-muted fw-semibold text-center mb-3">
          Detail Mitra - <span id="detailNamaMitraText"></span>
        </p>
        <div class="table-responsive">
          <table class="table table-bordered">
            <tr><th>ID Mitra</th><td id="detailIdMitra"></td></tr>
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
    </div>
  </div>
</div>

<!-- Modal Restore -->
<div class="modal fade" id="modalRestore" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white fw-bold">
        <h5 class="modal-title text-white fw-bold">Konfirmasi Restore Mitra</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin merestore mitra <strong id="restoreNama"></strong>?</p>
        <input type="hidden" id="restoreId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary fw-bold" id="btnKonfirmasiRestore">Restore</button>
      </div>
    </div>
  </div>
</div>

<!-- === Sidebar + Scripts (sama seperti arsip pengguna) === -->
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

    // Detail mitra
    $(document).on("click", ".btn-detail", function(){
        let id = $(this).data("id");
        fetch("<?= base_url('kelola_mitra/detail') ?>/" + id)
            .then(res => res.json())
            .then(res => {
                if(res.status){
                    let d = res.data;
                    $("#detailIdMitra").text(d.id_mitra);
                    $("#detailNamaMitra").text(d.nama_mitra);
                    $("#detailNamaMitraText").text(d.nama_mitra);
                    $("#detailNamaMitraTable").text(d.nama_mitra);
                    $("#detailBidangUsaha").text(d.bidang_usaha ?? "-");
                    $("#detailAlamat").text(d.alamat ?? "-");
                    $("#detailKota").text(d.kota ?? "-");
                    $("#detailKodePos").text(d.kode_pos ?? "-");
                    $("#detailProvinsi").text(d.provinsi ?? "-");
                    $("#detailNegara").text(d.negara ?? "-");
                    $("#detailNoTelp").text(d.no_telp ?? "-");
                    $("#detailEmail").text(d.email ?? "-");
                    $("#detailStatus").html('<span class="badge bg-danger">Tidak Aktif</span>');
                    $("#modalDetail").modal("show");
                }
            })
            .catch(() => alert("Terjadi kesalahan server."));
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
            url: "<?= base_url('kelola_mitra/restoreAjax') ?>",
            type: "POST",
            dataType: "json",
            data: {id_mitra: id},
            success: function(response){
                if(response.status === "success"){
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(){
                alert("Terjadi kesalahan server.");
            }
        });
    });

});
</script>

<?= $this->endSection() ?>
