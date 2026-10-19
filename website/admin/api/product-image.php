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
if ($id <= 0) { json_out(['success' => false, 'error' => 'Invalid product id.'], 422); }

if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
    json_out(['success' => false, 'error' => 'No image uploaded.'], 422);
}
if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    json_out(['success' => false, 'error' => 'Upload error (code ' . (int)$_FILES['image']['error'] . ').'], 422);
}

$file = $_FILES['image'];
$allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
$mime = @mime_content_type($file['tmp_name']);
if (!isset($allowed[$mime])) { json_out(['success' => false, 'error' => 'Invalid image type. Use JPG, PNG, WEBP or GIF.'], 422); }
if ($file['size'] > 5 * 1024 * 1024) { json_out(['success' => false, 'error' => 'Image too large (max 5MB).'], 422); }

// Determine category slug for storage subdir
$pdo = db();
$ext = $allowed[$mime];
$dir = __DIR__ . '/../../assets/images/products';
// store under a products-uploads subdir to avoid clobbering slug-based defaults? Use category slug subdir.
$cstmt = $pdo->prepare('SELECT c.slug FROM product_categories c JOIN products p ON p.category_id=c.id WHERE p.id=:id LIMIT 1');
$cstmt->execute([':id' => $id]);
$catSlug = $cstmt->fetchColumn() ?: 'products';
$subdir = $dir . '/' . $catSlug;
if (!is_dir($subdir)) { mkdir($subdir, 0775, true); }
$filename = 'prod_' . $id . '.' . $ext;
$dest = $subdir . '/' . $filename;
if (!move_uploaded_file($file['tmp_name'], $dest)) {
    json_out(['success' => false, 'error' => 'Failed to save uploaded image.'], 500);
}
$imagePath = 'assets/images/products/' . $catSlug . '/' . $filename;

$pdo->prepare('UPDATE products SET image_path=:ip, updated_at=datetime("now") WHERE id=:id')
    ->execute([':ip' => $imagePath, ':id' => $id]);

json_out(['success' => true, 'image_path' => $imagePath]);