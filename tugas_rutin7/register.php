
<?php
require_once __DIR__ . '/auth.php';

if (isset($_SESSION['user_id'])) {
    redirect('dashboard.php');
}

$error = '';
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi nama
    if ($name === '') {
        $error = 'Nama wajib diisi.';
    } elseif (strlen($name) > 100) {
        $error = 'Nama maksimal 100 karakter.';
    }

    // Validasi email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    }

    // Validasi password
    elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    }

    elseif ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak cocok.';
    }

    // Cek email duplikat
    elseif (find_user_by_email($email) !== null) {
        $error = 'Email sudah terdaftar. Gunakan email lain.';
    }

    else {
        $users = get_users();

        // Hash password sebelum disimpan
        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $new_user = [
            'id' => bin2hex(random_bytes(8)),
            'name' => $name,
            'email' => $email,
            'password' => $hashed_password,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $users[] = $new_user;

        if (save_users($users)) {
            set_flash(
                'success',
                'Registrasi berhasil! Silakan login.'
            );

            redirect('login.php');
        } else {
            $error = 'Gagal menyimpan data. Periksa izin folder data.';
        }
    }
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <h1>Buat Akun</h1>
        <p class="subtitle">Daftar untuk mulai menggunakan aplikasi.</p>

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

        <form method="POST" action="register.php">
            <label for="name">Nama lengkap</label>
            <input
                type="text"
                id="name"
                name="name"
                maxlength="100"
                value="<?= e($name) ?>"
                placeholder="Masukkan nama lengkap"
                autocomplete="name"
                required
            >

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
                minlength="8"
                placeholder="Minimal 8 karakter"
                autocomplete="new-password"
                required
            >

            <label for="confirm_password">Konfirmasi password</label>
            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                minlength="8"
                placeholder="Masukkan ulang password"
                autocomplete="new-password"
                required
            >

            <button type="submit">Daftar Sekarang</button>
        </form>

        <p class="auth-footer">
            Sudah punya akun?
            <a href="login.php">Login di sini</a>
        </p>
    </div>
</div>

</body>
</html>
