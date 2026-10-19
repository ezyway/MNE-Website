<?php
/**
 * Maruti Nandan Exports — Admin authentication helpers
 */
if (!defined('ADMIN_ENTRY')) {
    http_response_code(403);
    exit('Forbidden');
}
$configPath = __DIR__ . '/../../config.php';
if (file_exists($configPath)) {
    require_once $configPath;
}
require_once __DIR__ . '/db.php';

function auth_start_session(): void {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/admin',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function is_logged_in(): bool {
    auth_start_session();
    return !empty($_SESSION['admin_id']) && !empty($_SESSION['admin_user']);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function attempt_login(string $username, string $password): bool {
    auth_start_session();
    $stmt = db()->prepare('SELECT id, username, password_hash FROM admins WHERE username = :u LIMIT 1');
    $stmt->execute([':u' => $username]);
    $row = $stmt->fetch();
    if (!$row) {
        return false;
    }
    if (!password_verify($password, $row['password_hash'])) {
        return false;
    }
    session_regenerate_id(true);
    $_SESSION['admin_id']   = (int) $row['id'];
    $_SESSION['admin_user'] = $row['username'];
    $_SESSION['csrf']       = bin2hex(random_bytes(32));
    return true;
}

function logout(): void {
    auth_start_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function csrf_token(): string {
    auth_start_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_verify(?string $token): bool {
    auth_start_session();
    return is_string($token) && hash_equals($_SESSION['csrf'] ?? '', $token);
}

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function status_badge_class(string $status): string {
    return match ($status) {
        'new'       => 'badge badge--new',
        'read'      => 'badge badge--read',
        'replied'   => 'badge badge--replied',
        'archived'  => 'badge badge--archived',
        default     => 'badge',
    };
}