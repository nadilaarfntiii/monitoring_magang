<?= $this->extend('layouts/kaprodi') ?>

<?= $this->section('title') ?>
Profil Akun
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">

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

<div class="page-heading mb-3">
    <h3 class="fw-bold">Profil Akun</h3>
    <p class="text-muted">Kelola informasi akun anda.</p>
</div>

<div class="page-content">

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger shadow-sm"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card card-custom p-4">

        <form action="<?= base_url('kaprodi/update_profil') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">

            <div class="row">

                <!-- FOTO PROFIL -->
                <div class="col-md-4 text-center border-end">

                    <?php if (!empty($user['foto'])) : ?>
                        <img id="previewFoto" src="<?= base_url('uploads/foto/' . $user['foto']) ?>" class="profile-img mb-3">
                    <?php else : ?>
                        <img id="previewFoto" src="<?= base_url('assets/images/pp.jpg') ?>" class="profile-img mb-3">
                    <?php endif; ?>

                    <div class="mt-2 px-4">
                        <input type="file" id="fotoInput" name="foto" accept="image/*" class="form-control">
                        <small class="text-muted d-block mt-1">Format JPG/PNG, Maks 2MB</small>
                    </div>
                </div>

                <!-- FORM -->
                <div class="col-md-8 p-4">

                <div class="row">

                    <!-- Nama Lengkap -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control"
                            value="<?= $dosen['nama_lengkap'] ?? '' ?>" readonly>
                    </div>

                    <!-- NPPY -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NPPY</label>
                        <input type="text" name="nppy" class="form-control"
                            value="<?= $dosen['nppy'] ?? '' ?>" readonly>
                    </div>

                    <!-- Pendidikan Terakhir -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan_terakhir" class="form-control"
                            value="<?= $dosen['pendidikan_terakhir'] ?? '' ?>" readonly>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="L" <?= ($dosen['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= ($dosen['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>

                    <!-- Tempat Lahir -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control"
                            value="<?= $dosen['tempat_lahir'] ?? '' ?>">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control"
                            value="<?= $dosen['tanggal_lahir'] ?? '' ?>">
                    </div>

                    <!-- Alamat -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control"
                            value="<?= $dosen['alamat'] ?? '' ?>">
                    </div>


                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= $dosen['email'] ?? '' ?>">
                    </div>

                    <!-- Nomor HP -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="no_hp" class="form-control"
                            value="<?= $dosen['no_hp'] ?? '' ?>">
                    </div>

                    <!-- Status Dosen -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status Dosen</label>
                        <input type="text" name="status_dosen" class="form-control"
                            value="<?= $dosen['status_dosen'] ?? '' ?>"readonly>
                    </div>

                    <!-- Jabatan Fungsional -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jabatan Fungsional</label>
                        <input type="text" name="jabatan_fungsional" class="form-control"
                            value="<?= $dosen['jabatan_fungsional'] ?? '' ?>"readonly>
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

</div>

<!-- SCRIPT PREVIEW FOTO -->
<script>
document.getElementById('fotoInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('previewFoto');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?= $this->endSection() ?>
