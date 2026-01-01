<?= $this->extend('layouts/kaprodi') ?>

<?= $this->section('title') ?>
Daftar Nilai Magang
<?= $this->endSection() ?>

<!-- CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">

<?= $this->section('content') ?>

<style>
.table-responsive {
  overflow: visible !important;
}
</style>

            <!-- Page Heading & Breadcrumb -->
            <div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Daftar Nilai Magang</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('kaprodi/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Daftar Nilai Magang</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
 

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        Nilai Mahasiswa Magang Periode Semester <?= esc($semester) ?> - TA <?= esc($tahun_ajaran) ?>
                    </h5>

                    <button id="exportExcel" 
                        class="btn btn-success btn-sm fw-bold d-flex align-items-center justify-content-center gap-2 rounded">
                        
                        <i class="bi bi-file-earmark-excel" 
                        style="font-size:16px; line-height:1; vertical-align:middle; margin-top:-1px;"></i>

                        Export Excel
                    </button>
                </div>

                <div class="card-body">

                    <?php if(session()->getFlashdata('success')): ?>
                        <div class="alert alert-light-success color-success">
                            <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success'); ?>
                        </div>
                    <?php elseif(session()->getFlashdata('error')): ?>
                        <div class="alert alert-light-danger color-danger">
                            <i class="bi bi-exclamation-circle"></i> <?= session()->getFlashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                    <table class="table align-middle text-center" id="table1">
                        <thead>
                            <tr>
                                <th style="width:40px;">No</th>
                                <th style="width:120px;">NIM</th>
                                <th style="width:220px; text-align:left;">Nama Lengkap</th>

                                <!-- Nilai Akhir & Grade per Matakuliah -->
                                <th style="width:100px;">Nilai Magang</th>
                                <th style="width:75px;">Grade Magang</th>

                                <th style="width:100px;">Nilai Kombis</th>
                                <th style="width:75px;">Grade Kombis</th>

                                <th style="width:100px;">Nilai ASIB</th>
                                <th style="width:75px;">Grade ASIB</th>

                                <th style="width:100px;">Nilai DSIB</th>
                                <th style="width:75px;">Grade DSIB</th>

                                <th style="width:75px;">Aksi</th>
                            </tr>
                        </thead>


                        <tbody>
                        <?php $no = 1; foreach ($mahasiswa as $m): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($m['nim']) ?></td>
                                <td style="text-align:left;"><?= esc($m['nama_lengkap']) ?></td>

                                <td>
                                    <?= isset($m['magang_final']) 
                                        ? '<span class="fw-bold text-primary">'.number_format($m['magang_final'],2).'</span>'
                                        : '-' ?>
                                </td>
                                <td>
                                    <?= isset($m['grade_magang'])
                                        ? '<span class="fw-bold text-primary">'.$m['grade_magang'].'</span>'
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= isset($m['kombis']) 
                                        ? '<span class="fw-bold text-primary">'.number_format($m['kombis'],2).'</span>'
                                        : '-' ?>
                                </td>
                                <td>
                                    <?= isset($m['grade_kombis'])
                                        ? '<span class="fw-bold text-primary">'.$m['grade_kombis'].'</span>'
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= isset($m['asib']) 
                                        ? '<span class="fw-bold text-primary">'.number_format($m['asib'],2).'</span>'
                                        : '-' ?>
                                </td>
                                <td>
                                    <?= isset($m['grade_asib'])
                                        ? '<span class="fw-bold text-primary">'.$m['grade_asib'].'</span>'
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= isset($m['dsib']) 
                                        ? '<span class="fw-bold text-primary">'.number_format($m['dsib'],2).'</span>'
                                        : '-' ?>
                                </td>
                                <td>
                                    <?= isset($m['grade_dsib'])
                                        ? '<span class="fw-bold text-primary">'.$m['grade_dsib'].'</span>'
                                        : '-' ?>
                                </td>

                                <td>
                                    <a href="<?= base_url('kaprodi/detail_nilai/'.$m['nim']) ?>" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    </div>

                </div>
            </div>

<!-- JS -->
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>

<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/static/js/pages/datatables.js"></script>

<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

<!-- JSZip untuk export Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
$(document).ready(function() {
    $('#table1').DataTable();
});
</script>

<script>
$(document).ready(function() {

    var table = $('#table1').DataTable();

    new $.fn.dataTable.Buttons(table, {
        buttons: [
            {
                extend: 'excelHtml5',
                title: null,
                filename: 'Nilai Mahasiswa Magang Periode Semester <?= $semester ?> - TA <?= str_replace("/", "", $tahun_ajaran) ?>',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10,11]
                },
                customize: function(xlsx) {


                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    var sheetData = sheet.getElementsByTagName('sheetData')[0];

                    // ==============================
                    // Tanggal Real-Time (JavaScript)
                    // ==============================
                    let now = new Date();
                    let today = String(now.getDate()).padStart(2, '0') + '-' +
                                String(now.getMonth() + 1).padStart(2, '0') + '-' +
                                now.getFullYear();

                    // ========== SIMPAN ROW ASLI ==========
                    var originalRows = sheetData.innerHTML;

                    // ========== HAPUS ISI SHEET ==========
                    sheetData.innerHTML = "";

                    // ========== TAMBAH HEADER BARU ==========
                    sheetData.innerHTML += `
                        <row r="1">
                            <c t="inlineStr" r="A1"><is><t>Nilai Mahasiswa Magang Periode Semester <?= $semester ?> - TA <?= $tahun_ajaran ?></t></is></c>
                        </row>

                        <row r="2"></row>

                        <row r="3">
                            <c t="inlineStr" r="A3"><is><t>Diunduh Oleh :</t></is></c>
                            <c t="inlineStr" r="C3"><is><t><?= $kaprodi_nama ?></t></is></c>

                            <c t="inlineStr" r="E3"><is><t>Tanggal :</t></is></c>
                            <c t="inlineStr" r="F3"><is><t>${today}</t></is></c>
                        </row>

                        <row r="4">
                            <c t="inlineStr" r="A4"><is><t>Jabatan :</t></is></c>
                            <c t="inlineStr" r="C4"><is><t><?= $kaprodi_jabatan ?></t></is></c>
                        </row>

                        <row r="5"></row>
                    `;

                    // ========== MASUKKAN KEMBALI ROW TABEL ==========

                    // shift semua row original + 5
                    // tapi TANPA geser satu per satu agar tidak bentrok
                    originalRows = originalRows.replace(/r="(\d+)"/g, function(match, rowNum) {
                        return 'r="' + (parseInt(rowNum) + 5) + '"';
                    });

                    originalRows = originalRows.replace(/([A-Z]+)(\d+)/g, function(match, col, rowNum) {
                        return col + (parseInt(rowNum) + 5);
                    });

                    sheetData.innerHTML += originalRows;
                }
            }
        ]
    });

    $('#exportExcel').on('click', function() {
        table.button(0).trigger();
    });

});
</script>


<?= $this->endSection() ?>
