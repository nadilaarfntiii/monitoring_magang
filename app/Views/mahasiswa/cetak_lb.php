<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Logbook</title>
<style>
    body { 
        font-family: "Times New Roman", Times, serif;
        font-size: 16px;
    }

    .center { text-align:center; }

    .page-break { page-break-after: always; }

    table { 
        width:100%; 
        border-collapse:collapse; 
        font-family: "Times New Roman", Times, serif;
    }

    th, td { 
        border:1px solid #000; 
        padding:6px; 
        vertical-align:top; 
        font-family: "Times New Roman", Times, serif;
        font-size: 16px;
    }

    th { 
        font-weight:bold;
        vertical-align: middle;
    }

    .foto {
        width:110px;
        height:140px;
        border:1px solid #000;
        object-fit:cover;
    }

    .judul-utama {
        font-size:22px;
        font-weight:bold;
        margin-top:150px;
        line-height:1.5;
        font-family: "Times New Roman", Times, serif;
    }

    .judul-section {
        font-size:18px;
        font-weight:bold;
        text-align:center;
        margin-bottom:10px;
        font-family: "Times New Roman", Times, serif;
    }
</style>

</head>
<body>

<!-- ======================== -->
<!--        PAGE 1 COVER      -->
<!-- ======================== -->

<!-- Kotak kode FM kanan atas -->
<div style="
    width:180px; 
    border:1px solid #000; 
    padding:5px; 
    text-align:center; 
    float:right;
    font-size:16px;
">
    FM-01.02.40-R0
</div>

<div style="clear:both;"></div>

<!-- Judul Tengah -->
<div class="center" style="font-size:20px; font-weight:bold; margin-top:50px; line-height:1.5;">
    LOG BOOK <br>
    <span>PROGRAM MAGANG <i>(INTERNSHIP PROJECT)</i></span>
</div>

<br><br><br>

<!-- Logo Tengah -->
<?php if (!empty($logoBase64)): ?>
<div class="center">
    <img src="<?= $logoBase64 ?>" style="width:7cm; height:7cm; object-fit:contain;">
</div>
<?php endif; ?>

<br><br><br> <!-- Spasi 3 baris -->

<!-- Tabel Perusahaan -->
<table style="width:85%; margin:auto; margin-left:10%; border:none; border-collapse:collapse; font-size:14px; line-height:1.5; font-weight:bold;">
    <tr>
        <td style="width:27%; border:none;">Nama Perusahaan</td>
        <td style="border:none;">: <?= $profil['nama_mitra'] ?></td>
    </tr>
    <tr>
        <td style="border:none;">Alamat Perusahaan</td>
        <td style="border:none;">: <?= $profil['mitra_alamat'] ?></td>
    </tr>
</table>

<br><br><br><br><br><br><br><br>

<div class="center" style="font-size:20px; line-height:1.6;">
    <b>FAKULTAS TEKNOLOGI INFORMASI</b><br>
    <b>PROGRAM STUDI SISTEM INFORMASI</b><br>
    <b>INSTITUT WIDYA PRATAMA</b><br>
    <b>PEKALONGAN, <?= date('Y') ?></b>
</div>

<div class="page-break"></div>


<!-- ======================== -->
<!--    PAGE 2 DATA DIRI      -->
<!-- ======================== -->

<h3 class="judul-section" style="margin-bottom:15px; margin-top:50px;">Data Pribadi Mahasiswa</h3>

<!-- TABEL DATA MAHASISWA -->
<table style="width:90%; border:none; margin-left:8%; border-collapse:collapse; font-size:14px; line-height:1.8;">
    <tr>
        <td style="width:25%; border:none;">NIM</td>
        <td style="width:3%; border:none;">:</td>
        <td style="border:none; border-bottom:1px solid #000; padding-bottom:4px;"><?= $profil['nim'] ?></td>
    </tr>
    <tr>
        <td style="border:none;">Nama Mahasiswa</td>
        <td style="border:none;">:</td>
        <td style="border:none; border-bottom:1px solid #000; padding-bottom:4px;"><?= strtoupper($profil['nama_lengkap']) ?></td>
    </tr>
    <tr>
        <td style="border:none;">Program Studi</td>
        <td style="border:none;">:</td>
        <td style="border:none; border-bottom:1px solid #000; padding-bottom:4px;"><?= strtoupper($profil['nama_program']) ?></td>
    </tr>
    <tr>
        <td style="vertical-align:top; border:none;">Alamat</td>
        <td style="vertical-align:top; border:none;">:</td>
        <td style="border:none; border-bottom:1px solid #000; padding-bottom:4px;"><?= nl2br($profil['alamat']) ?></td>
    </tr>
    <tr>
        <td style="border:none;">No. HP</td>
        <td style="border:none;">:</td>
        <td style="border:none; border-bottom:1px solid #000; padding-bottom:4px;"><?= $profil['handphone'] ?></td>
    </tr>
    <tr>
        <td style="vertical-align:top; border:none;">Judul</td>
        <td style="vertical-align:top; border:none;">:</td>
        <td style="border:none; border-bottom:1px solid #000; padding-bottom:4px;"><?= strtoupper($judulTA) ?></td>
    </tr>
</table>


<br><br><br><br> <!-- SPASI 2 KALI -->

<!-- FOTO + TANDA TANGAN -->
<table style="width:90%; border:none; margin-left:8%; margin-top:10px; font-size:14px;">
    <tr>
        <!-- FOTO -->
        <td style="width:40%; border:none; display:flex; justify-content:center;">
            <div style="
                width: 3.81cm;
                height: 5.59cm;
                border: 1px solid #999;
                display:flex;
                justify-content:center;
                align-items:center;
                flex-direction:column;
                font-size:12px;
                color:#6e6e6e;
                text-align:center;
            ">
                Photo Mahasiswa
            </div>
        </td>

        <!-- TANDA TANGAN -->
        <td style="text-align:center; vertical-align:top; border:none;">
            <div style="margin-bottom:60px;">
                Mahasiswa,
            </div>

            <br><br><br><br><br><!-- ruang tanda tangan -->

            <div style="font-weight:bold; text-decoration:underline;">
                <?= strtoupper($profil['nama_lengkap']) ?>
            </div>
        </td>
    </tr>
</table>

<div class="page-break"></div>

<!-- ======================== -->
<!--      PAGE 3 LOGBOOK      -->
<!-- ======================== -->
<h3 class="judul-section" style="margin-top:50px;">CATATAN AKTIVITAS DI PERUSAHAAN</h3>

<br>

<table>
    <thead>
        <tr>
            <th style="width:5%;">No</th>
            <th style="width:12%;">Tanggal</th>
            <th style="width:12%;">Jam Masuk</th>
            <th style="width:12%;">Jam Pulang</th>
            <th style="width:45%;">Aktivitas</th>
            <th style="width:14%;">Paraf Pembimbing</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($logbooks as $lb): ?>
        <tr>
            <td style="text-align:center; vertical-align:middle;"><?= $no++ ?>.</td>
            <td style="text-align:center; vertical-align:middle;"><?= date('d/m/Y', strtotime($lb['tanggal'])) ?></td>
            <td style="text-align:center; vertical-align:middle;"><?= $lb['jam_masuk'] ?></td>
            <td style="text-align:center; vertical-align:middle;"><?= $lb['jam_pulang'] ?></td>
            <td style="text-align:justify; vertical-align:middle;"><?= nl2br($lb['catatan_aktivitas']) ?></td>
            <td></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
