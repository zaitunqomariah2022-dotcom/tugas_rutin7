
<?php
require_once __DIR__ . '/auth.php';

// Hanya pengguna yang sudah login boleh masuk
require_login();

// Ambil data pengguna yang sedang login
$user = find_user_by_id($_SESSION['user_id']);

if ($user === null) {
    $_SESSION = [];
    session_destroy();
    redirect('login.php');
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard-container">
    <header class="dashboard-header">
        <div>
            <h1>Dashboard</h1>
            <p class="subtitle">Selamat datang di akun kamu.</p>
        </div>

        <a class="button-link danger-button" href="logout.php">
            Logout
        </a>
    </header>

    <?php if ($flash): ?>
        <div class="alert <?= e($flash['type']) ?>">
            <?= e($flash['message']) ?>
        </div>
    <?php endif; ?>

    <section class="profile-card">
        <h2>Profil Pengguna</h2>

        <div class="profile-item">
            <span>Nama lengkap</span>
            <strong><?= e($user['name']) ?></strong>
        </div>

        <div class="profile-item">
            <span>Email</span>
            <strong><?= e($user['email']) ?></strong>
        </div>

        <div class="profile-item">
            <span>Tanggal registrasi</span>
            <strong><?= e($user['created_at'] ?? '-') ?></strong>
        </div>

        <a class="button-link" href="edit_profile.php">
            Edit Profil
        </a>
    </section>
</div>

</body>
</html>
