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

$order = $data['order'] ?? null;
if (!is_array($order) || empty($order)) {
    json_out(['success' => false, 'error' => 'No order list provided.'], 422);
}

try {
    $pdo = db();
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("UPDATE products SET sort_order = :so, updated_at = datetime('now') WHERE id = :id");
    
    $index = 1;
    foreach ($order as $id) {
        $prodId = (int)$id;
        if ($prodId > 0) {
            $stmt->execute([':so' => $index, ':id' => $prodId]);
            $index++;
        }
    }
    
    $pdo->commit();
    json_out(['success' => true]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_out(['success' => false, 'error' => $e->getMessage()], 500);
}
