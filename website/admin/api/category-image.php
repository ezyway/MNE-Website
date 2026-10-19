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

$token = $_POST['csrf'] ?? null;
if (!csrf_verify(is_string($token) ? $token : null)) {
    json_out(['success' => false, 'error' => 'Invalid CSRF token.'], 403);
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    json_out(['success' => false, 'error' => 'Invalid category id.'], 422);
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    json_out(['success' => false, 'error' => 'Image upload required.'], 422);
}

$file = $_FILES['image'];
if ($file['size'] > 5 * 1024 * 1024) {
    json_out(['success' => false, 'error' => 'Image too large (max 5MB).'], 422);
}

$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
$mime = @mime_content_type($file['tmp_name']);
if (!isset($allowed[$mime])) {
    json_out(['success' => false, 'error' => 'Invalid image format. Allowed: JPG, PNG, WEBP, GIF.'], 422);
}

$curStmt = db()->prepare('SELECT slug FROM product_categories WHERE id = :id LIMIT 1');
$curStmt->execute([':id' => $id]);
$slug = $curStmt->fetchColumn();
if (!$slug) {
    json_out(['success' => false, 'error' => 'Category not found.'], 404);
}

$ext = $allowed[$mime];
$dir = __DIR__ . '/../../assets/images/products/all';
if (!is_dir($dir)) {
    mkdir($dir, 0775, true);
}

$filename = 'cat_' . $id . '_' . time() . '.' . $ext;
$dest = $dir . '/' . $filename;
if (!move_uploaded_file($file['tmp_name'], $dest)) {
    json_out(['success' => false, 'error' => 'Failed to save image file.'], 500);
}

$imagePath = 'assets/images/products/all/' . $filename;
try {
    db()->prepare("UPDATE product_categories SET image_path = :ip, updated_at = datetime('now') WHERE id = :id")
        ->execute([':ip' => $imagePath, ':id' => $id]);
    json_out(['success' => true, 'image_path' => $imagePath]);
} catch (Exception $e) {
    json_out(['success' => false, 'error' => $e->getMessage()], 500);
}
