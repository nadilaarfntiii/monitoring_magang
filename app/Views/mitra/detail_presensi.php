<?= $this->extend('layouts/mitra') ?>

<?= $this->section('title') ?>
Detail Presensi Mahasiswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
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
                <h5 class="fw-bold mb-3">Detail Presensi Harian</h5>
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
                                                 class="img-thumbnail rounded shadow-sm"
                                                 style="max-width: 90px;">
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

            <div class="text-end mt-4">
                <a href="<?= base_url('mitra/mahasiswa') ?>" class="btn btn-outline-secondary">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
