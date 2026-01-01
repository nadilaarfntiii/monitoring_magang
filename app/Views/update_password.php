<?php 
$role = session()->get('role'); 

$layout = match($role) {
    'admin'     => 'layouts/admin',
    'dospem'    => 'layouts/dospem',
    'kaprodi'   => 'layouts/kaprodi',
    'mahasiswa' => 'layouts/mhs',
    'mitra'     => 'layouts/mitra',
    default     => 'layouts/auth'
};
?>

<?= $this->extend($layout) ?>

<?= $this->section('title') ?>
Perbarui Kata Sandi
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-2">

    <!-- Page Heading & Breadcrumb -->
     <div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Ubah Kata Sandi</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end me-4">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Ubah Kata Sandi</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

                 <!-- Konten / Isi Halaman -->

    <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-12">

            <!-- Card Wrapper -->
            <div class="card">
                <div class="card-body p-5" style="min-height: 450px; max-width: 100%;">

                    <!-- Alert Info -->
                    <div class="alert alert-warning d-flex align-items-center shadow-sm mb-4" role="alert" style="border-left: 5px solid #f0ad4e; font-size: 1.1rem; padding: 1.25rem 1.5rem;">
                        <i class="bi bi-exclamation-triangle-fill d-flex align-items-center me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <strong>Perbarui kata sandi</strong> untuk menjaga keamanan akun Anda.
                        </div>
                    </div>

                    <!-- Flashdata Error -->
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-light-danger d-flex align-items-center justify-content-between mb-3">
                            <div><i class="bi bi-exclamation-circle"></i> <?= session()->getFlashdata('error') ?></div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Flashdata Success -->
                    <?php if(session()->getFlashdata('success')): ?>
                        <div class="alert alert-light-success d-flex align-items-center justify-content-between mb-3">
                            <div><i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?></div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Form -->
                    <form action="<?= base_url('update_password_process') ?>" method="post">

                        <!-- Kata Sandi Lama -->
                        <div class="form-group mb-4">
                            <label class="form-label fw-semibold">Kata Sandi Lama</label>
                            <div class="input-group">
                                <input type="password" name="old_password" class="form-control form-control-lg" placeholder="Masukkan kata sandi lama" required id="old_password">
                                <span class="input-group-text bg-white border-start-0 toggle-password" style="cursor:pointer;">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Kata Sandi Baru -->
                        <div class="form-group mb-4">
                            <label class="form-label fw-semibold">Kata Sandi Baru</label>
                            <div class="input-group">
                                <input type="password" name="new_password" class="form-control form-control-lg" placeholder="Masukkan kata sandi baru" minlength="8" required id="new_password">
                                <span class="input-group-text bg-white border-start-0 toggle-password" style="cursor:pointer;">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                            <small class="text-muted">Minimal 8 karakter.</small>
                        </div>

                        <!-- Konfirmasi Kata Sandi Baru -->
                        <div class="form-group mb-5">
                            <label class="form-label fw-semibold">Konfirmasi Kata Sandi Baru</label>
                            <div class="input-group">
                                <input type="password" name="confirm_password" class="form-control form-control-lg" placeholder="Konfirmasi kata sandi baru" minlength="8" required id="confirm_password">
                                <span class="input-group-text bg-white border-start-0 toggle-password" style="cursor:pointer;">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg">Perbarui Kata Sandi</button>

                    </form>

                </div>
            </div>
            <!-- End Card -->

        </div>
    </div>
</div>

<!-- Script Toggle Password -->
<script>
document.querySelectorAll('.toggle-password').forEach(function(el){
    el.addEventListener('click', function(){
        let input = this.previousElementSibling; // input sebelum span
        let icon = this.querySelector('i');      // icon di dalam span
        if(input.type === "password"){
            input.type = "text";
            icon.classList.remove('bi-eye-fill');
            icon.classList.add('bi-eye-slash-fill');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye-slash-fill');
            icon.classList.add('bi-eye-fill');
        }
    });
});
</script>

<?= $this->endSection() ?>
