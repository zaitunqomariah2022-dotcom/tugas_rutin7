
<?php
require_once __DIR__ . '/auth.php';

require_login();

$user = find_user_by_id($_SESSION['user_id']);

if ($user === null) {
    $_SESSION = [];
    session_destroy();
    redirect('login.php');
}

$error = '';
$name = $user['name'];
$email = $user['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));

    if ($name === '') {
        $error = 'Nama wajib diisi.';
    } elseif (strlen($name) > 100) {
        $error = 'Nama maksimal 100 karakter.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } else {
        $users = get_users();
        $duplicate = false;

        foreach ($users as $existing) {
            if (
                strtolower($existing['email']) === $email &&
                (string) $existing['id'] !==
                (string) $user['id']
            ) {
                $duplicate = true;
                break;
            }
        }

        if ($duplicate) {
            $error = 'Email sudah digunakan akun lain.';
        } else {
            foreach ($users as &$existing) {
                if (
                    (string) $existing['id'] ===
                    (string) $user['id']
                ) {
                    $existing['name'] = $name;
                    $existing['email'] = $email;
                    break;
                }
            }

            unset($existing);

            if (save_users($users)) {
                set_flash(
                    'success',
                    'Profil berhasil diperbarui.'
                );

                redirect('dashboard.php');
            } else {
                $error = 'Gagal menyimpan perubahan profil.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <h1>Edit Profil</h1>
        <p class="subtitle">Perbarui informasi akun kamu.</p>

        <?php if ($error !== ''): ?>
            <div class="alert error">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="edit_profile.php">
            <label for="name">Nama lengkap</label>
            <input
                type="text"
                id="name"
                name="name"
                maxlength="100"
                value="<?= e($name) ?>"
                required
            >

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= e($email) ?>"
                required
            >

            <button type="submit">Simpan Perubahan</button>
        </form>

        <p class="auth-footer">
            <a href="dashboard.php">Kembali ke dashboard</a>
        </p>
    </div>
</div>

</body>
</html>
