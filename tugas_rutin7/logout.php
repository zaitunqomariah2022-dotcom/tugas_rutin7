```php
<?php
require_once __DIR__ . '/auth.php';

// Hapus token Remember Me untuk akun yang sedang login
if (isset($_SESSION['user_id'])) {
    forget_remember_token((string) $_SESSION['user_id']);
} else {
    clear_remember_cookie();
}

// Hapus seluruh data session
$_SESSION = [];

// Hapus cookie session jika digunakan
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Akhiri session
session_destroy();

// Arahkan kembali ke halaman awal
header('Location: index.php');
exit;