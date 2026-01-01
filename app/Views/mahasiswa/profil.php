<?= $this->extend('layouts/mhs') ?>

<?= $this->section('title') ?>
Profil Akun Mahasiswa
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
                                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Profil Akun</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

<div class="page-content">

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card card-custom p-4">
        <form action="<?= base_url('mahasiswa/update_profil') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="row">

                <!-- FOTO -->
                <?php
                // Prioritas foto:
                // 1. Foto mahasiswa
                // 2. Foto user
                // 3. Foto default
                if (!empty($mahasiswa['foto'])) {
                    $fotoProfil = base_url('uploads/foto/' . $mahasiswa['foto']);
                } elseif (!empty($user['foto'])) {
                    $fotoProfil = base_url('uploads/foto/' . $user['foto']);
                } else {
                    $fotoProfil = base_url('assets/images/pp.jpg');
                }
                ?>

                <div class="col-md-4 text-center border-end">
                    <img id="previewFoto" src="<?= $fotoProfil ?>" class="profile-img mb-3">

                    <div class="px-4">
                        <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/*">
                        <small class="text-muted">JPG / PNG • Maks 2MB</small>
                    </div>
                </div>


                <!-- FORM -->
                <div class="col-md-8 p-4">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIM</label>
                            <input type="text" class="form-control" value="<?= $mahasiswa['nim'] ?>" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control"
                                   value="<?= $mahasiswa['nama_lengkap'] ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fakultas</label>
                            <input type="text" class="form-control"
                                   value="<?= $mahasiswa['fakultas'] ?>" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Program Studi</label>
                            <input type="text" class="form-control"
                                   value="<?= $mahasiswa['program_studi'] ?>" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"><?= $mahasiswa['alamat'] ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option value="Pria" <?= $mahasiswa['jenis_kelamin']=='Pria'?'selected':'' ?>>Laki-laki</option>
                                <option value="Wanita" <?= $mahasiswa['jenis_kelamin']=='Wanita'?'selected':'' ?>>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control"
                                   value="<?= $mahasiswa['tempat_lahir'] ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control"
                                   value="<?= $mahasiswa['tanggal_lahir'] ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Handphone</label>
                            <input type="text" name="handphone" class="form-control"
                                   value="<?= $mahasiswa['handphone'] ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="<?= $mahasiswa['email'] ?>">
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="save-btn">
                            Simpan Perubahan
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('fotoInput').addEventListener('change', function(e){
    const file = e.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = ev => document.getElementById('previewFoto').src = ev.target.result;
        reader.readAsDataURL(file);
    }
});
</script>

<?= $this->endSection() ?>
