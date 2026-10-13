
<?php
require_once __DIR__ . '/auth.php';

// Periksa status login pengguna
if (isset($_SESSION['user_id'])) {
    redirect('dashboard.php');
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Login dan Registrasi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card home-card">
        <h1>Login & Register</h1>

        <p class="subtitle">
            Selamat datang di aplikasi sistem autentikasi PHP Native.
        </p>

        <?php if ($flash): ?>
            <div class="alert <?= e($flash['type']) ?>">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>

        <div class="home-actions">
            <a class="button-link" href="login.php">
                Login
            </a>

            <a class="button-link secondary-button" href="register.php">
                Buat Akun
            </a>
        </div>

        <p class="auth-footer">
            Daftarkan akun terlebih dahulu jika kamu belum memiliki akun.
        </p>
    </div>
</div>

</body>
</html>
