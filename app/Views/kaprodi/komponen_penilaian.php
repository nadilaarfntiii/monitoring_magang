<?= $this->extend('layouts/kaprodi') ?>

<?= $this->section('title') ?>
Komponen Penilaian
<?= $this->endSection() ?>

<!-- === CSS IMPORT === -->
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
                            <h3>Komponen Nilai Magang</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('kaprodi/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Komponen Nilai Magang</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0 fw-bold">Komponen Nilai Magang</h5>
    </div>

    <div class="card-body">
        <!-- Filter Mata Kuliah -->
        <form method="get" action="">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Pilih Mata Kuliah</label>
                    <select name="kode_mk" id="filterMk" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Mata Kuliah --</option>
                        <?php foreach ($mata_kuliah as $mk): ?>
                            <option value="<?= esc($mk['kode_mk']) ?>" <?= isset($_GET['kode_mk']) && $_GET['kode_mk'] == $mk['kode_mk'] ? 'selected' : '' ?>>
                                <?= esc($mk['nama_mk']) ?> (<?= esc($mk['kode_mk']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- 🔹 Tambahkan filter program magang -->
                <div class="col-md-4">
                    <label class="form-label">Pilih Program Magang</label>
                    <select name="id_program" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Program --</option>
                        <?php foreach ($program_magang as $prog): ?>
                            <option value="<?= esc($prog['id_program']) ?>" 
                                <?= isset($_GET['id_program']) && $_GET['id_program'] == $prog['id_program'] ? 'selected' : '' ?>>
                                <?= esc($prog['nama_program']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filter Role (muncul kalau MK Magang) -->
                <div class="col-md-4 <?= (isset($_GET['kode_mk']) && $_GET['kode_mk'] == 'BB010') ? '' : 'd-none' ?>" id="filterRoleWrapper">
                    <label class="form-label">Filter Berdasarkan Role</label>
                    <select name="role" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Role --</option>
                        <option value="dospem" <?= isset($_GET['role']) && $_GET['role'] == 'dospem' ? 'selected' : '' ?>>Dosen Pembimbing</option>
                        <option value="mitra" <?= isset($_GET['role']) && $_GET['role'] == 'mitra' ? 'selected' : '' ?>>Pembimbing Mitra</option>
                        <option value="kaprodi" <?= isset($_GET['role']) && $_GET['role'] == 'kaprodi' ? 'selected' : '' ?>>Kepala Program Studi</option>
                    </select>
                </div>
            </div>
        </form>

        <!-- Tombol Tambah -->
        <div class="mb-3 text-end">
            <a href="javascript:void(0)" class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle"></i> Tambah Komponen Nilai
            </a>
        </div>

        <!-- 🔔 ALERT FLASHDATA -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-light-success color-success d-flex align-items-center justify-content-between">
                <div><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success'); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="alert alert-light-danger color-danger d-flex align-items-center justify-content-between">
                <div><i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error'); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <!-- END ALERT -->

        <!-- Tabel Komponen Nilai -->
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Mata Kuliah</th>
                    <th style="width: 10%;">Program Magang</th>
                    <th style="width: 10%;">Role Penilai</th>
                    <th style="width: 45%;">Komponen</th>
                    <th style="width: 10%;">Presentase (%)</th>
                    <th style="width: 8%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // --- Kelompokkan dan filter terlebih dahulu ---
                    $kelompok = [];
                    if (!empty($komponen_nilai ?? [])) {
                        foreach ($komponen_nilai as $row) {

                            // 🔹 Filter program magang (jika dipilih)
                            if (isset($_GET['id_program']) && $_GET['id_program'] != '') {
                                if (!isset($row['id_program']) || $row['id_program'] != $_GET['id_program']) {
                                    continue;
                                }
                            }

                            // 🔹 Filter role (kalau MK magang BB010)
                            if (isset($_GET['kode_mk']) && $_GET['kode_mk'] == 'BB010' && isset($_GET['role']) && $_GET['role'] != '' && $row['role'] != $_GET['role']) {
                                continue;
                            }

                            // 🔹 Filter kode MK (kalau dipilih)
                            if (isset($_GET['kode_mk']) && $_GET['kode_mk'] != '' && $row['kode_mk'] != $_GET['kode_mk']) {
                                continue;
                            }

                            // 🔹 Kunci pengelompokan (gabungkan kode MK + id_program + role)
                            $key = $row['kode_mk'] . '_' . ($row['id_program'] ?? '0') . '_' . ($row['role'] ?? 'dospem');
                            $kelompok[$key][] = $row;
                        }
                    }

                    // --- Jika hasil filter kosong ---
                    if (empty($kelompok)):
                    ?>
                        <tr>
                            <td colspan="7">Belum ada komponen nilai</td>
                        </tr>
                    <?php 
                    else:
                        $no = 1;
                        foreach ($kelompok as $group):
                            $rowspan = count($group);
                            $first = true;

                            foreach ($group as $row):
                    ?>
                        <tr>
                            <?php if ($first): ?>
                                <td rowspan="<?= $rowspan ?>"><?= $no++ ?></td>
                                <td rowspan="<?= $rowspan ?>"><?= esc($row['nama_mk']) ?></td>
                                <td rowspan="<?= $rowspan ?>"><?= esc($row['nama_program'] ?? '-') ?></td>
                                <td rowspan="<?= $rowspan ?>"><?= esc(ucwords($row['role'] ?? '-')) ?></td>
                            <?php endif; ?>

                            <td style="text-align: justify;"><?= esc($row['komponen']) ?></td>
                            <td><?= esc($row['presentase']) ?>%</td>

                            <?php if ($first): ?>
                                <td rowspan="<?= $rowspan ?>" class="align-middle">
                                <!-- Tombol Edit -->
                                    <button 
                                        class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit_<?= $row['kode_mk'] ?>_<?= esc($row['role']) ?>_<?= esc($row['id_program']) ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="<?= base_url('komponenNilai/deleteGroup') ?>" 
                                          method="post" 
                                          class="d-inline"
                                          onsubmit="return confirm('Hapus semua komponen nilai untuk Matakuliah ini?')">

                                        <input type="hidden" name="kode_mk" value="<?= $row['kode_mk'] ?>">
                                        <input type="hidden" name="id_program" value="<?= $row['id_program'] ?>">
                                        <input type="hidden" name="role" value="<?= $row['role'] ?>">

                                        <button class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php 
                                $first = false;
                                endforeach;

                                // === Tambahkan total presentase tiap group ===
                                $totalPresentase = array_sum(array_column($group, 'presentase'));
                                ?>
                                    <tr class="fw-bold table-light">
                                        <td colspan="5" class="text-center">TOTAL NILAI</td>
                                        <td><?= esc($totalPresentase) ?>%</td>
                                        <td class="table-light"></td>
                                    </tr>
                                <?php
                                endforeach;
                                endif;
                                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Komponen Nilai -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <form id="formTambahKomponen" action="<?= base_url('kaprodi/komponen_penilaian/save') ?>" method="post">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title text-white fw-bold" id="modalTambahLabel">Tambah Komponen Nilai</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- Pilih Mata Kuliah -->
          <div class="mb-3">
            <label class="label-with-icon">Mata Kuliah</label>
            <select name="kode_mk" id="kodeMkSelect" class="form-select" required>
              <option value="">-- Pilih Mata Kuliah --</option>
              <?php foreach ($mata_kuliah as $mk): ?>
                <option value="<?= esc($mk['kode_mk']) ?>"><?= esc($mk['nama_mk']) ?> (<?= esc($mk['kode_mk']) ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Dropdown Program Magang -->
            <div class="mb-3">
                <label class="form-label">Program Magang</label>
                <select name="id_program" class="form-select">
                    <option value="">-- Pilih Program --</option>
                    <?php foreach ($program_magang as $prog): ?>
                        <option value="<?= esc($prog['id_program']) ?>"><?= esc($prog['nama_program']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

          <!-- Dropdown Role -->
          <div class="mb-3 d-none" id="roleWrapper">
            <label for="roleSelect">Role Penilai</label>
            <select name="role" id="roleSelect" class="form-select" required>
              <option value="dospem">Dosen Pembimbing</option>
            </select>
          </div>

          <!-- Input Komponen -->
          <div class="mb-3">
            <div id="komponenContainer">
                <div class="row mb-2 komponen-row align-items-center">
                <div class="col-md-9">
                    <label class="form-label text-muted mb-1">Komponen</label>
                    <input type="text" name="komponen[]" class="form-control" placeholder="Contoh: Kehadiran, Laporan, Kedisiplinan..." required>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted mb-1">Presentase (%)</label>
                    <input type="number" name="presentase[]" class="form-control presentase-input text-center" placeholder="%" min="1" max="100" required>
                </div>
                </div>
            </div>
            </div>

            <!-- Total Presentase (label di luar col-md-3, field tetap di kanan, error mulai dari bawah label) -->
            <div class="row justify-content-end align-items-center mb-1">
            <!-- Label di luar kolom input -->
            <div class="col-auto d-flex align-items-center">
                <label for="totalPresentase" class="fw-bold mb-0">Total Presentase:</label>
            </div>

            <!-- Input total di kanan -->
            <div class="col-md-3">
                <input type="text" id="totalPresentase" class="form-control fw-bold text-center" value="0%" readonly>
            </div>
            </div>

            <!-- Pesan error di bawah label dan field (mulai dari bawah label) -->
            <div class="row justify-content-end">
            <div class="col-auto"></div>
            <div class="col-md-3 offset-md-1">
                <div id="errorTotal" class="text-danger fw-bold d-none mt-1">Total presentase harus 100%</div>
            </div>
            </div>



          <!-- Tombol Add & Hapus -->
          <div class="text-end mb-3">
            <button type="button" class="btn btn-danger btn-sm d-none" id="removeKomponen">
                <i class="bi bi-dash-circle"></i> Hapus
            </button>
            <button type="button" class="btn btn-secondary btn-sm" id="addKomponen">
                <i class="bi bi-plus-circle"></i> Add
            </button>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Batal
          </button>
          <button type="submit" id="btnSimpan" class="btn btn-primary" disabled>
            <i class="bi bi-save"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- === Modal Edit Tiap Kelompok (Tanpa AJAX) === -->
<?php foreach ($kelompok as $key => $group): 
    $kode = $group[0]['kode_mk'];
    $role = $group[0]['role'] ?? 'dospem';
    $modalId = 'modalEdit_' . $kode . '_' . $role;
    $totalPresentase = array_sum(array_column($group, 'presentase'));
?>
<div class="modal fade" id="modalEdit_<?= $group[0]['kode_mk'] ?>_<?= esc($group[0]['role']) ?>_<?= esc($group[0]['id_program']) ?>" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="<?= base_url('kaprodi/komponen_penilaian/updateGroup') ?>" method="post" class="formEditGroup">
        <input type="hidden" name="id_program" value="<?= esc($group[0]['id_program'] ?? '') ?>">
        <input type="hidden" name="kode_mk" value="<?= esc($group[0]['kode_mk']) ?>">
        <input type="hidden" name="role" value="<?= esc($group[0]['role'] ?? 'dospem') ?>">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title fw-bold text-white" id="<?= $modalId ?>Label">
            Edit Komponen Nilai – <?= esc($group[0]['nama_mk']) ?> (<?= strtoupper($role) ?>)
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- Program Magang (readonly) -->
          <div class="mb-3">
            <label class="form-label">Program Magang</label>
            <input type="text" class="form-control" 
                  value="<?= esc($group[0]['nama_program'] ?? '-') ?>" readonly>
          </div>
          <!-- Label sekali di atas -->
          <div class="row mb-1">
            <div class="col-md-9">
              <label class="form-label text-muted fw-bold">Komponen</label>
            </div>
            <div class="col-md-3">
              <label class="form-label text-muted fw-bold">Presentase (%)</label>
            </div>
          </div>

          <div class="komponenEditContainer">
            <?php foreach ($group as $row): ?>
                <div class="row mb-2 komponen-row align-items-center">
                <input type="hidden" name="id_nilai[]" value="<?= $row['id_nilai'] ?>">
                <div class="col-md-8">
                    <input type="text" name="komponen[]" class="form-control" 
                        value="<?= esc($row['komponen']) ?>" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="presentase[]" class="form-control text-center presentase-input" 
                        value="<?= esc($row['presentase']) ?>" min="1" max="100" required>
                </div>
                <div class="col-md-1 text-center">
                    <button type="button" class="btn btn-danger btn-sm btnRemoveRow">
                    <i class="bi bi-trash"></i>
                    </button>
                </div>
                </div>
            <?php endforeach; ?>
          </div>

          <!-- Total Presentase -->
          <div class="row justify-content-end align-items-center mb-1 mt-3">
            <div class="col-auto d-flex align-items-center">
              <label class="fw-bold mb-0">Total Presentase:</label>
            </div>
            <div class="col-md-3">
              <input type="text" class="form-control fw-bold text-center totalEdit" 
                     value="<?= $totalPresentase ?>%" readonly>
            </div>
          </div>

          <!-- Tombol Add & Hapus -->
          <div class="text-end mb-3">
            <button type="button" class="btn btn-danger btn-sm d-none btnRemoveEdit">
                <i class="bi bi-dash-circle"></i> Hapus
            </button>
            <button type="button" class="btn btn-secondary btn-sm btnAddEdit">
                <i class="bi bi-plus-circle"></i> Add
            </button>
          </div>

          <div class="row justify-content-end">
            <div class="col-auto"></div>
            <div class="col-md-3 offset-md-1">
              <div class="text-danger fw-bold d-none errorEdit mt-1">
                Total presentase harus 100%
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary btnSaveEdit">
            <i class="bi bi-save"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach; ?>



<!-- === SCRIPT === -->
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('komponenContainer');
    const addBtn = document.getElementById('addKomponen');
    const removeBtn = document.getElementById('removeKomponen');
    const totalField = document.getElementById('totalPresentase');
    const errorTotal = document.getElementById('errorTotal');
    const btnSimpan = document.getElementById('btnSimpan');
    const kodeMkSelect = document.getElementById('kodeMkSelect');
    const roleWrapper = document.getElementById('roleWrapper');
    const roleSelect = document.getElementById('roleSelect');
    const form = document.getElementById('formTambahKomponen');
    const modal = new bootstrap.Modal(document.getElementById('modalTambah'));

    // === Fungsi hitung total presentase ===
    function hitungTotalPresentase() {
        const inputs = container.querySelectorAll('.presentase-input');
        let total = 0;
        inputs.forEach(input => {
            const val = parseFloat(input.value) || 0;
            total += val;
        });

        totalField.value = total + '%';

        if (total === 100) {
            totalField.classList.remove('text-danger');
            totalField.classList.add('text-success');
            errorTotal.classList.add('d-none');
            btnSimpan.removeAttribute('disabled');
        } else {
            totalField.classList.add('text-danger');
            totalField.classList.remove('text-success');
            errorTotal.classList.remove('d-none');
            btnSimpan.setAttribute('disabled', true);
        }
    }

    // === Buat baris baru komponen ===
    function createNewRow() {
        const row = document.createElement('div');
        row.classList.add('row', 'mb-2', 'komponen-row', 'align-items-center');
        row.innerHTML = `
            <div class="col-md-9">
                <input type="text" name="komponen[]" class="form-control" placeholder="Komponen" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="presentase[]" class="form-control presentase-input text-center" placeholder="%" min="1" max="100" required>
            </div>`;
        
        // Tambah event input langsung saat baris baru dibuat
        row.querySelector('.presentase-input').addEventListener('input', hitungTotalPresentase);
        return row;
    }

    // === Tombol tambah komponen ===
    addBtn.addEventListener('click', function() {
        container.appendChild(createNewRow());
        updateRemoveButton();
        hitungTotalPresentase();
    });

    // === Tombol hapus komponen ===
    removeBtn.addEventListener('click', function() {
        const rows = container.querySelectorAll('.komponen-row');
        if (rows.length > 1) rows[rows.length - 1].remove();
        updateRemoveButton();
        hitungTotalPresentase();
    });

    // === Atur tombol hapus aktif / tidak ===
    function updateRemoveButton() {
        const rows = container.querySelectorAll('.komponen-row');
        rows.length > 1 ? removeBtn.classList.remove('d-none') : removeBtn.classList.add('d-none');
    }

    // === Event input untuk baris pertama ===
    container.querySelector('.presentase-input').addEventListener('input', hitungTotalPresentase);

    // === Role Penilai otomatis ===
    kodeMkSelect.addEventListener('change', function() {
        if (this.value === 'BB010') {
            roleWrapper.classList.remove('d-none');
            roleSelect.innerHTML = `
                <option value="">-- Pilih Role --</option>
                <option value="dospem">Dosen Pembimbing</option>
                <option value="mitra">Pembimbing Mitra</option>
                <option value="kaprodi">Kepala Program Studi</option>`;
            roleSelect.removeAttribute('disabled');
        } else if (this.value !== '') {
            roleWrapper.classList.remove('d-none');
            roleSelect.innerHTML = `<option value="dospem">Dosen Pembimbing</option>`;
            roleSelect.setAttribute('disabled', true);
        } else {
            roleWrapper.classList.add('d-none');
        }
    });

    // === Submit form dengan validasi ===
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const total = parseInt(totalField.value);
        if (total !== 100) {
            Swal.fire({ icon: 'warning', title: 'Total Belum 100%', text: 'Pastikan total presentase = 100%.' });
            return;
        }

        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message, timer: 1500, showConfirmButton: false });
                    form.reset();
                    container.innerHTML = createNewRow().outerHTML;
                    updateRemoveButton();
                    hitungTotalPresentase();
                    modal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
                }
            })
            .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan saat menyimpan.' }));
    });
});

// === Script untuk setiap Modal Edit ===
document.querySelectorAll('.formEditGroup').forEach(form => {
  const container = form.querySelector('.komponenEditContainer');
  const addBtn = form.querySelector('.btnAddEdit');
  const removeBtn = form.querySelector('.btnRemoveEdit');
  const totalField = form.querySelector('.totalEdit');
  const errorMsg = form.querySelector('.errorEdit');
  const saveBtn = form.querySelector('.btnSaveEdit');

  // Fungsi hitung total
  function hitungTotal() {
    let total = 0;
    form.querySelectorAll('.presentase-input').forEach(inp => {
      total += parseFloat(inp.value) || 0;
    });
    totalField.value = total + '%';
    if (total === 100) {
      totalField.classList.add('text-success');
      totalField.classList.remove('text-danger');
      errorMsg.classList.add('d-none');
      saveBtn.removeAttribute('disabled');
    } else {
      totalField.classList.add('text-danger');
      totalField.classList.remove('text-success');
      errorMsg.classList.remove('d-none');
      saveBtn.setAttribute('disabled', true);
    }
  }

  // Tambah baris baru
  function createRow() {
    const row = document.createElement('div');
    row.className = 'row mb-2 komponen-row align-items-center';
    row.innerHTML = `
      <input type="hidden" name="id_nilai[]" value="">
      <div class="col-md-8">
        <input type="text" name="komponen[]" class="form-control" placeholder="Komponen" required>
      </div>
      <div class="col-md-3">
        <input type="number" name="presentase[]" class="form-control text-center presentase-input" placeholder="%" min="1" max="100" required>
      </div>
      <div class="col-md-1 text-center">
        <button type="button" class="btn btn-danger btn-sm btnRemoveRow">
          <i class="bi bi-trash"></i>
        </button>
      </div>
    `;
    row.querySelector('.presentase-input').addEventListener('input', hitungTotal);
    row.querySelector('.btnRemoveRow').addEventListener('click', () => {
      row.remove();
      hitungTotal();
    });
    return row;
  }

  addBtn.addEventListener('click', () => {
    container.appendChild(createRow());
    hitungTotal();
  });

  container.querySelectorAll('.btnRemoveRow').forEach(btn => {
    btn.addEventListener('click', e => {
      e.target.closest('.komponen-row').remove();
      hitungTotal();
    });
  });

  container.querySelectorAll('.presentase-input').forEach(inp => {
    inp.addEventListener('input', hitungTotal);
  });

  hitungTotal();
});

</script>

<?= $this->endSection() ?>
