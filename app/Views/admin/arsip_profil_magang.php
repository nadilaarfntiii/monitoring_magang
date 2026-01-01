<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>
Arsip Profil Magang
<?= $this->endSection() ?>

<!-- ✅ CSS (disamakan dengan Arsip Mitra) -->
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
                            <h3>Arsip Profil Magang</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Arsip Profil Magang</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Arsip Profil Magang</h5>
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
                    <?php $no=1; foreach($profil as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($p['nim']) ?></td>
                            <td><?= esc(mb_strimwidth($p['nama_lengkap'] ?? '-', 0, 25, '...')) ?></td>
                            <td><?= esc(mb_strimwidth($p['nama_dosen'] ?? '-', 0, 25, '...')) ?></td>
                            <td><?= esc(mb_strimwidth($p['nama_mitra'] ?? '-', 0, 25, '...')) ?></td>
                            <td><?= esc(mb_strimwidth($p['nama_program'] ?? '-', 0, 25, '...')) ?></td>
                            <td><span class="badge bg-danger">Tidak Aktif</span></td>
                            <td>
                                <button class="btn btn-info btn-sm btn-detail" 
                                    data-id="<?= $p['id_profil'] ?>">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-primary btn-sm btn-restore"
                                    data-id="<?= $p['id_profil'] ?>"
                                    data-nama="<?= $p['nama_lengkap'] ?>">
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

<!-- Modal Restore -->
<div class="modal fade" id="modalRestore" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white fw-bold">
        <h5 class="modal-title text-white fw-bold">Konfirmasi Restore Profil Magang</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin merestore profil magang <strong id="restoreNama"></strong>?</p>
        <input type="hidden" id="restoreId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary fw-bold" id="btnKonfirmasiRestore">Restore</button>
      </div>
    </div>
  </div>
</div>

<!-- === Sidebar + Scripts (sama seperti Arsip Mitra) === -->
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<!-- ⚠️ app.js tidak dimuat otomatis karena halaman arsip_profil_magang -->
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="assets/static/js/pages/datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>


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
$(document).ready(function(){

    $('#table1').DataTable();

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
            url: "<?= base_url('kelola_profil_magang/restoreAjax') ?>",
            type: "POST",
            dataType: "json",
            data: {id_profil: id},
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
