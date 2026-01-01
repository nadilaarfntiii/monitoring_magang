<?= $this->extend('layouts/mitra') ?>

<?= $this->section('title') ?>
Daftar Mahasiswa Magang
<?= $this->endSection() ?>

<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">


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

/* ✅ Responsif */
@media (max-width: 768px) {
  #modalDetail .modal-body {
    padding: 1.25rem;
  }
}

.table-responsive {
  overflow: visible !important;
}

.dropdown-menu {
  z-index: 2000 !important;
  position: absolute !important;
  border-radius: 0.6rem;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.dropdown-toggle::after {
  margin-left: 0.4rem;
}

/* Lebarkan modal */
.custom-modal-width {
  max-width: 1000px; /* Lebar maksimum */
  width: 95%;        /* Supaya fleksibel di layar kecil */
}

/* Responsive padding */
@media (max-width: 768px) {
  .custom-modal-width {
    width: 100%;
    margin: 0 10px;
  }
  #modalDetail .modal-body {
    padding: 1.5rem;
  }
}

</style>

<!-- Page Heading & Breadcrumb -->
<div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Data Mahasiswa</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('mitra/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Data Mahasiswa</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0 fw-bold">Data Mahasiswa</h5>
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
            <table class="table align-middle text-center" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Dosen Pembimbing</th>
                        <th>Program Magang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mahasiswa)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted fw-bold">
                                Belum ada mahasiswa yang terdaftar.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($mahasiswa as $m): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <div class="btn-group">
                                    <button type="button" 
                                            class="btn btn-primary dropdown-toggle" 
                                            data-bs-toggle="dropdown" 
                                            data-bs-display="static"
                                            aria-expanded="false">
                                        <?= esc($m['nim']) ?>
                                    </button>
                                    <ul class="dropdown-menu">
                                      <li><a class="dropdown-item" href="<?= base_url('mitra/detailPresensi/' . $m['nim']) ?>">Presensi</a></li>
                                      <li><a class="dropdown-item" href="<?= base_url('mitra/detail_learning_plan/' . $m['nim']) ?>">Learning Plan</a></li>
                                      <li><a class="dropdown-item" href="<?= base_url('mitra/logbook/' . $m['nim']) ?>">Logbook</a></li>
                                      <li><a class="dropdown-item" href="<?= base_url('input_nilai_magang/' . $m['nim']) ?>">Penilaian</a></li>
                                    </ul>
                                    </div>
                                  </td>
                                <td style="text-align: justify;"><?= esc($m['nama_lengkap']) ?></td>
                                <td><?= esc($m['nama_dosen']) ?></td>
                                <td><?= esc($m['nama_program']) ?></td>
                                <td>
                                    <a href="javascript:void(0)" 
                                       class="btn btn-info btn-sm btn-detail"
                                       data-id="<?= $m['id_profil'] ?>">
                                       <i class="bi bi-eye"></i>
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

<!-- Modal Detail -->

<div class="modal fade" id="modalDetail" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg border-0 rounded-3">
  <!-- HEADER -->
  <div class="modal-header bg-primary text-white fw-bold rounded-top-3">
    <h5 class="modal-title d-flex align-items-center gap-2 text-white fw-bold">
      Detail Mahasiswa Magang
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
  </div>

  <!-- BODY -->
  <div class="modal-body px-5 py-4"> <!-- ✅ Tambah padding kanan & kiri -->
    <div id="detail-loading" class="text-center py-4">
      <div class="spinner-border text-primary"></div>
      <p class="mt-2">Memuat data...</p>
    </div>

    <div id="detail-content" style="display:none;">

      <!-- INFORMASI MAHASISWA -->
      <div class="mb-4 pb-3 border-bottom">
        <h6 class="fw-bold text-primary mb-3">
          <i class="bi bi-person-lines-fill me-2"></i> Informasi Mahasiswa
        </h6>
        <div class="row">
          <div class="col-md-6">
            <dl class="row mb-0">
              <dt class="col-sm-5">NIM</dt>
              <dd class="col-sm-7" id="detail-nim"></dd>

              <dt class="col-sm-5">Nama Lengkap</dt>
              <dd class="col-sm-7" id="detail-nama"></dd>
            </dl>
          </div>
          <div class="col-md-6">
            <dl class="row mb-0">
              <dt class="col-sm-5">No. Handphone</dt>
              <dd class="col-sm-7" id="detail-hp"></dd>

              <dt class="col-sm-5">Email</dt>
              <dd class="col-sm-7" id="detail-email"></dd>
            </dl>
          </div>
        </div>
      </div>

      <!-- INFORMASI DOSEN PEMBIMBING -->
      <div class="mb-4 pb-3 border-bottom">
        <h6 class="fw-bold text-primary mb-3">
          <i class="bi bi-person-workspace me-2"></i> Dosen Pembimbing
        </h6>
        <div class="row">
          <div class="col-md-6">
            <dl class="row mb-0">
              <dt class="col-sm-5">NPPY</dt>
              <dd class="col-sm-7" id="detail-nppy"></dd>

              <dt class="col-sm-5">Nama Lengkap</dt>
              <dd class="col-sm-7" id="detail-dosen"></dd>
            </dl>
          </div>
          <div class="col-md-6">
            <dl class="row mb-0">
              <dt class="col-sm-5">No. HP</dt>
              <dd class="col-sm-7" id="detail-dosen-hp"></dd>

              <dt class="col-sm-5">Email</dt>
              <dd class="col-sm-7" id="detail-dosen-email"></dd>
            </dl>
          </div>
        </div>
      </div>

      <!-- PERIODE MAGANG -->
      <div>
        <h6 class="fw-bold text-primary mb-3">
          <i class="bi bi-calendar-event me-2"></i> Periode Magang
        </h6>
        <div class="row">
          <div class="col-md-6">
            <dl class="row mb-0">
              <dt class="col-sm-5">Program</dt>
              <dd class="col-sm-7" id="detail-program"></dd>

              <dt class="col-sm-5">Tanggal Mulai</dt>
              <dd class="col-sm-7" id="detail-mulai"></dd>

              <dt class="col-sm-5">Tanggal Selesai</dt>
              <dd class="col-sm-7" id="detail-selesai"></dd>
            </dl>
          </div>
          <div class="col-md-6">
            <dl class="row mb-0">
              <dt class="col-sm-5">Semester</dt>
              <dd class="col-sm-7" id="detail-semester"></dd>

              <dt class="col-sm-5">Tahun Ajaran</dt>
              <dd class="col-sm-7" id="detail-tahun"></dd>
            </dl>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- FOOTER -->
  <div class="modal-footer border-top-0 px-5">
    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
      <i class="bi bi-x-circle me-1"></i> Tutup
    </button>
  </div>
</div>

  </div>
</div>


 
<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>

<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/static/js/pages/datatables.js"></script>

<script>
$(document).ready(function() {

  // ✅ Inisialisasi DataTable hanya sekali
  if (!$.fn.DataTable.isDataTable('#table1')) {
    $('#table1').DataTable({
      drawCallback: function() {
        // Aktifkan dropdown Bootstrap setiap kali tabel di-render ulang
        $('.dropdown-toggle').dropdown();
      }
    });
  }

  // ✅ Event klik tombol detail
  $(document).on('click', '.btn-detail', function() {
    const id = $(this).data('id');
    const modal = new bootstrap.Modal(document.getElementById('modalDetail'));
    modal.show();

    // Tampilkan loading
    $('#detail-loading').show();
    $('#detail-content').hide();

    fetch(`<?= base_url('mitra/mahasiswa') ?>/${id}`)
      .then(res => res.json())
      .then(res => {
        if (res.status === 'success') {
          const d = res.data;

          // Isi data mahasiswa
          $('#detail-nim').text(d.nim);
          $('#detail-nama').text(d.nama_lengkap);
          $('#detail-email').text(d.email_mahasiswa ?? '-');
          $('#detail-hp').text(d.handphone ?? '-');

          // Isi data dosen
          $('#detail-nppy').text(d.nppy ?? '-');
          $('#detail-dosen').text(d.nama_dosen ?? '-');
          $('#detail-dosen-hp').text(d.no_hp_dosen ?? '-');
          $('#detail-dosen-email').text(d.email_dosen ?? '-');

          // Isi data magang
          $('#detail-program').text(d.nama_program ?? '-');
          $('#detail-mulai').text(formatTanggal(d.tanggal_mulai));
          $('#detail-selesai').text(formatTanggal(d.tanggal_selesai));
          $('#detail-semester').text(d.semester ?? '-');
          $('#detail-tahun').text(d.tahun_ajaran ?? '-');

          // Sembunyikan loading, tampilkan konten
          $('#detail-loading').hide();
          $('#detail-content').show();
        } else {
          $('#detail-loading').html(`<div class="alert alert-danger">${res.message}</div>`);
        }
      })
      .catch(() => {
        $('#detail-loading').html(`<div class="alert alert-danger">Terjadi kesalahan koneksi.</div>`);
      });

    // Fungsi format tanggal
    function formatTanggal(tanggal) {
      if (!tanggal) return '-';
      const tgl = new Date(tanggal);
      const dd = String(tgl.getDate()).padStart(2, '0');
      const mm = String(tgl.getMonth() + 1).padStart(2, '0');
      const yyyy = tgl.getFullYear();
      return `${dd}/${mm}/${yyyy}`;
    }
  });

});
</script>


<?= $this->endSection() ?>
