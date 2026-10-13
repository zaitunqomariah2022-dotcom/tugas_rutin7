<?php

// Memulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lokasi file penyimpanan akun
define('DATA_FILE', __DIR__ . '/data/users.json');

// Nama cookie dan masa berlaku Remember Me (30 hari)
define('REMEMBER_COOKIE', 'remember_login');
define('REMEMBER_DURATION', 60 * 60 * 24 * 30);

// Membaca data pengguna dari JSON
function get_users(): array
{
    if (!file_exists(DATA_FILE)) {
        return [];
    }

    $content = file_get_contents(DATA_FILE);

    if ($content === false || trim($content) === '') {
        return [];
    }

    $users = json_decode($content, true);

    return is_array($users) ? $users : [];
}

// Menyimpan data pengguna ke JSON
function save_users(array $users): bool
{
    $json = json_encode(
        $users,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );

    if ($json === false) {
        return false;
    }

    return file_put_contents(DATA_FILE, $json, LOCK_EX) !== false;
}

// Mengamankan teks saat ditampilkan dalam HTML
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Mengarahkan pengguna ke halaman lain
function redirect(string $page): void
{
    header('Location: ' . $page);
    exit;
}

// Menyimpan pesan sukses atau error
function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

// Mengambil pesan dan menghapusnya dari session
function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

// Mengatur cookie Remember Me
function set_remember_cookie(string $value, int $expires): void
{
    setcookie(REMEMBER_COOKIE, $value, [
        'expires' => $expires,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

// Menghapus cookie Remember Me dari browser
function clear_remember_cookie(): void
{
    set_remember_cookie('', time() - 3600);
}

// Membuat token Remember Me dan menyimpan hash-nya
function create_remember_token(string $userId): bool
{
    $users = get_users();
    $found = false;
    $token = bin2hex(random_bytes(32));
    $expires = time() + REMEMBER_DURATION;

    foreach ($users as &$user) {
        if ((string) ($user['id'] ?? '') === $userId) {
            $user['remember_token_hash'] = hash('sha256', $token);
            $user['remember_expires'] = $expires;
            $found = true;
            break;
        }
    }
    unset($user);

    if (!$found || !save_users($users)) {
        return false;
    }

    set_remember_cookie($userId . ':' . $token, $expires);

    return true;
}

// Menghapus token Remember Me dari JSON dan browser
function forget_remember_token(?string $userId = null): void
{
    if ($userId !== null) {
        $users = get_users();

        foreach ($users as &$user) {
            if ((string) ($user['id'] ?? '') === $userId) {
                unset(
                    $user['remember_token_hash'],
                    $user['remember_expires']
                );
                break;
            }
        }
        unset($user);

        save_users($users);
    }

    clear_remember_cookie();
}

// Mengamankan teks saat ditampilkan dalam HTML
function redirect_after_remember_login(): void
{
    session_regenerate_id(true);
    redirect('dashboard.php');
}

// Mencoba login otomatis menggunakan cookie Remember Me
function restore_remember_login(): void
{
    if (isset($_SESSION['user_id'])) {
        return;
    }

    if (empty($_COOKIE[REMEMBER_COOKIE])) {
        return;
    }

    $parts = explode(':', $_COOKIE[REMEMBER_COOKIE], 2);

    if (count($parts) !== 2 || $parts[0] === '' || $parts[1] === '') {
        clear_remember_cookie();
        return;
    }

    [$userId, $token] = $parts;
    $user = find_user_by_id($userId);

    if (
        $user === null ||
        empty($user['remember_token_hash']) ||
        empty($user['remember_expires']) ||
        (int) $user['remember_expires'] < time() ||
        !hash_equals(
            $user['remember_token_hash'],
            hash('sha256', $token)
        )
    ) {
        clear_remember_cookie();
        return;
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
}

// Melindungi halaman agar hanya bisa dibuka setelah login
function require_login(): void
{
    if (!isset($_SESSION['user_id'])) {
        set_flash('error', 'Silakan login terlebih dahulu.');
        redirect('login.php');
    }
}

// Mencari akun berdasarkan ID
function find_user_by_id($id): ?array
{
    foreach (get_users() as $user) {
        if ((string) ($user['id'] ?? '') === (string) $id) {
            return $user;
        }
    }

    return null;
}

// Mencari akun berdasarkan email
function find_user_by_email(string $email): ?array
{
    foreach (get_users() as $user) {
        if (
            strtolower($user['email'] ?? '') === strtolower($email)
        ) {
            return $user;
        }
    }

    return null;
}

// Pulihkan sesi login bila cookie Remember Me masih valid
restore_remember_login();