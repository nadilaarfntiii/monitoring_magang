<?= $this->extend('layouts/dospem') ?>

<?= $this->section('title') ?>
Detail Presensi Mahasiswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Heading & Breadcrumb -->
<div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Detail Presensi</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('dospem/data_presensi') ?>">Data Presensi</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Detail Presensi</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div> 
            </div>

<section class="section">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 fw-semibold text-white" style="color: white !important;">Rekapitulasi Presensi <?= esc($mahasiswa['nama_lengkap']) ?></h5>
        </div>

        <div class="card-body">

            <?php
            $hadir = $izin = $sakit = $alpha = 0;

            foreach ($presensi as $p) {
                switch ($p['keterangan']) {
                    case 'Masuk': $hadir++; break;
                    case 'Izin': $izin++; break;
                    case 'Sakit': $sakit++; break;
                    case 'Alpha': $alpha++; break;
                }
            }

            $totalHari = count($presensi);
            $persentase = $totalHari > 0 ? round(($hadir / $totalHari) * 100, 2) : 0;
            ?>

            <br>

            <!-- 🔹 Rekap Presensi -->
            <div class="row justify-content-center text-center mb-4">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <h6 class="fw-semibold text-secondary mb-1">Hadir</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $hadir ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <h6 class="fw-semibold text-secondary mb-1">Izin</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $izin ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <h6 class="fw-semibold text-secondary mb-1">Sakit</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $sakit ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <h6 class="fw-semibold text-secondary mb-1">Alpha</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $alpha ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 🔹 Detail Presensi -->
            <div class="table-responsive">
                <h5 class="fw-bold mb-3">Detail Presensi</h5>
                <table class="table align-middle text-center shadow-sm">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Hari Ke</th>
                            <th style="width: 12%;">Tanggal</th>
                            <th style="width: 12%;">Waktu Masuk</th>
                            <th style="width: 12%;">Status Kehadiran</th>
                            <th style="width: 15%;">Keterangan</th>
                            <th style="width: 15%;">Foto Bukti</th>
                            <th style="width: 12%;">Status Presensi</th>
                            <th style="width: 17%;">Catatan Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($presensi)) : ?>
                            <?php foreach ($presensi as $p) : ?>
                                <tr>
                                    <td><?= esc($p['hari_ke']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($p['tanggal'])) ?></td>
                                    <td><?= $p['waktu_masuk'] ? date('H:i:s', strtotime($p['waktu_masuk'])) : '-' ?></td>
                                    <td><?= esc($p['status_kehadiran'] ?? '-') ?></td>
                                    <td><?= esc($p['keterangan'] ?? '-') ?></td>
                                    <td>
                                        <?php if (!empty($p['foto_bukti'])): ?>
                                            <img src="<?= base_url('/uploads/presensi/'.$p['foto_bukti']) ?>"
                                                alt="Bukti Presensi"
                                                class="img-thumbnail rounded shadow-sm foto-presensi"
                                                style="max-width: 90px; cursor: pointer;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalFoto"
                                                data-foto="<?= base_url('/uploads/presensi/'.$p['foto_bukti']) ?>">
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        switch($p['status_presensi']){
                                            case 'Menunggu Validasi': 
                                                echo '<span class="badge bg-secondary">Menunggu</span>'; 
                                                break;
                                            case 'Disetujui': 
                                                echo '<span class="badge bg-success">Disetujui</span>'; 
                                                break;
                                            case 'Tidak Disetujui': 
                                                echo '<span class="badge bg-danger">Ditolak</span>'; 
                                                break;
                                            default: 
                                                echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td><?= esc($p['catatan_validasi'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada data presensi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Preview Foto -->
<div class="modal fade" id="modalFoto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title text-white">Bukti Presensi</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img id="previewFoto" src="" class="img-fluid rounded shadow">
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('foto-presensi')) {
        const foto = e.target.getAttribute('data-foto');
        document.getElementById('previewFoto').src = foto;
    }
});
</script>

<?= $this->endSection() ?>
