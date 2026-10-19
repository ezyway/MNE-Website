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
if ($id <= 0) {
    json_out(['success' => false, 'error' => 'Invalid category id.'], 422);
}

$stmt = db()->prepare("SELECT * FROM product_categories WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$cur = $stmt->fetch();
if (!$cur) {
    json_out(['success' => false, 'error' => 'Category not found.'], 404);
}

$name = isset($data['name']) ? trim((string)$data['name']) : $cur['name'];
$cardDesc = isset($data['card_description']) ? trim((string)$data['card_description']) : $cur['card_description'];
$countLabel = isset($data['count_label']) ? trim((string)$data['count_label']) : $cur['count_label'];
$title = isset($data['title']) ? trim((string)$data['title']) : $cur['title'];
$tagline = isset($data['tagline']) ? trim((string)$data['tagline']) : $cur['tagline'];
$subtitle = isset($data['subtitle']) ? trim((string)$data['subtitle']) : $cur['subtitle'];

if ($name === '') {
    json_out(['success' => false, 'error' => 'Category name cannot be empty.'], 422);
}

try {
    db()->prepare("UPDATE product_categories SET name=:n, title=:t, tagline=:tg, subtitle=:s, card_description=:cd, count_label=:cl, updated_at=datetime('now') WHERE id=:id")
        ->execute([
            ':n'  => $name,
            ':t'  => $title,
            ':tg' => $tagline,
            ':s'  => $subtitle,
            ':cd' => $cardDesc,
            ':cl' => $countLabel,
            ':id' => $id
        ]);
    json_out(['success' => true]);
} catch (Exception $e) {
    json_out(['success' => false, 'error' => $e->getMessage()], 500);
}
