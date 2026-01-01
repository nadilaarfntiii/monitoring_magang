<?= $this->extend('layouts/dospem') ?>

<?= $this->section('title') ?>
Input Nilai Mahasiswa
<?= $this->endSection() ?>

<!-- === CSS IMPORT (disamakan dengan Detail Bimbingan) === -->
<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

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

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  vertical-align: middle;
}

.btn i {
  font-size: 1rem;
  line-height: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: -1px;
}
</style>

<!-- Page Heading & Breadcrumb -->
<div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Input Nilai Magang</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('dospem/kelola_penilaian') ?>">Kelola Nilai Mahasiswa</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Input Nilai Magang</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">

    <div class="card-body">

        <!-- ========================== -->
        <!-- CARD INFO MAHASISWA (disamakan formatnya) -->
        <!-- ========================== -->
        <div class="card border-primary shadow-sm mb-4">
            <div class="card-body">

                <h5 class="card-title fw-bold text-primary mb-3">
                    Informasi Mahasiswa
                </h5>

                <div class="row">

                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="text-secondary" style="width:40%;">NIM</th>
                                    <td style="width:5%;">:</td>
                                    <td class="fw-semibold"><?= esc($mahasiswa['nim']) ?></td>
                                </tr>

                                <tr>
                                    <th class="text-secondary">Nama Lengkap</th>
                                    <td>:</td>
                                    <td class="fw-semibold"><?= esc($mahasiswa['nama_lengkap']) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="text-secondary" style="width:40%;">Program Magang</th>
                                    <td style="width:5%;">:</td>
                                    <td class="fw-semibold"><?= esc($mahasiswa['nama_program']) ?></td>
                                </tr>

                                <tr>
                                    <th class="text-secondary">Tempat Magang</th>
                                    <td>:</td>
                                    <td class="fw-semibold"><?= esc($mahasiswa['nama_mitra']) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <!-- Jika belum ada komponen nilai -->
        <?php if (empty($komponen)): ?>
            <div class="alert alert-warning">
                <i class="bi bi-info-circle me-2"></i> Komponen nilai belum diinput oleh Kaprodi untuk program ini.
            </div>
        <?php else: ?>

        <!-- FORM INPUT NILAI -->
        <form action="<?= base_url('dospem/simpan_nilai_magang/' . $mahasiswa['nim']) ?>" method="post">

        <div class="table-responsive">

        <!-- FILTER KODE MK -->
        <?php if (!empty($komponen)): ?>
        <div class="row mb-3">
            <div class="col-md-6">
                
                <div class="d-flex align-items-center gap-2">

                    <!-- Label + Icon -->
                    <label>
                        Filter Mata Kuliah :
                    </label>

                    <!-- Select -->
                    <select id="filterKodeMK" class="form-select" style="max-width: 348px;">
                        <option value="">Semua Mata Kuliah</option>

                        <?php foreach ($listKodeMK as $mk): ?>
                            <option value="<?= esc($mk['kode_mk']) ?>">
                                <?= esc($mk['kode_mk']) ?> - <?= esc($mk['nama_mk']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                </div>

            </div>
        </div>
        <?php endif; ?>

        <table id="tabelKomponen" class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:25%;">Mata Kuliah</th>
                    <th style="width:40%;">Komponen Nilai</th>
                    <th style="width:10%;">Bobot (%)</th>
                    <th style="width:20%;">Nilai</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $no = 1;
                $lastMK = '';
                
                // Hitung jumlah komponen per MK (untuk rowspan)
                $mkCounts = [];
                foreach ($komponen as $k) {
                    $mk = $k['kode_mk'];
                    if (!isset($mkCounts[$mk])) $mkCounts[$mk] = 0;
                    $mkCounts[$mk]++;
                }

                // Buffer penyimpanan untuk perhitungan total per MK
                $mkTotals = [];

                foreach ($komponen as $index => $k):

                    $mk = $k['kode_mk'];

                    // Inisialisasi group total jika belum
                    if (!isset($mkTotals[$mk])) {
                        $mkTotals[$mk] = [
                            'totalBobot' => 0,
                            'totalWeighted' => 0
                        ];
                    }

                ?>
                <tr data-kodemk="<?= strtolower($mk) ?>">
                    
                    <?php if ($mk !== $lastMK): ?>
                        <td rowspan="<?= $mkCounts[$mk] ?>"><?= $no++ ?></td>
                        <td rowspan="<?= $mkCounts[$mk] ?>"><?= esc($k['nama_mk']) ?></td>
                    <?php endif; ?>

                    <td class="text-start"><?= esc($k['komponen']) ?></td>
                    <td class="bobot"><?= esc($k['presentase']) ?>%</td>

                    <td>
                        <input type="number"
                        name="nilai[<?= $k['id_nilai'] ?>]"
                        class="form-control nilaiInput text-center"
                        data-mk="<?= $mk ?>"
                        min="0"
                        max="<?= $k['presentase'] ?>"
                        data-bobot="<?= $k['presentase'] ?>"
                        value="<?= $nilaiTersimpan[$k['id_nilai']]['nilai'] ?? '' ?>"
                        placeholder="0 - <?= $k['presentase'] ?>">
                    </td>

                </tr>

                <?php
                // Simpan bobot untuk perhitungan total
                $mkTotals[$mk]['totalBobot'] += $k['presentase'];

                // Jika ini baris terakhir untuk MK → insert row total MK
                $isLastRowForMK = 
                    (!isset($komponen[$index + 1])) ||
                    ($komponen[$index + 1]['kode_mk'] !== $mk);

                if ($isLastRowForMK):
                ?>

                <!-- BARIS TOTAL PER MATA KULIAH -->
                <tr class="table-light fw-bold group-total" data-totalmk="<?= $mk ?>">
                    <td colspan="3" class="text-end">Total Nilai <?= esc($k['nama_mk']) ?> :</td>
                    <td class="totalBobotMK">0%</td>
                    <td class="totalNilaiMK">
                        <?= isset($totalPerMK[$mk]) ? number_format($totalPerMK[$mk], 2) : '0.00' ?>
                    </td>
                </tr>

                <?php endif; ?>

                <?php
                $lastMK = $mk;
                endforeach;
                ?>
            </tbody>
        </table>


        </div>


            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Nilai
                </button>
            </div>

        </form>

        <?php endif; ?>

    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterMK = document.getElementById('filterKodeMK');

    function applyFilter() {
        let value = filterMK.value.toLowerCase();
        let rows = document.querySelectorAll("#tabelKomponen tbody tr");

        // Sembunyikan semua
        rows.forEach(row => row.style.display = "none");

        // Jika filter kosong → tampilkan semua
        if (!value) {
            rows.forEach(row => row.style.display = "");
            return;
        }

        // 1. Tampilkan baris komponen sesuai MK
        rows.forEach(row => {
            if (row.dataset.kodemk === value) {
                row.style.display = "";
            }
        });

        // 2. Tampilkan baris total MK (group-total)
        const totalRow = document.querySelector(`tr.group-total[data-totalmk="${value.toUpperCase()}"]`);
        if (totalRow) totalRow.style.display = "";
    }

    filterMK.addEventListener('change', applyFilter);
});

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const inputs = document.querySelectorAll('.nilaiInput');

    function hitungPerMK() {

    const groups = {}; // tempat hitungan per MK

    inputs.forEach(input => {
        const mk = input.dataset.mk;
        const nilai = parseFloat(input.value || 0);
        const bobot = parseFloat(input.dataset.bobot || 0);

        if (!groups[mk]) {
            groups[mk] = {
                totalNilai: 0,
                totalBobot: 0
            };
        }

        // ❗ TAMBAHKAN NILAI SAJA TANPA PERKALIAN BOBOT
        groups[mk].totalNilai += nilai;
        groups[mk].totalBobot += bobot;
    });

    // Update ke row total
    document.querySelectorAll('.group-total').forEach(row => {
        const mk = row.dataset.totalmk;

        const totalNilaiMK = groups[mk]?.totalNilai || 0;
        const totalBobotMK = groups[mk]?.totalBobot || 0;

        // 🔥 tampilkan total bobot MK (dalam format persen)
        row.querySelector('.totalBobotMK').innerText = totalBobotMK + "%";
        row.querySelector('.totalNilaiMK').innerText =
            Number.isInteger(totalNilaiMK) ? totalNilaiMK : totalNilaiMK.toFixed(2);
    });
    }

    // 🔥 Langsung hitung saat halaman pertama kali dimuat
    hitungPerMK();

    // Saat input nilai berubah, hitung ulang
    inputs.forEach(input => {
        input.addEventListener('input', function () {

            let nilai = parseFloat(this.value || 0);
            let bobot = parseFloat(this.dataset.bobot || 0);

            if (nilai > bobot) {
                alert(`Nilai tidak boleh melebihi bobot komponen (${bobot})`);
                this.value = bobot;
            }
            
            if (nilai < 0) this.value = 0;

            hitungPerMK();
        });
    });

});
</script>

<?= $this->endSection() ?>
