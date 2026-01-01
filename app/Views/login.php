<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sistem Informasi Monitoring Magang</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="./assets/images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="./assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="./assets/vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="./assets/vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="./assets/vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/util.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/main.css">

    <style>
        .wrap-input100 {
        position: relative;
        margin-bottom: 2.0rem; /* cukup beri space untuk error, jangan ubah input */
    }

    /* input merah saat invalid */
    .input100.is-invalid {
        border-color: #dc3545 !important;
    }

    /* pesan error di posisi absolute */
    .invalid-feedback {
        position: absolute;
        left: 20px;
        bottom: -25px; /* di bawah input, tidak memengaruhi input */
        font-size: 0.875rem;
        color: #dc3545;
        display: none;
    }

    /* tampilkan jika input invalid */
    .input100.is-invalid + .invalid-feedback {
        display: block;
    }

    h5 {
        text-align: center;           /* rata tengah */
        font-family: 'Poppins', sans-serif; /* jika login100 pakai Poppins, atau sesuaikan */
        font-weight: 600;             /* tebal mirip judul login100 */
        font-size: 1.2rem;            /* ukuran mirip login100 */
        margin-top: -46px;             /* jarak dari logo ~1 cm */
        margin-bottom: 20px;          /* jarak ke flashdata atau input */
        line-height: 1.5;
        color: #333;                  /* warna teks */
    }

    .alert.alert-danger {
        margin: 0.3rem 0 1.5rem 0;
        padding: 0.4rem 0.8rem;
        font-size: 0.9rem;
    }
    </style>
</head>
<body>
    
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
        <div class="login100-pic">
            <img src="./assets/images/iwima.jpg" alt="IMG">
        </div>

            <form id="loginForm" class="login100-form validate-form" action="<?= base_url('/login/auth') ?>" method="POST" novalidate>
            <span class="login100-form-title">
                <img src="./assets/images/logo-iwima.jpg" alt="Logo IWIMA" style="width:120px; display:block; margin:0 auto;">
            </span>

                <h5>Sistem Informasi <br> Monitoring Magang</h5>

                <!-- Tampilkan error backend -->
                <?php if(session()->getFlashdata('error')): ?>
                    <div id="flashError" class="alert alert-danger text-center">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <!-- Username -->
                <div class="wrap-input100 validate-input">
                    <input type="text" name="username" class="input100" placeholder="Username" value="<?= old('username') ?>">
                    <div class="invalid-feedback">Username wajib diisi.</div>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </span>
                </div>

                <!-- Password -->
                <div class="wrap-input100 validate-input">
                    <input type="password" name="pass" class="input100" placeholder="Password">
                    <div class="invalid-feedback">Password wajib diisi.</div>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn" type="submit">
                        Login
                    </button>
                </div>

                <div class="text-center p-t-12 mt-2">
                    <span class="txt1">Forgot</span>
                    <a class="txt2" href="#">Username / Password?</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="./assets/vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="./assets/vendor/bootstrap/js/popper.js"></script>
<script src="./assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="./assets/vendor/select2/select2.min.js"></script>
<script src="./assets/vendor/tilt/tilt.jquery.min.js"></script>
<script>
    $('.js-tilt').tilt({ scale: 1.1 })
</script>

<script>
// Validasi frontend saat submit
$('#loginForm').on('submit', function(e){
    var valid = true;

    $(this).find('input.input100').each(function(){
        if ($(this).val().trim() === '') {
            $(this).addClass('is-invalid');
            valid = false;
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    if(!valid){
        e.preventDefault(); // hentikan submit jika ada input kosong
        return false;
    }
});

$('#loginForm input.input100').on('input', function() {
    $('#flashError').fadeOut(); // sembunyikan alert dengan ID
});
</script>

</body>
</html>
