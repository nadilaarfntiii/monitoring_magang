<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Learning Plan Magang</title>

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

<h2>LEARNING PLAN / RENCANA KEGIATAN MAGANG (INTERNSHIP)</h2>


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
<!--   INFORMASI PERUSAHAAN  -->
<!-- ======================= -->
<div class="section-title">Informasi Perusahaan</div>
<table>
    <tr>
        <td style="width: 25%; text-align: right; font-weight: bold;">Nama Perusahaan/Instansi:</td>
        <td><?= esc($profil['nama_mitra'] ?? '-') ?></td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold;">Nama Penanggung Jawab:</td>
        <td><?= esc($profil['nama_pembimbing'] ?? '-') ?></td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold;">Bagian / Unit / Departement:</td>
        <td><?= esc($profil['nama_unit'] ?? '-') ?></td>
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
<div class="section-title">Informasi Magang (Internship)</div>
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
<!--   DESKRIPSI PEKERJAAN   -->
<!-- ======================= -->
<div class="section-title">Deskripsi Pekerjaan</div>
<table>
    <tr>
        <td style="width: 25%; 
            text-align: justify; 
            text-align-last: left; 
            line-height: 1.4; 
            font-weight: bold;
            vertical-align: middle;
            ">
            Deskripsi Posisi Pekerjaan dan Uraian Pekerjaan selama Magang.
        </td>
        <td><?= nl2br(esc($lp['deskripsi_pekerjaan'] ?? '-')) ?></td>
    </tr>
</table>

<!-- ======================= -->
<!--   CAPAIAN MAGANG        -->
<!-- ======================= -->
<div class="section-title" style="page-break-before: always;">Capaian Pembelajaran Magang</div>

<div style="text-align: justify; line-height: 1.5; margin-bottom: 15px; ">
    <?= nl2br(esc($lp['capaian_magang'])) ?>
</div>


<!-- ======================= -->
<!--   AKTIVITAS MAGANG      -->
<!-- ======================= -->
<div class="section-title">Aktivitas Pembelajaran Magang</div>

<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <tr>
        <th colspan="3" style="text-align:center; font-weight:bold;">
            Aktivitas Pembelajaran Magang
        </th>
    </tr>

    <tr>
        <th style="width:5%; text-align:center;">No</th>
        <th style="width:70%;">Kompetensi Teknis</th>
        <th style="width:30%; text-align:center;">Pengalaman</th>
    </tr>

    <?php
    $no = 1;
    foreach ($aktivitasMagang as $a):
        $checked = ($a['dicentang'] == 1) ? '✔' : '✘';
    ?>
        <tr>
            <td style="text-align:center;"><?= $no++; ?></td>
            <td><?= esc($a['kompetensi']) ?></td>
            <td style="text-align:center;"><?= $checked ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<div style="margin-top: 10px; font-size: 13px; line-height: 1.4; text-align: justify;">
    *** Kompetensi teknis yang diperoleh selama kegiatan magang dan yang relevan dengan kompetensi lulusan Program Studi yang dinyatakan dalam dokumen kurikulum. Objek Magang (Perusahaan/Instansi) <b>HARUS MENYEDIAKAN</b> kompetensi teknis tersebut.<br>
    *** Berikan tanda centang (√) untuk satu atau lebih kompetensi teknis yang diperoleh selama kegiatan magang.
</div>

<!-- ======================= -->
<!--  CAPAIAN MATA KULIAH    -->
<!-- ======================= -->
<div class="section-title" style="page-break-before: always;">
    Capaian Pembelajaran Mata Kuliah
</div>

<div style="margin-top: 10px;">
    <?= nl2br(esc($lp['capaian_mata_kuliah'] ?? '-')) ?>
</div>

<div class="section-title">Aktifitas Pembelajaran Mata Kuliah</div>

<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <tr>
        <th colspan="3" style="text-align:center; font-weight:bold;">
            Aktivitas Pembelajaran Mata Kuliah
        </th>
    </tr>

    <tr>
        <th style="width:5%; text-align:center;">No</th>
        <th style="width:70%;">Kompetensi Teknis</th>
        <th style="width:30%; text-align:center;">Keterkaitan</th>
    </tr>

    <?php
    $no = 1;

    foreach ($aktivitasMK as $a):
        // hanya ambil yang bertipe Mata Kuliah
        if ($a['tipe'] !== 'Mata Kuliah') continue;

        $checkedMK = ($a['dicentang'] == 1) ? '✔' : '✘';
    ?>
        <tr>
            <td style="text-align:center;"><?= $no++; ?></td>
            <td><?= esc($a['kompetensi']) ?></td>
            <td style="text-align:center;"><?= $checkedMK ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<div style="margin-top: 10px; font-size: 13px; line-height: 1.4; text-align: justify;">
    *** Kompetensi teknis yang diperoleh selama kegiatan magang dan yang relevan dengan kompetensi lulusan Program Studi yang dinyatakan dalam dokumen kurikulum.<br>
    *** Memberikan tanda centang (√) apabila ada keterkaitan dengan mata kuliah sebelumnya.
</div>


<!-- ======================= -->
<!--        TANDA TANGAN      -->
<!-- ======================= -->
<div class="section-title" style="page-break-before: always;">
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
