<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SmartParkir System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            width: 420px;
            border: none;
            border-radius: 20px;
            background: #ffffff;
        }
        .btn-login {
            background: #4e73df;
            border: none;
        }
        .btn-login:hover {
            background: #2e59d9;
        }
    </style>
</head>
<body>

<div class="card login-card shadow-lg animate__animated animate__fadeIn">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <div class="text-primary mb-2">
                <i class="fa-solid fa-square-parking fa-3x"></i>
            </div>
            <h4 class="fw-bold text-gray-900 m-0">SmartParkir</h4>
            <small class="text-muted">Sistem Manajemen Perparkiran Digital</small>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger p-2 small text-center rounded-3">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/prosesLogin'); ?>" method="POST">
            <?= csrf_field(); ?>
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                    <input type="text" name="username" class="form-control bg-light border-start-0" placeholder="Masukkan username" required autocomplete="off">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="Masukkan password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-login btn-primary w-100 py-2.5 fw-bold text-white shadow rounded-3">
                Masuk Ke Sistem <i class="fa-solid fa-arrow-right-to-bracket ms-1"></i>
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>