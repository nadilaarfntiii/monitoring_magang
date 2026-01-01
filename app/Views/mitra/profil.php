<?= $this->extend('layouts/mitra') ?>

<?= $this->section('title') ?>
Profil Akun Pembimbing Mitra
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
.card-custom {
    border-radius: 0.8rem;
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.profile-img {
    width: 130px;
    height: 130px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #e5e7eb;
}
.save-btn {
    background: linear-gradient(90deg, #0d6efd, #3b82f6);
    border: none;
    color: white;
    padding: 10px 26px;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all .3s ease;
}
.save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.15);
}
</style>

<!-- Konten / Isi Halaman -->
<div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3 class="fw-bold">Profil Akun</h3>
                            <p class="text-muted">Kelola informasi akun anda.</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('mitra/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Profil Akun</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="card card-custom p-4">

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('mitra/update_profil') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <input type="hidden" name="id_unit" value="<?= $unit['id_unit'] ?>">

        <div class="row">

            <!-- FOTO PROFIL -->
            <div class="col-md-4 text-center border-end">

                <?php if (!empty($user['foto'])) : ?>
                    <img id="previewFoto"
                         src="<?= base_url('uploads/foto/' . $user['foto']) ?>"
                         class="profile-img mb-3">
                <?php else : ?>
                    <img id="previewFoto"
                         src="<?= base_url('assets/images/pp.jpg') ?>"
                         class="profile-img mb-3">
                <?php endif; ?>

                <div class="mt-2 px-4">
                    <input type="file"
                           id="fotoInput"
                           name="foto"
                           accept="image/*"
                           class="form-control">
                    <small class="text-muted d-block mt-1">
                        Format JPG/PNG, Maks 2MB
                    </small>
                </div>
            </div>

            <!-- FORM -->
            <div class="col-md-8 p-4">

                <div class="row">

                    <!-- Informasi Mitra -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Perusahaan</label>
                        <input type="text" class="form-control"
                               value="<?= $mitra['nama_mitra'] ?>" readonly>
                    </div>

                    <!-- Alamat Mitra -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Alamat Perusahaan</label>
                        <input type="text"
                            name="alamat"
                            class="form-control"
                            value="<?= $mitra['alamat'] ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Unit</label>
                        <input type="text" class="form-control"
                               value="<?= $unit['nama_unit'] ?>" readonly>
                    </div>

                    <!-- Data Pembimbing -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Pembimbing Perusahaan</label>
                        <input type="text"
                               name="nama_pembimbing"
                               class="form-control"
                               value="<?= $unit['nama_pembimbing'] ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text"
                               name="jabatan"
                               class="form-control"
                               value="<?= $unit['jabatan'] ?>">
                    </div>

                    <!-- Kontak -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Pembimbing</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="<?= $unit['email'] ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nomor HP Pembimbing</label>
                        <input type="text"
                               name="no_hp"
                               class="form-control"
                               value="<?= $unit['no_hp'] ?>">
                    </div>

                    <!-- Username (Tetap 1 kolom penuh) -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control"
                            value="<?= $user['username'] ?>" required>
                    </div>

                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="save-btn d-inline-flex align-items-center gap-2">
                        <i class="iconly-boldUpload"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>

            </div>

        </div>

    </form>

</div>

<!-- SCRIPT PREVIEW FOTO -->
<script>
document.getElementById('fotoInput').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById('previewFoto');

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?= $this->endSection() ?>
