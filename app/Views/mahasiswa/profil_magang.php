<?= $this->extend('layouts/mhs') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="./assets/compiled/css/app.css">
<link rel="stylesheet" href="./assets/compiled/css/app-dark.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery UI CSS -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


<style>
.save-btn {
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    background: linear-gradient(90deg, #0d6efd, #3b82f6);
    border: none;
    color: white;
}
.save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(13, 110, 253, 0.3);
    background: linear-gradient(90deg, #3b82f6, #0d6efd);
}
.save-btn i {
    display: flex;
    align-items: center;
    justify-content: center;
}
.save-btn span {
    display: flex;
    align-items: center;
}
</style>


<?php if (isset($dataLengkap) && !$dataLengkap): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Perhatian!</strong> Silakan lengkapi data profil magang Anda terlebih dahulu agar status magang dapat diproses.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="container py-4">

    <!-- Page Heading & Breadcrumb -->
    <div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Profil Magang</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('mahasiswa/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Profil Magang</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

    <?php if ($profil): ?>
        <form action="<?= base_url('mahasiswa/profil/update') ?>" method="post">
            <?= csrf_field() ?>

            <div class="card shadow-sm p-4 mb-4">
                <div class="row g-4">
                    <!-- Foto Mahasiswa -->
                    <div class="col-md-3 text-center">
                        <img src="<?= base_url('assets/images/pp.jpg') ?>" alt="Foto Mahasiswa" class="img-fluid rounded-circle mb-3" style="max-width:150px;">
                        <h5 class="mb-1"><?= $profil['nama_lengkap'] ?></h5>
                        <p class="text-muted mb-0"><?= $profil['nim'] ?></p>
                    </div>

                    <!-- Data Mahasiswa & Profil Magang -->
                    <div class="col-md-9">
                        <!-- Data Pribadi -->
                        <h5 class="mb-3 border-bottom pb-2">Data Pribadi</h5>
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" value="<?= $profil['nama_lengkap'] ?>" readonly disabled>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">NIM</label>
                                <input type="text" name="nim" class="form-control" value="<?= $profil['nim'] ?>" readonly disabled>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Program Studi</label>
                                <input type="text" name="program_studi" class="form-control" value="<?= $profil['program_studi'] ?? '' ?>" readonly disabled>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <input type="text" name="jenis_kelamin" class="form-control" value="<?= $profil['jenis_kelamin'] ?? '' ?>" readonly disabled>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email_mahasiswa" class="form-control" value="<?= $profil['email_mahasiswa'] ?? '' ?>">
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Handphone</label>
                                <input type="text" name="handphone" class="form-control" value="<?= $profil['handphone'] ?? '' ?>">
                            </div>
                        </div>

                        <!-- Data Mitra & Unit -->
                        <h5 class="mb-3 border-bottom pb-2 mt-4">Data Mitra & Unit</h5>
                        <div class="row">
                            <!-- Mitra -->
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Mitra/Perusahaan</label>
                                <input type="text" id="mitraInput" class="form-control" placeholder="Cari Mitra..." value="<?= $profil['nama_mitra'] ?? '' ?>">
                                <input type="hidden" name="id_mitra" id="mitraValue" value="<?= $profil['id_mitra'] ?? '' ?>">
                            </div>
                            <!-- Alamat Mitra (input manual) -->
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Alamat Mitra</label>
                                <input type="text" name="alamat_mitra" class="form-control" 
                                    value="<?= $profil['alamat_mitra'] ?? '' ?>" 
                                    placeholder="Masukkan alamat mitra">
                            </div>
                            <!-- Program -->
                            <div class="col-md-6">
                            <label class="form-label">
                                Program Magang
                            </label>
                            <select name="id_program" id="editProgramSelect" class="form-select">
                                <option value="">Pilih Program Magang...</option>
                                <?php foreach ($program as $p): ?>
                                    <option value="<?= $p['id_program'] ?>" 
                                        <?= (isset($profil['id_program']) && $profil['id_program'] == $p['id_program']) ? 'selected' : '' ?>>
                                        <?= esc($p['nama_program']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            </div>
                            <!-- Unit -->
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Unit</label>
                                <div class="input-group">
                                    <select name="id_unit" id="unitSelect" class="form-select">
                                        <option value="">Pilih Unit...</option>
                                        <?php if(isset($unitList) && !empty($unitList)): ?>
                                            <?php foreach($unitList as $unit): ?>
                                                <option value="<?= $unit['id_unit'] ?>" <?= (isset($profil['id_unit']) && $profil['id_unit'] == $unit['id_unit']) ? 'selected' : '' ?>>
                                                    <?= $unit['nama_unit'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <option value="__tambah__">+ Tambah Unit Baru</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Pembimbing Unit</label>
                                <input type="text" name="nama_pembimbing" class="form-control" value="<?= $profil['nama_pembimbing'] ?? '' ?>">
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Jabatan Pembimbing</label>
                                <input type="text" name="jabatan" class="form-control" value="<?= $profil['jabatan'] ?? '' ?>">
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">No Handphone Pebimbing</label>
                                <input type="text" name="no_hp" class="form-control" value="<?= $profil['no_hp'] ?? '' ?>">
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Email Pebimbing</label>
                                <input type="email" name="email_unit" class="form-control" value="<?= $profil['email'] ?? '' ?>">
                            </div>
                        </div>

                        <!-- Data Dosen Pembimbing -->
                        <h5 class="mb-3 border-bottom pb-2 mt-4">Dosen Pembimbing</h5>
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Nama Dosen</label>
                                <input type="text" name="nama_dosen" class="form-control" value="<?= $profil['nama_dosen'] ?? '' ?>" readonly disabled>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Status Magang</label>
                                <input type="text" name="status" class="form-control" value="<?= isset($profil['status']) ? ucfirst($profil['status']) : '' ?>" readonly disabled>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control" value="<?= $profil['tanggal_mulai'] ?? '' ?>">
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control" value="<?= $profil['tanggal_selesai'] ?? '' ?>">
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm d-inline-flex align-items-center justify-content-center gap-2 save-btn">
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </form>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            Profil magang Anda belum tersedia.
        </div>
    <?php endif; ?>
</div>

<!-- Modal Tambah Unit -->
<div class="modal fade" id="modalTambahUnit" tabindex="-1" aria-labelledby="modalTambahUnitLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formTambahUnit" method="post" action="<?= base_url('mahasiswa/tambahUnit') ?>">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahUnitLabel">Tambah Unit Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Unit</label>
            <input type="text" name="nama_unit" class="form-control" placeholder="Masukkan nama unit" required>
          </div>
          <input type="hidden" name="id_mitra" id="idMitraForUnit" value="<?= $profil['id_mitra'] ?? '' ?>">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script> 
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="assets/static/js/pages/datatables.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    select: function(event, ui){
    // Set nilai Mitra
    $(inputSelector).val(ui.item.label);
    $(hiddenSelector).val(ui.item.id);

    // 🧹 Reset semua field terkait mitra & unit
    $('input[name="alamat_mitra"]').val('');
    $('select[name="id_unit"]').empty().append('<option value="">Pilih Unit...</option><option value="__tambah__">+ Tambah Unit Baru</option>');
    $('input[name="nama_pembimbing"]').val('');
    $('input[name="jabatan"]').val('');
    $('input[name="no_hp"]').val('');
    $('input[name="email_unit"]').val('');

    // 🔍 Ambil detail mitra (misal alamat)
    $.getJSON("<?= base_url('mahasiswa/getMitraDetail') ?>", { id_mitra: ui.item.id }, function(data){
        if (!data.error && data.alamat_mitra) {
            $('input[name="alamat_mitra"]').val(data.alamat_mitra);
        }
    });

    // 🔄 Load unit-unit milik mitra ini
    if(unitSelectSelector){
        $.getJSON("<?= base_url('mahasiswa/getUnitByMitra') ?>", { id_mitra: ui.item.id }, function(units){
            let $unit = $(unitSelectSelector);
            $unit.empty();
            $unit.append('<option value="">Pilih Unit...</option>');
            $.each(units, function(i, u){
                $unit.append('<option value="'+u.id_unit+'">'+u.nama_unit+'</option>');
            });
            $unit.append('<option value="__tambah__">+ Tambah Unit Baru</option>');
        });
    }

    return false; // cegah default autocomplete
},

</script>


<script>
// === Ketika Unit dipilih, ambil detail pembimbing ===
$('#unitSelect').on('change', function(){
    const idUnit = $(this).val();

    // Kalau bukan tambah baru dan bukan kosong
    if (idUnit && idUnit !== '__tambah__') {
        $.getJSON("<?= base_url('mahasiswa/getUnitDetail') ?>", { id_unit: idUnit }, function(data){
            if (!data.error) {
                $('input[name="nama_pembimbing"]').val(data.nama_pembimbing);
                $('input[name="jabatan"]').val(data.jabatan);
                $('input[name="no_hp"]').val(data.no_hp);
                $('input[name="email_unit"]').val(data.email);
            } else {
                console.log(data.error);
            }
        });
    }
});
</script>

<script>
$(function(){
    // Fungsi untuk setup autocomplete Mitra
    function setupAutocomplete(inputSelector, hiddenSelector, searchUrl, unitSelectSelector){
        $(inputSelector).autocomplete({
            source: function(request, response){
                $.getJSON(searchUrl, { q: request.term }, function(data){
                    response(data.slice(0,5)); // ambil maksimal 5 hasil
                });
            },
            minLength: 1,
            select: function(event, ui){
                // 🟦 1. Set nilai Mitra
                $(inputSelector).val(ui.item.label);
                $(hiddenSelector).val(ui.item.id);

                // 🟨 2. Kosongkan field terkait mitra & unit
                $('input[name="alamat_mitra"]').val('');
                $('select[name="id_unit"]').empty()
                    .append('<option value="">Pilih Unit...</option>')
                    .append('<option value="__tambah__">+ Tambah Unit Baru</option>');
                $('input[name="nama_pembimbing"]').val('');
                $('input[name="jabatan"]').val('');
                $('input[name="no_hp"]').val('');
                $('input[name="email_unit"]').val('');

                // 🟩 3. Ambil detail mitra (alamat)
                $.getJSON("<?= base_url('mahasiswa/getMitraDetail') ?>", { id_mitra: ui.item.id }, function(data){
                    if (!data.error && data.alamat_mitra) {
                        $('input[name="alamat_mitra"]').val(data.alamat_mitra);
                    }
                });

                // 🟦 4. Ambil daftar unit milik mitra
                if(unitSelectSelector){
                    $.getJSON("<?= base_url('mahasiswa/getUnitByMitra') ?>", { id_mitra: ui.item.id }, function(units){
                        let $unit = $(unitSelectSelector);
                        $unit.empty();
                        $unit.append('<option value="">Pilih Unit...</option>');
                        $.each(units, function(i, u){
                            $unit.append('<option value="'+u.id_unit+'">'+u.nama_unit+'</option>');
                        });
                        $unit.append('<option value="__tambah__">+ Tambah Unit Baru</option>');
                    });
                }

                return false; // cegah default autocomplete
            },
            open: function(){
                // Styling dropdown hasil autocomplete
                var $input = $(this);
                $(".ui-autocomplete").css({
                    "z-index": 3000,
                    "background": "#fff",
                    "border": "1px solid #ced4da",
                    "border-radius": "0.375rem",
                    "box-shadow": "0 4px 8px rgba(0,0,0,0.1)",
                    "max-height": "200px",
                    "overflow-y": "auto",
                    "width": $input.outerWidth() + "px"
                });
            }
        });
    }

    // Panggil fungsi autocomplete Mitra
    setupAutocomplete(
        "#mitraInput",   // input text Mitra
        "#mitraValue",   // hidden id_mitra
        "<?= base_url('mahasiswa/searchMitra') ?>", // URL pencarian mitra
        "#unitSelect"    // select dropdown Unit
    );
});
</script>



<script>
// Jika user memilih "+ Tambah Unit Baru"
$('#unitSelect').on('change', function() {
    const selected = $(this).val();
    if (selected === '__tambah__') {
        if (!$('#mitraValue').val()) {
            alert('Pilih Mitra terlebih dahulu sebelum menambah unit!');
            $(this).val(''); // reset
            return;
        }

        // Set mitra ke form modal
        $('#idMitraForUnit').val($('#mitraValue').val());
        $('#modalTambahUnit').modal('show');
        $(this).val(''); // reset dropdown ke kosong
    }
});

// Proses tambah unit via AJAX
$('#formTambahUnit').on('submit', function(e){
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function(res){
            if(res.success){
                $('#modalTambahUnit').modal('hide');
                alert('Unit berhasil ditambahkan');
                // Tambah ke dropdown dan pilih otomatis
                $('#unitSelect option[value="__tambah__"]').before(
                    $('<option>', {
                        value: res.data.id_unit,
                        text: res.data.nama_unit,
                        selected: true
                    })
                );
            } else {
                alert('Gagal menambah unit: ' + res.message);
            }
        },
        error: function(){
            alert('Terjadi kesalahan server.');
        }
    });
});

</script>


<?= $this->endSection() ?>
