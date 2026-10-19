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
if ($id <= 0) { json_out(['success' => false, 'error' => 'Invalid category id.'], 422); }

try {
    $pdo = db();
    $pdo->beginTransaction();
    $pdo->prepare("DELETE FROM products WHERE category_id = :id")->execute([':id' => $id]);
    $pdo->prepare("DELETE FROM product_categories WHERE id = :id")->execute([':id' => $id]);
    $pdo->commit();
    json_out(['success' => true]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_out(['success' => false, 'error' => $e->getMessage()], 500);
}
