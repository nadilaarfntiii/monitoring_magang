<?= $this->extend('layouts/mhs') ?>
<?= $this->section('title') ?>Presensi Mahasiswa<?= $this->endSection() ?>

<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<?= $this->section('content') ?>

<style>
    .btn i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;       /* ukuran ikon seimbang dengan teks */
    line-height: 1;        /* mencegah ikon tampak lebih tinggi */
    vertical-align: middle; /* pastikan posisi vertikal rata */
    margin-top: -1px;       /* kecilkan offset agar benar-benar sejajar */
}

</style>

            <!-- Page Heading & Breadcrumb -->
            <div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Presensi</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('mahasiswa/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Presensi</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">

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

    <!-- Informasi Jam Kerja -->
    <div class="card mb-3">
    <div class="card-body">
        <h6 class="fw-bold mb-3">Informasi Jam Kerja Hari Ini</h6>
        <?php if (!empty($jamKerjaHariIni)) : ?>
        <div class="row gy-2 gx-5">
            <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-1" style="gap: 10px;">
                <span class="fw-semibold text-muted">Hari</span>
                <span class="fw-bold"><?= esc($jamKerjaHariIni['hari']) ?></span>
            </div>
            </div>
            <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-1" style="gap: 10px;">
                <span class="fw-semibold text-muted">Status Hari</span>
                <span class="badge bg-success"><?= esc($jamKerjaHariIni['status_hari']) ?></span>
            </div>
            </div>
            <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-1" style="gap: 10px;">
                <span class="fw-semibold text-muted">Jam Masuk</span>
                <span class="fw-bold"><?= esc($jamKerjaHariIni['jam_masuk']) ?></span>
            </div>
            </div>
            <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-1" style="gap: 10px;">
                <span class="fw-semibold text-muted">Jam Pulang</span>
                <span class="fw-bold"><?= esc($jamKerjaHariIni['jam_pulang']) ?></span>
            </div>
            </div>
        </div>
        <?php else : ?>
        <p class="text-muted mb-0">Tidak ada jadwal jam kerja untuk hari ini.</p>
        <?php endif; ?>
    </div>
    </div>

    <!-- Tombol Absen -->
    <div class="d-flex gap-2 mb-3">
        <button class="btn btn-success btn-sm fw-bold d-flex align-items-center justify-content-center gap-2 rounded"
            data-bs-toggle="modal" data-bs-target="#modalAbsenMasuk">
            <i class="bi bi-door-open-fill"></i> Absen Masuk
        </button>
        <button class="btn btn-danger btn-sm fw-bold d-flex align-items-center justify-content-center gap-2 rounded"
            data-bs-toggle="modal" data-bs-target="#modalAbsenPulang">
            <i class="bi bi-door-closed-fill"></i> Absen Pulang
        </button>
    </div>


    <!-- Tabel Presensi -->
    <div class="table-responsive">
      <table class="table" id="table1">
        <thead>
          <tr>
            <th>No</th>
            <th>Hari</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Waktu Masuk</th>
            <th>Waktu Pulang</th>
            <th>Status Kehadiran</th>
            <th>Status Presensi</th>
            <th>Foto</th>
          </tr>
        </thead>
        <tbody>
            <?php if (empty($presensi)): ?>
                <tr>
                    <td colspan="9" class="text-center text-muted fw-bold">Belum ada data presensi.</td>
                </tr>
            <?php else: ?>
            <?php 
            $no=1; 
            $hariIndo = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];
            ?>
            <?php foreach ($presensi as $p): ?>
            <?php 
                $hari = $hariIndo[date('l', strtotime($p['tanggal']))] ?? '-';
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($hari) ?></td>
                <td><?= date('d-m-Y', strtotime($p['tanggal'])) ?></td>
                <td><?= esc($p['keterangan']) ?></td>
                <td>
                    <?php 
                    if (!empty($p['waktu_masuk'])) {
                        echo date('H:i:s', strtotime($p['waktu_masuk']));
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
                <td>
                    <?php 
                    if (!empty($p['waktu_keluar'])) {
                        echo date('H:i:s', strtotime($p['waktu_keluar']));
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
                <td>
                <?php if ($p['status_kehadiran'] == 'Tepat Waktu'): ?>
                    <span class="badge bg-success">Tepat Waktu</span>
                <?php elseif ($p['status_kehadiran'] == 'Telat'): ?>
                    <span class="badge bg-warning text-dark">Telat</span>
                <?php else: ?>
                    <span class="badge bg-danger">Tidak Hadir</span>
                <?php endif; ?>
                </td>
                <td>
                    <?php if ($p['status_presensi'] == 'Disetujui'): ?>
                        <span class="badge bg-success">Disetujui</span>
                    <?php elseif ($p['status_presensi'] == 'Menunggu Validasi'): ?>
                        <span class="badge bg-secondary">Menunggu</span>
                    <?php else: ?>
                        <span class="badge bg-danger pointer" 
                              data-bs-toggle="modal" 
                              data-bs-target="#modalCatatanMahasiswa"
                              data-catatan="<?= esc($p['catatan_validasi']) ?>">
                              Tidak Disetujui
                        </span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!empty($p['foto_bukti'])): ?>
                        <img src="<?= base_url('uploads/presensi/' . $p['foto_bukti']) ?>" 
                            alt="Bukti" width="50" height="50" class="rounded border img-thumbnail pointer"
                            data-bs-toggle="modal" data-bs-target="#fotoModal" 
                            data-src="<?= base_url('uploads/presensi/' . $p['foto_bukti']) ?>">
                    <?php else: ?>
                        <span class="text-muted">-</span>
                    <?php endif; ?>
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

<!-- Modal Lihat Catatan Validasi -->
<div class="modal fade" id="modalCatatanMahasiswa" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title text-white fw-bold">Catatan Validasi</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="p-3 bg-light rounded shadow-sm">
          <p id="catatanMahasiswa" class="mb-0 text-dark" style="white-space: pre-wrap;"></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>



<!-- Modal Preview Gambar -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 bg-transparent">
      <div class="modal-body p-0">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
        <img src="" id="modalGambar" class="img-fluid rounded" alt="Preview Gambar">
      </div>
    </div>
  </div>
</div>


<!-- Modal Absen Masuk -->
<div class="modal fade" id="modalAbsenMasuk" tabindex="-1" aria-labelledby="modalAbsenMasukLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form action="<?= base_url('presensi/simpanMasuk') ?>" method="post" enctype="multipart/form-data" class="modal-content shadow-lg border-0 rounded-3">
      <div class="modal-header bg-primary text-white rounded-top-3">
        <h5 class="modal-title text-white fw-bold">Form Absen Masuk</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body p-4">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label label-with-icon"><i class="bi bi-calendar"></i> Tanggal Absensi</label>
              <input type="text" id="tanggalSekarangMasuk" name="tanggal" class="form-control rounded-3 shadow-sm" readonly>
            </div>

            <div class="mb-3">
              <label class="form-label label-with-icon"><i class="bi bi-clock"></i> Waktu Absensi</label>
              <input type="text" id="waktuSekarangMasuk" class="form-control rounded-3 shadow-sm" readonly>
            </div>
          </div>

          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label label-with-icon"><i class="bi bi-check-circle"></i> Status Absensi</label>
              <select name="keterangan" id="keterangan_masuk" class="form-select rounded-3 shadow-sm" required>
                <option value="Masuk">Masuk</option>
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
              </select>
            </div>

            <div class="mb-3">
            <label class="form-label label-with-icon"><i class="bi bi-image"></i> Upload Bukti</label>
            <input type="file" name="foto_bukti" id="foto_bukti_masuk" class="form-control rounded-3 shadow-sm" accept="image/*" required>
            <!-- Preview gambar -->
            <div id="preview_foto_masuk" class="mt-2">
                <img id="imgPreviewMasuk" src="" alt="Preview Foto" style="max-width:150px; display:none; border:1px solid #ddd; border-radius:5px;">
            </div>
            </div>
          </div>
        </div>
        <p class="text-muted small mt-2">Waktu absen masuk akan otomatis dicatat saat Anda klik “Simpan”.</p>
      </div>

      <div class="modal-footer border-top-0 d-flex justify-content-end p-3">
        <button type="button" class="btn btn-light border rounded-3" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary fw-bold rounded-3">Simpan</button>
      </div>
    </form>
  </div>
</div>


<!-- Modal Absen Pulang -->
<div class="modal fade" id="modalAbsenPulang" tabindex="-1" aria-labelledby="modalAbsenPulangLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="<?= base_url('presensi/simpanPulang') ?>" method="post" class="modal-content shadow-lg border-0 rounded-3">
      <div class="modal-header bg-primary text-white rounded-top-3">
        <h5 class="modal-title text-white fw-bold">Form Absen Pulang</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
          <label class="form-label label-with-icon"><i class="bi bi-clock"></i> Waktu Sekarang</label>
          <input type="text" id="waktuSekarangPulang" class="form-control rounded-3 shadow-sm" readonly>
        </div>
        <p class="text-muted small mb-0">Pastikan Anda sudah menyelesaikan aktivitas hari ini sebelum absen pulang.</p>
      </div>
      <div class="modal-footer border-top-0 d-flex justify-content-end p-3">
        <button type="button" class="btn btn-light border rounded-3" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary fw-bold rounded-3">Simpan</button>
      </div>
    </form>
  </div>
</div>


<!-- ===================== SCRIPT ===================== -->
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script>
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<?php if (!empty($jamKerja)): ?>
<script src="assets/static/js/pages/datatables.js"></script>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function updateDateTime() {
    const now = new Date();
    const tanggal = now.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
    const waktu = now.toLocaleTimeString('id-ID', { hour12: false });
    document.getElementById('tanggalSekarangMasuk').value = tanggal;
    document.getElementById('waktuSekarangMasuk').value = waktu;
    document.getElementById('waktuSekarangPulang').value = waktu;
}
setInterval(updateDateTime, 1000);
updateDateTime();
</script>

<script>
document.getElementById('foto_bukti_masuk').addEventListener('change', function(event) {
    const input = event.target;
    const preview = document.getElementById('imgPreviewMasuk');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result; // set src ke base64 image
            preview.style.display = 'block'; // tampilkan gambar
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
});
</script>

<script>
var fotoModal = document.getElementById('fotoModal');
fotoModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; // Gambar yang diklik
    var src = button.getAttribute('data-src'); // Ambil URL gambar
    var modalImg = document.getElementById('modalGambar');
    modalImg.src = src; // Set src modal
});
</script>

<script>
var modalCatatan = document.getElementById('modalCatatanMahasiswa');
modalCatatan.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget;
    var catatan = button.getAttribute('data-catatan');
    document.getElementById('catatanMahasiswa').textContent = catatan ? catatan : '-';
});


</script>


<?= $this->endSection() ?>
