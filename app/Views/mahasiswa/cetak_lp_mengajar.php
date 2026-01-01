<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Learning Plan IWIMA Mengajar</title>

    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 13px; /* seluruh isi dokumen 12px */
        margin: 25px;
    }

    .header-wrapper {
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;    /* sejajarkan logo & teks secara vertikal */
    }

    .header-logo {
        width: 70px;            /* ukuran logo */
        height: auto;
        display: block;
    }

    .header-text {
        flex: 1;                /* agar teks memanjang ke kanan */
        margin-left: 15px;      /* jarak dari logo */
        font-size: 14px;
        line-height: 1.3;
    }

    .line {
        width: 100%;
        border-bottom: 1px solid #000;
        margin-top: 3px;
        margin-bottom: 10px;
    }

    h2 {
        text-align: center;
        margin: 0;
        font-size: 13px; /* heading 14px */
        font-weight: bold;
    }

    .form-code {
        text-align: right;
        font-size: 13px;
        margin-top: -10px;
        margin-bottom: 5px;
    }

    .section-title {
        font-weight: bold;
        margin-top: 15px;
        margin-bottom: 5px;
        font-size: 13px; /* heading 14px */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px; /* isi tabel 12px */
    }

    table td,
    table th {
        border: 1px solid #000;
        padding: 6px;
        vertical-align: top;
        font-size: 13px; /* isi tabel 12px */
    }

    table th {
        font-size: 13px; /* heading tabel 14px */
        font-weight: bold;
        text-align: center;
    }

    .text-center {
        text-align: center;
    }

    .no-break {
        page-break-inside: avoid;
        break-inside: avoid;
    }
    .no-border,
    .no-border td,
    .no-border th {
        border: none !important;
    }
</style>


</head>

<body>

<!-- ======================= -->
<!--        HEADER           -->
<!-- ======================= -->
<div class="header-wrapper">
    <table class="no-border" style="width:100%;">
        <tr>
            <!-- KOLOM LOGO -->
            <td style="width: 80px; vertical-align: top; text-align:left;">
                <img src="<?= $logoBase64 ?>" style="width:70px; height:auto;">
            </td>

            <!-- KOLOM TEKS HEADER -->
            <td style="vertical-align: middle; text-align:left;">
                <span style="font-size:16px; font-weight:bold;">Fakultas Teknologi Informasi</span><br>
                Institut Widya Pratama<br>
                Jl. Patriot No 25 Pekalongan
            </td>
        </tr>
    </table>
</div>

<div class="line"></div>

<div class="form-code">
    Form FM-01.02.39
</div>

<h2>LEARNING PLAN / RENCANA KEGIATAN IWIMA MENGAJAR SKEMA TEACHING ASISTENT</h2>


<!-- ======================= -->
<!--   INFORMASI MAHASISWA   -->
<!-- ======================= -->
<div class="section-title">Informasi Mahasiswa</div>
<table>
    <tr>
    <td style="width: 25%; text-align: right; font-weight: bold;">NIM:</td>
        <td><?= esc($profil['nim']) ?></td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold;">Nama:</td>
        <td><?= esc($profil['nama_lengkap']) ?></td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold;">Email:</td>
        <td><?= esc($profil['email']) ?></td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold;">Nomor Telp/Hp:</td>
        <td><?= esc($profil['handphone']) ?></td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold;">Alamat:</td>
        <td><?= esc($profil['alamat']) ?></td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold;">Fakultas:</td>
        <td><?= esc($profil['fakultas']) ?></td>
    </tr>
    <?php
        $daftarProdi = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Manajemen Informatika',
            'Komputerisasi Akuntansi'
        ];

        $prodiDipilih = $profil['program_studi']; // Prodi dari database
        ?>

        <tr>
            <td style="text-align: right; font-weight: bold;">Program Studi:</td>
            <td>
                <?php foreach ($daftarProdi as $prodi): ?>
                    <?= ($prodi == $prodiDipilih ? '•' : 'o') . ' ' . $prodi ?><br>
                <?php endforeach; ?>
            </td>
        </tr>
    <tr>
        <td style="text-align: right; font-weight: bold;">Semester:</td>
        <td><?= esc($profil['semester']) ?></td>
    </tr>
</table>


<!-- ======================= -->
<!--   Satuan Pendidikan  -->
<!-- ======================= -->
<div class="section-title" style="page-break-before: always;">Satuan Pendidikan</div>
<table>
    <tr>
        <td style="width: 25%; text-align: right; font-weight: bold;">Nama Sekolah:</td>
        <td><?= esc($profil['nama_mitra'] ?? '-') ?></td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold;">Nama Penanggung Jawab:</td>
        <td><?= esc($profil['nama_pembimbing'] ?? '-') ?></td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold;">Email:</td>
        <td><?= esc($profil['unit_email'] ?? '-') ?></td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold;">Nomor Telp/Hp:</td>
        <td><?= esc($profil['unit_no_hp'] ?? '-') ?></td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold;">Alamat:</td>
        <td><?= esc($profil['mitra_alamat'] ?? '-') ?></td>
    </tr>
</table>


<!-- ======================= -->
<!--     INFORMASI MAGANG    -->
<!-- ======================= -->
<div class="section-title">Informasi TEACHING ASISTENT</div>
<table>
    <tr>
        <td style="width: 25%; text-align: right; font-weight: bold;">Tanggal Mulai:</td>
        <td><?= esc($profil['tanggal_mulai'] ?? '-') ?></td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold;">Tanggal Akhir:</td>
        <td><?= esc($profil['tanggal_selesai'] ?? '-') ?></td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold;">Jam Kerja (Hari dan Waktu):</td>
        <td><?= nl2br(esc($jamKerja ?? ($profil['keterangan'] ?? '-'))) ?></td>
    </tr>
</table>

<!-- ======================= -->
<!--   A. RENCANA KEGIATAN   -->
<!-- ======================= -->
<div class="section-title" style="page-break-before: always;">A. Rencana Kegiatan Pembelajaran yang Akan Dilakukan</div>

<table border="1" cellspacing="0" cellpadding="5" width="100%" style="border-collapse: collapse;">

    <!-- HEADER -->
    <tr>
        <th style="width:5%; text-align:center; vertical-align:middle; background-color:#d9eaff; padding:15px 5px;">No</th>
        <th style="width:15%; text-align:center; vertical-align:middle; background-color:#d9eaff; padding:15px 5px;">Nama Kegiatan / Program Kerja</th>
        <th style="width:10%; text-align:center; vertical-align:middle; background-color:#d9eaff; padding:15px 5px;">Pelaksana Kegiatan<br>(Individu/Kelompok)</th>
        <th style="width:30%; text-align:center; vertical-align:middle; background-color:#d9eaff; padding:15px 5px;">Uraian Kegiatan / Program Kerja</th>
        <th style="width:15%; text-align:center; vertical-align:middle; background-color:#d9eaff; padding:15px 5px;">Metode & Media Yang Digunakan</th>
        <th style="width:25%; text-align:center; vertical-align:middle; background-color:#d9eaff; padding:15px 5px;">Rencana Tindak Lanjut (RTL)</th>
    </tr>

    <!-- ROW UTAMA -->
    <tr>
        <td style="text-align:center;">1</td>
        <td style="text-align:center;"><?= nl2br(esc($lp['nama_kegiatan'] ?? '-')) ?></td>
        <td style="text-align:center;"><?= nl2br(esc($lp['pelaksana_kegiatan'] ?? '-')) ?></td>
        <td><?= nl2br(esc($lp['uraian_kegiatan'] ?? '-')) ?></td>
        <td><?= nl2br(esc($lp['metode_media'] ?? '-')) ?></td>
        <td><?= nl2br(esc($lp['rtl_kegiatan'] ?? '-')) ?></td>
    </tr>

</table>

<!-- ======== JARAK ======== -->
<div style="height:20px;"></div>

<!-- ======================= -->
<!--          B. STAR        -->
<!-- ======================= -->
<div class="section-title" style="page-break-before: always;"></div>
<table border="1" cellspacing="0" cellpadding="6" width="100%" style="border-collapse: collapse;">

    <!-- SITUATION -->
    <tr>
        <th colspan="6" style="text-align:left; background-color:#d9eaff;">
            <i>Situation</i> (Situasi)<br>
            <span style="font-weight: normal;">
                Silakan tuliskan satu situasi yang ada di satuan pendidikan tersebut
            </span>
        </th>
    </tr>
    <tr>
        <td colspan="6" style="text-align:justify;">
            <?= nl2br(esc($lp['situation'] ?? '-')) ?>
        </td>
    </tr>

    <!-- TASK -->
    <tr>
        <th colspan="6" style="text-align:left; background-color:#d9eaff;">
            <i>Task</i> (Tugas)<br>
            <span style="font-weight: normal;">
                Silakan ceritakan peran yang dilakukan pada situasi tersebut!
            </span>
        </th>
    </tr>
    <tr>
        <td colspan="6" style="text-align:justify;">
            <?= nl2br(esc($lp['task'] ?? '-')) ?>
        </td>
    </tr>

    <!-- ACTION -->
    <tr>
        <th colspan="6" style="text-align:left; background-color:#d9eaff;">
            <i>Action</i> (Aksi)<br>
            <span style="font-weight: normal;">
                Silakan ceritakan strategi dan tindakan yang dilakukan untuk menghadapi situasi tersebut!
            </span>
        </th>
    </tr>
    <tr>
        <td colspan="6" style="text-align:justify;">
            <?= nl2br(esc($lp['action'] ?? '-')) ?>
        </td>
    </tr>

    <!-- RESULT -->
    <tr>
        <th colspan="6" style="text-align:left; background-color:#d9eaff;">
            <i>Result</i> (Hasil)<br>
            <span style="font-weight: normal;">
                Silakan ceritakan hasil yang ingin didapatkan setelah menjalankan peran, strategi, dan tindakan pada situasi tersebut!
            </span>
        </th>
    </tr>
    <tr>
        <td colspan="6" style="text-align:justify;">
            <?= nl2br(esc($lp['result'] ?? '-')) ?>
        </td>
    </tr>

</table>


<!-- ======================= -->
<!-- CAPAIAN PEMBELAJARAN MAGANG -->
<!-- ======================= -->
<div class="section-title" style="page-break-before: always;">
    <b>Capaian Pembelajaran Mata Kuliah Magang (IWIMA Mengajar Skema Teaching Asistent)</b>
</div>

<!-- Menampilkan capaian_magang dari database -->
<div style="margin-top: 12px; text-align:justify;">
    <?= nl2br(esc($lp['capaian_magang'] ?? '-')) ?>
</div>

<!-- ======================= -->
<!--   AKTIFITAS PEMBELAJARAN MAGANG -->
<!-- ======================= -->
<div class="section-title" style="margin-top: 15px;">
    <b>Aktifitas Pembelajaran Magang</b>
</div>

<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <tr>
        <th style="width:5%; text-align:center;">No</th>
        <th style="width:20%; text-align:center;">Kompetensi</th>
        <th style="width:55%;">Kompetensi Teknis</th>
    </tr>

    <?php
    // Hitung jumlah baris per kompetensi
    $countKompetensi = [];
    foreach ($aktivitasMagang as $item) {
        $k = $item['kompetensi'];
        if (!isset($countKompetensi[$k])) {
            $countKompetensi[$k] = 0;
        }
        $countKompetensi[$k]++;
    }

    $printed = [];
    $no = 1;
    ?>

    <?php foreach ($aktivitasMagang as $a): 
        $kompetensi = $a['kompetensi'];
    ?>
        <tr>

            <!-- MERGE KOLOM NO (rowspan sama dengan kompetensi) -->
            <?php if (!isset($printed["no_$kompetensi"])): ?>
                <td rowspan="<?= $countKompetensi[$kompetensi] ?>" 
                    style="text-align:center;">
                    <?= $no++; ?>
                </td>
                <?php $printed["no_$kompetensi"] = true; ?>
            <?php endif; ?>

            <!-- MERGE KOLOM KOMPETENSI -->
            <?php if (!isset($printed[$kompetensi])): ?>
                <td rowspan="<?= $countKompetensi[$kompetensi] ?>" 
                    style="text-align:center;">
                    <?= esc($kompetensi) ?>
                </td>
                <?php $printed[$kompetensi] = true; ?>
            <?php endif; ?>

            <!-- Kolom Rincian Kompetensi Teknis -->
            <td>
                <?= nl2br(esc($a['kompetensi_teknis'])) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<br>

<!-- ======================= -->
<!--        TANDA TANGAN      -->
<!-- ======================= -->
<div class="section-title">
    Kesepakatan:
</div>

<div style="margin-top: 15px; font-size: 14px;">

    <!-- Mahasiswa -->
    <div style="margin-bottom: 20px;">
        1. Mahasiswa, 
        <span style="display:inline-block; width: 556px; border-bottom:1px solid #000; margin-left:10px;"></span>
        <span style="margin-left:20px;">Tanggal</span>
        <span style="display:inline-block; width:150px; border-bottom:1px solid #000; margin-left:10px;"></span>
    </div>

    <!-- Perusahaan -->
    <div style="margin-bottom: 20px;">
        2. Perusahaan, 
        <span style="display:inline-block; width: 550px; border-bottom:1px solid #000; margin-left:10px;"></span>
        <span style="margin-left:20px;">Tanggal</span>
        <span style="display:inline-block; width:150px; border-bottom:1px solid #000; margin-left:10px;"></span>
    </div>

    <!-- Program Studi -->
    <div style="margin-bottom: 20px;">
        3. Program Studi, 
        <span style="display:inline-block; width: 533px; border-bottom:1px solid #000; margin-left:10px;"></span>
        <span style="margin-left:20px;">Tanggal</span>
        <span style="display:inline-block; width:150px; border-bottom:1px solid #000; margin-left:10px;"></span>
    </div>

</div>


</body>
</html>
