```php
<?php
require_once __DIR__ . '/auth.php';

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['user_id'])) {
    redirect('dashboard.php');
}

$error = '';
$email = '';
$flash = get_flash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Masukkan alamat email yang valid.';
    } elseif ($password === '') {
        $error = 'Password wajib diisi.';
    } else {
        $user = find_user_by_email($email);

        if (
            $user !== null &&
            password_verify($password, $user['password'])
        ) {
            session_regenerate_id(true);

$_SESSION['user_id'] = $user['id'];

if (isset($_POST['remember']) && $_POST['remember'] === '1') {
    if (!create_remember_token((string) $user['id'])) {
        clear_remember_cookie();
    }
} else {
    forget_remember_token((string) $user['id']);
}

            set_flash(
                'success',
                'Login berhasil. Selamat datang, ' . $user['name'] . '!'
            );

            redirect('dashboard.php');
        } else {
            $error = 'Email atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <h1>Selamat Datang</h1>
        <p class="subtitle">Login untuk melanjutkan ke dashboard.</p>

        <?php if ($flash): ?>
            <div class="alert <?= e($flash['type']) ?>">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="alert error">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= e($email) ?>"
                placeholder="nama@email.com"
                autocomplete="email"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan password"
                autocomplete="current-password"
                required
            >

            <div class="remember-me">
                <label>
                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                    >
                    Ingat Saya (Remember Me)
                </label>
            </div>

            <button type="submit">Login</button>
        </form>

        <p class="auth-footer">
            Belum punya akun?
            <a href="register.php">Daftar sekarang</a>
        </p>

        <p class="auth-footer">
            <a href="index.php">Kembali ke halaman awal</a>
        </p>
    </div>
</div>

</body>
</html>
```