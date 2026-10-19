<?php
define('ADMIN_ENTRY', true);
require_once __DIR__ . '/../lib/auth.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

function json_out(array $p, int $code = 200): void {
    http_response_code($code);
    echo json_encode($p);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(['success' => false, 'error' => 'Method not allowed.'], 405);
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) { $data = $_POST; }

$token = $data['csrf'] ?? null;
if (!csrf_verify(is_string($token) ? $token : null)) {
    json_out(['success' => false, 'error' => 'Invalid CSRF token.'], 403);
}

$id = (int)($data['id'] ?? 0);
$name = trim((string)($data['name'] ?? ''));
$desc = trim((string)($data['description'] ?? ''));

if ($id <= 0) { json_out(['success' => false, 'error' => 'Invalid product id.'], 422); }
if ($name === '') { json_out(['success' => false, 'error' => 'Name is required.'], 422); }

try {
    db()->prepare("UPDATE products SET name=:n, description=:d, updated_at=datetime('now') WHERE id=:id")
        ->execute([':n' => $name, ':d' => $desc, ':id' => $id]);
    json_out(['success' => true]);
} catch (Exception $e) {
    json_out(['success' => false, 'error' => $e->getMessage()], 500);
}