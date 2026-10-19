<?php
define('ADMIN_ENTRY', true);
require_once __DIR__ . '/lib/auth.php';
require_login();
seed_products_if_empty();

$flash = null; $flashType = 'ok';
$csrf = csrf_token();

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf'] ?? null;
    $action = $_POST['action'] ?? '';
    if (!csrf_verify($token)) {
        $flash = 'Invalid CSRF token.'; $flashType = 'err';
    } else {
        try {
            if ($action === 'save_category') {
                $id = (int)($_POST['id'] ?? 0);
                $name = trim($_POST['name'] ?? '');
                $title = trim($_POST['title'] ?? '');
                $tagline = trim($_POST['tagline'] ?? '');
                $subtitle = trim($_POST['subtitle'] ?? '');
                $cardDesc = trim($_POST['card_description'] ?? '');
                $countLabel = trim($_POST['count_label'] ?? '');
                if ($name === '' || $title === '') { throw new Exception('Name and title are required.'); }

                $curStmt = db()->prepare('SELECT slug, image_path FROM product_categories WHERE id = :id LIMIT 1');
                $curStmt->execute([':id' => $id]);
                $cur = $curStmt->fetch();
                if (!$cur) { throw new Exception('Category not found.'); }
                $imagePath = $cur['image_path'];

                if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                        throw new Exception('Image upload error (code ' . (int)$_FILES['image']['error'] . ').');
                    }
                    $file = $_FILES['image'];
                    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
                    $mime = @mime_content_type($file['tmp_name']);
                    if (!isset($allowed[$mime])) { throw new Exception('Invalid image type. Use JPG, PNG, WEBP or GIF.'); }
                    if ($file['size'] > 5 * 1024 * 1024) { throw new Exception('Image too large (max 5MB).'); }
                    $ext = $allowed[$mime];
                    $dir = __DIR__ . '/../assets/images/products/all';
                    if (!is_dir($dir)) { mkdir($dir, 0775, true); }
                    $filename = $cur['slug'] . '.' . $ext;
                    $dest = $dir . '/' . $filename;
                    if (!move_uploaded_file($file['tmp_name'], $dest)) { throw new Exception('Failed to save uploaded image.'); }
                    $imagePath = 'assets/images/products/all/' . $filename;
                }

                db()->prepare("UPDATE product_categories SET name=:n, title=:t, tagline=:tg, subtitle=:s, card_description=:cd, count_label=:cl, image_path=:ip, updated_at=datetime('now') WHERE id=:id")
                    ->execute([':n'=>$name,':t'=>$title,':tg'=>$tagline,':s'=>$subtitle,':cd'=>$cardDesc,':cl'=>$countLabel,':ip'=>$imagePath,':id'=>$id]);
                $flash = 'Category updated.';
            } elseif ($action === 'add_category') {
                $name = trim($_POST['name'] ?? '');
                $cardDesc = trim($_POST['card_description'] ?? '');
                if ($name === '') {
                    throw new Exception('Category name is required.');
                }
                $baseSlug = preg_replace('/[^a-z0-9]+/', '_', strtolower(preg_replace('/\s*\(.*?\)\s*/', '', $name)));
                $baseSlug = trim($baseSlug, '_');
                if ($baseSlug === '') {
                    $baseSlug = 'category_' . time();
                }
                $slug = $baseSlug;

                $checkStmt = db()->prepare("SELECT COUNT(*) FROM product_categories WHERE slug = :s");
                $checkStmt->execute([':s' => $slug]);
                $counter = 1;
                while ((int)$checkStmt->fetchColumn() > 0) {
                    $slug = $baseSlug . '_' . $counter;
                    $counter++;
                    $checkStmt->execute([':s' => $slug]);
                }

                $maxSo = (int) db()->query("SELECT COALESCE(MAX(sort_order),0) FROM product_categories")->fetchColumn();

                $imagePath = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                        throw new Exception('Image upload error (code ' . (int)$_FILES['image']['error'] . ').');
                    }
                    $file = $_FILES['image'];
                    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
                    $mime = @mime_content_type($file['tmp_name']);
                    if (!isset($allowed[$mime])) {
                        throw new Exception('Invalid image type. Use JPG, PNG, WEBP or GIF.');
                    }
                    if ($file['size'] > 5 * 1024 * 1024) {
                        throw new Exception('Image too large (max 5MB).');
                    }
                    $ext = $allowed[$mime];
                    $dir = __DIR__ . '/../assets/images/products/all';
                    if (!is_dir($dir)) {
                        mkdir($dir, 0775, true);
                    }
                    $filename = $slug . '.' . $ext;
                    $dest = $dir . '/' . $filename;
                    if (!move_uploaded_file($file['tmp_name'], $dest)) {
                        throw new Exception('Failed to save uploaded image.');
                    }
                    $imagePath = 'assets/images/products/all/' . $filename;
                }

                db()->prepare("INSERT INTO product_categories (slug, name, title, tagline, subtitle, card_description, count_label, image_path, sort_order) VALUES (:slug, :name, :title, :tagline, :subtitle, :cd, :cl, :ip, :so)")
                    ->execute([
                        ':slug'      => $slug,
                        ':name'      => $name,
                        ':title'     => $name,
                        ':tagline'   => '',
                        ':subtitle'  => '',
                        ':cd'        => $cardDesc,
                        ':cl'        => '',
                        ':ip'        => $imagePath,
                        ':so'        => $maxSo + 1
                    ]);

                $flash = 'Category added.';
            } elseif ($action === 'add_product') {
                $catId = (int)($_POST['category_id'] ?? 0);
                $name = trim($_POST['name'] ?? '');
                $desc = trim($_POST['description'] ?? '');
                if ($name === '') { throw new Exception('Product name is required.'); }
                $maxSo = (int) db()->query("SELECT COALESCE(MAX(sort_order),0) FROM products WHERE category_id=$catId")->fetchColumn();
                db()->prepare("INSERT INTO products (category_id, name, description, sort_order) VALUES (:c,:n,:d,:so)")
                    ->execute([':c'=>$catId,':n'=>$name,':d'=>$desc,':so'=>$maxSo+1]);
                $newId = (int) db()->lastInsertId();

                // Handle optional image upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['image'];
                    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
                    $mime = @mime_content_type($file['tmp_name']);
                    if (isset($allowed[$mime]) && $file['size'] <= 5 * 1024 * 1024) {
                        $ext = $allowed[$mime];
                        // category slug
                        $cstmt = db()->prepare('SELECT slug FROM product_categories WHERE id=:id LIMIT 1');
                        $cstmt->execute([':id' => $catId]);
                        $catSlug = $cstmt->fetchColumn() ?: 'products';
                        $dir = __DIR__ . '/../assets/images/products/' . $catSlug;
                        if (!is_dir($dir)) { mkdir($dir, 0775, true); }
                        $filename = 'prod_' . $newId . '.' . $ext;
                        $dest = $dir . '/' . $filename;
                        if (move_uploaded_file($file['tmp_name'], $dest)) {
                            $imagePath = 'assets/images/products/' . $catSlug . '/' . $filename;
                            db()->prepare("UPDATE products SET image_path=:ip WHERE id=:id")->execute([':ip'=>$imagePath, ':id'=>$newId]);
                        }
                    }
                }
                $flash = 'Product added.';
            } elseif ($action === 'save_product') {
                $id = (int)($_POST['id'] ?? 0);
                $name = trim($_POST['name'] ?? '');
                $desc = trim($_POST['description'] ?? '');
                if ($name === '') { throw new Exception('Product name is required.'); }
                db()->prepare("UPDATE products SET name=:n, description=:d, updated_at=datetime('now') WHERE id=:id")
                    ->execute([':n'=>$name,':d'=>$desc,':id'=>$id]);
                $flash = 'Product updated.';
            } elseif ($action === 'delete_product') {
                $id = (int)($_POST['id'] ?? 0);
                db()->prepare("DELETE FROM products WHERE id=:id")->execute([':id'=>$id]);
                $flash = 'Product deleted.';
            } elseif ($action === 'delete_category') {
                $id = (int)($_POST['id'] ?? 0);
                $pdo = db();
                $pdo->beginTransaction();
                $pdo->prepare("DELETE FROM products WHERE category_id = :id")->execute([':id' => $id]);
                $pdo->prepare("DELETE FROM product_categories WHERE id = :id")->execute([':id' => $id]);
                $pdo->commit();
                $flash = 'Category deleted.';
            }
        } catch (Exception $e) {
            $flash = $e->getMessage(); $flashType = 'err';
        }
    }
}

$catSlug = isset($_GET['cat']) ? trim($_GET['cat']) : '';
$selectedCat = $catSlug !== '' ? get_category_by_slug($catSlug) : null;

$pageTitle = $selectedCat ? 'Products — ' . $selectedCat['name'] : 'Products';
require __DIR__ . '/partials/header.php';
?>
    <style>
        .prod-cat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; margin-top: 1.5rem; margin-bottom: 1.5rem; }
        @media (max-width: 900px) { .prod-cat-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 560px) { .prod-cat-grid { grid-template-columns: 1fr; } }
        .prod-cat-card {
            position: relative; border-radius: 20px; overflow: hidden; min-height: 260px;
            background-size: cover; background-position: center; background-color: var(--teal-950, #041410);
            box-shadow: 0 10px 30px rgba(14,59,51,0.18); text-decoration: none; color: #fff;
            display: flex; flex-direction: column; justify-content: space-between; cursor: default;
            transition: transform 0.3s ease, box-shadow 0.3s ease; border: none;
        }
        .prod-cat-card:hover { transform: translateY(-6px) scale(1.01); box-shadow: 0 18px 40px rgba(14,59,51,0.28); }
        .prod-cat-card-add {
            text-align: left;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 2px dashed rgba(14, 59, 51, 0.22);
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 260px;
            box-shadow: 0 10px 30px rgba(14, 59, 51, 0.06);
            transition: transform 0.25s ease, border-color 0.25s ease, background 0.25s ease;
            box-sizing: border-box;
        }
        .prod-cat-card-add:hover {
            transform: translateY(-4px);
            border-color: rgba(39, 165, 136, 0.5);
            background: rgba(255, 255, 255, 0.65);
        }
        .prod-cat-card__overlay { position: absolute; inset: 0; padding: 1.5rem; background: linear-gradient(180deg, rgba(4,20,16,0.2) 0%, rgba(4,20,16,0.5) 45%, rgba(4,20,16,0.94) 100%); display: flex; flex-direction: column; justify-content: space-between; }
        .prod-cat-card .card-actions {
            position: static;
            opacity: 0;
            transform: translateY(-4px);
            pointer-events: none;
            transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1), transform 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .prod-cat-card:hover .card-actions,
        .prod-cat-card:focus-within .card-actions {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        .prod-cat-card.is-dragging {
            opacity: 0.45;
            transform: scale(0.97) !important;
            border: 2px dashed var(--teal-500, #27a588) !important;
            box-shadow: 0 14px 36px rgba(4, 20, 16, 0.25) !important;
            cursor: grabbing !important;
            user-select: none;
        }
        .inline-cat-field {
            border: 1px solid transparent;
            border-radius: 8px;
            padding: 0.2rem 0.45rem;
            margin: -0.2rem -0.45rem;
            outline: none;
            transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
            background: transparent;
            color: #ffffff;
            width: 100%;
            font-family: inherit;
        }
        .inline-cat-field:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.25);
        }
        .inline-cat-field:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(14, 59, 51, 0.35);
            color: var(--teal-950, #041410);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .inline-cat-title {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 0.25rem;
        }
        .inline-cat-desc {
            font-size: 0.85rem;
            line-height: 1.45;
            resize: none;
            overflow: hidden;
            min-height: 2.8em;
            display: block;
            color: rgba(255, 255, 255, 0.9);
        }
        .inline-cat-badge {
            padding: 0.32rem 0.75rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--teal-950, #041410);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            width: auto;
            max-width: 170px;
        }
        .inline-cat-badge:hover {
            background: #ffffff;
            border-color: rgba(14, 59, 51, 0.25);
        }
        .inline-cat-badge:focus {
            background: #ffffff;
            border-color: var(--teal-600, #1c7a69);
            color: var(--teal-950, #041410);
        }
        .cat-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.6rem;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .cat-manage-btn {
            color: #ffffff;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: transform 0.15s ease, color 0.15s ease;
        }
        .cat-manage-btn:hover {
            transform: translateX(3px);
            color: var(--gold-300, #f1cf75);
        }
        .cat-edit-modal-btn {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 999px;
            padding: 0.25rem 0.65rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            transition: background 0.2s ease, transform 0.15s ease;
        }
        .cat-edit-modal-btn:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: scale(1.05);
        }
        .prod-cat-card__badge { align-self: flex-start; padding: 0.35rem 0.85rem; border-radius: 999px; background: rgba(255,255,255,0.92); backdrop-filter: blur(8px); font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--teal-950, #041410); box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        .prod-cat-card__content { display: flex; flex-direction: column; gap: 0.4rem; }
        .prod-cat-card__title { font-family: var(--font-display); font-size: 1.5rem; color: #fff; line-height: 1.2; }
        .prod-cat-card__desc { font-size: 0.85rem; color: rgba(255,255,255,0.85); line-height: 1.5; }
        .prod-cat-card__hint { display: inline-flex; align-items: center; gap: 0.35rem; margin-top: 0.6rem; font-size: 0.8rem; font-weight: 600; color: rgba(255,255,255,0.9); }

        /* Table */
        .prod-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        .prod-table th, .prod-table td { padding: 0.7rem 0.6rem; text-align: left; border-bottom: 1px solid rgba(14,59,51,0.06); vertical-align: middle; }
        .prod-table th { font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--ink-secondary, #5a7a72); font-weight: 700; background: rgba(245,247,245,0.8); }
        .prod-table tr:hover td { background: rgba(39,165,136,0.04); }
        .prod-table .preview-thumb { width: 64px; height: 48px; border-radius: 8px; overflow: hidden; background: #e8f1ee; cursor: pointer; }
        .prod-table .preview-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .clickable { cursor: pointer; }

        /* Modal */
        .modal-overlay { position: fixed; inset: 0; z-index: 1000; background: rgba(4,20,16,0.6); backdrop-filter: blur(6px); display: none; align-items: center; justify-content: center; padding: 1.5rem; }
        .modal-overlay.open { display: flex; }
        .modal { background: rgba(255,255,255,0.92); backdrop-filter: blur(18px); border: 1px solid rgba(255,255,255,0.95); border-radius: 24px; box-shadow: 0 24px 64px rgba(4,20,16,0.35); max-width: 720px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 1.75rem; position: relative; }
        .modal-close { position: absolute; top: 1rem; right: 1rem; width: 36px; height: 36px; border-radius: 50%; border: none; background: rgba(14,59,51,0.08); color: var(--teal-950, #041410); font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .modal-close:hover { background: rgba(14,59,51,0.16); }
        .modal-title { font-family: var(--font-display); font-size: 1.6rem; color: var(--teal-950, #041410); margin: 0 0 1rem; letter-spacing: -0.02em; }
        .modal-preview { margin-bottom: 1.25rem; }
        .client-preview { background: linear-gradient(135deg, rgba(245,245,240,0.9) 0%, rgba(235,238,235,0.95) 100%); border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.95); box-shadow: 0 6px 20px rgba(14,59,51,0.08); position: relative; }
        .client-preview__img { height: 150px; background: #e8f1ee; }
        .client-preview__img img { width: 100%; height: 100%; object-fit: cover; }
        .client-preview__badge { position: absolute; top: 0.75rem; right: 0.75rem; padding: 0.25rem 0.6rem; border-radius: 999px; background: rgba(255,255,255,0.9); font-size: 0.65rem; font-weight: 800; text-transform: uppercase; color: var(--teal-950, #041410); border: 1px solid rgba(39,165,136,0.3); }
        .client-preview__body { padding: 0.9rem 1rem; }
        .client-preview__name { font-size: 1.15rem; font-weight: 700; color: var(--teal-950, #041410); margin-bottom: 0.3rem; cursor: pointer; }
        .client-preview__desc { font-size: 0.88rem; color: #555; line-height: 1.45; cursor: pointer; }
        .cat-preview { position: relative; border-radius: 16px; overflow: hidden; min-height: 180px; background-size: cover; background-position: center; background-color: var(--teal-950, #041410); }
        .cat-preview__overlay { position: absolute; inset: 0; padding: 1.25rem; background: linear-gradient(180deg, rgba(4,20,16,0.15) 0%, rgba(4,20,16,0.4) 45%, rgba(4,20,16,0.92) 100%); display: flex; flex-direction: column; justify-content: space-between; }
        .cat-preview__badge { align-self: flex-start; padding: 0.3rem 0.75rem; border-radius: 999px; background: rgba(255,255,255,0.92); font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--teal-950, #041410); }
        .cat-preview__title { font-family: var(--font-display); font-size: 1.4rem; color: #fff; line-height: 1.2; cursor: pointer; }
        .cat-preview__desc { font-size: 0.82rem; color: rgba(255,255,255,0.85); line-height: 1.45; cursor: pointer; }

        .modal .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem; }
        .modal .field-row.single { grid-template-columns: 1fr; }
        .modal .field { display: flex; flex-direction: column; gap: 0.3rem; }
        .modal .field label { font-size: 0.8rem; font-weight: 600; color: var(--teal-950, #041410); }
        .modal .field input, .modal .field textarea { padding: 0.55rem 0.8rem; border: 1px solid rgba(14,59,51,0.12); border-radius: 10px; font-size: 0.9rem; font-family: inherit; background: rgba(255,255,255,0.9); }
        .modal .field textarea { min-height: 70px; resize: vertical; }
        .modal .btn { padding: 0.6rem 1.2rem; border: none; border-radius: 10px; background: linear-gradient(135deg, var(--teal-950, #041410), #0a2e28); color: #fff; font-weight: 600; cursor: pointer; font-size: 0.88rem; }
        .modal .btn.ghost { background: rgba(14,59,51,0.08); color: var(--teal-950, #041410); }
        .modal .btn.danger { background: #c62828; }
        .modal-actions { display: flex; gap: 0.6rem; flex-wrap: wrap; margin-top: 1rem; align-items: center; }
        .hint { font-size: 0.75rem; color: var(--ink-secondary, #5a7a72); margin-top: 0.25rem; }
        /* Product Card Action Buttons (Hover) */
        .card-actions {
            position: absolute;
            top: 0.85rem;
            left: 0.85rem;
            z-index: 10;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            opacity: 0;
            transform: translateY(-4px);
            transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1), transform 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }
        .product-card:hover .card-actions,
        .product-card:focus-within .card-actions {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        .card-action-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.32);
            background: rgba(4, 20, 16, 0.78);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: #ffffff;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0;
            outline: none;
            box-shadow: 0 4px 12px rgba(4, 20, 16, 0.25);
            transition: background-color 0.2s ease, transform 0.15s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s ease, box-shadow 0.2s ease, color 0.2s ease;
        }
        .card-action-btn svg {
            width: 15px;
            height: 15px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
            display: block;
            pointer-events: none;
        }
        .card-action-btn:hover {
            background: rgba(14, 59, 51, 0.95);
            border-color: rgba(255, 255, 255, 0.65);
            transform: scale(1.08);
            box-shadow: 0 6px 16px rgba(4, 20, 16, 0.35);
        }
        .card-action-btn:active {
            transform: scale(0.94);
        }
        .card-action-btn--delete {
            background: rgba(198, 40, 40, 0.88);
            border-color: rgba(255, 255, 255, 0.32);
            box-shadow: 0 4px 12px rgba(198, 40, 40, 0.28);
        }
        .card-action-btn--delete:hover {
            background: #d32f2f;
            border-color: rgba(255, 255, 255, 0.7);
            box-shadow: 0 6px 16px rgba(198, 40, 40, 0.45);
        }
        .card-action-btn--drag {
            cursor: grab;
            user-select: none;
            -webkit-user-select: none;
            touch-action: none;
        }
        .card-action-btn--drag:active {
            cursor: grabbing;
        }
        .product-card__image {
            user-select: none;
            -webkit-user-drag: none;
        }
        .product-card.is-dragging {
            opacity: 0.45;
            transform: scale(0.97) !important;
            border: 2px dashed var(--teal-500, #27a588) !important;
            box-shadow: 0 14px 36px rgba(4, 20, 16, 0.25) !important;
            cursor: grabbing !important;
            user-select: none;
        }
        .reorder-toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1100;
            background: rgba(4, 20, 16, 0.92);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            padding: 0.65rem 1.25rem;
            border-radius: 999px;
            font-size: 0.88rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            opacity: 0;
            transform: translateY(14px);
            pointer-events: none;
            transition: opacity 0.25s cubic-bezier(0.16, 1, 0.3, 1), transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reorder-toast.show {
            opacity: 1;
            transform: translateY(0);
        }
        .inline-input { border: 1px solid transparent; border-radius: 6px; padding: 0.1rem 0.25rem; margin: -0.1rem -0.25rem; outline: none; transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease; font: inherit; }
        .inline-input:hover { background: rgba(255,255,255,0.18); }
        .inline-input:focus { background: rgba(255,255,255,0.95); border-color: rgba(14,59,51,0.35); color: var(--teal-950, #041410); }
        .inline-textarea { display: block; }
    </style>

    <?php if ($flash): ?>
        <div class="flash <?php echo $flashType === 'err' ? 'err' : 'ok'; ?>"><?php echo h($flash); ?></div>
    <?php endif; ?>

    <?php if (!$selectedCat): ?>
        <h1 class="admin-title">Product Categories</h1>
        <p class="admin-subtitle">Click a card to preview and edit. Manage its products from there.</p>

        <div class="prod-cat-grid">
            <!-- Add new category card (inline form) -->
            <form method="POST" action="products.php" enctype="multipart/form-data" class="prod-cat-card-add glass-panel">
                <input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
                <input type="hidden" name="action" value="add_category">
                <div style="text-align:center; color:var(--teal-950,#041410); margin-bottom:0.75rem;">
                    <div style="font-size:2.4rem; line-height:1; font-weight:300;">+</div>
                    <div style="font-weight:700; font-size:1.05rem; letter-spacing:-0.01em;">Add New Category</div>
                </div>
                <div style="margin-bottom:0.5rem;">
                    <input type="text" name="name" required placeholder="Category name" style="width:100%; padding:0.5rem 0.75rem; border:1px solid rgba(14,59,51,0.18); border-radius:10px; font-size:0.9rem; background:rgba(255,255,255,0.85); box-sizing:border-box;">
                </div>
                <div style="margin-bottom:0.5rem;">
                    <textarea name="card_description" placeholder="Description or tagline" style="width:100%; min-height:50px; padding:0.5rem 0.75rem; border:1px solid rgba(14,59,51,0.18); border-radius:10px; font-size:0.85rem; resize:vertical; background:rgba(255,255,255,0.85); font-family:inherit; box-sizing:border-box;"></textarea>
                </div>
                <div style="margin-bottom:0.75rem;">
                    <label style="font-size:0.76rem; font-weight:600; color:var(--teal-950,#041410); display:block; margin-bottom:0.25rem;">Image (optional)</label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" style="font-size:0.8rem; width:100%; box-sizing:border-box;">
                </div>
                <button type="submit" class="btn" style="width:100%; padding:0.6rem; border-radius:10px; font-weight:600;">Add Category</button>
            </form>

            <?php foreach (get_categories() as $c):
                $slug = preg_replace('/[^a-z0-9]+/', '_', strtolower(preg_replace('/\s*\(.*?\)\s*/', '', $c['name'])));
                $slug = trim($slug, '_');
                if (!empty($c['image_path'])) { $imgUrl = '../' . $c['image_path']; }
                else { $imgUrl = "../assets/images/products/all/{$slug}.png"; }
                $pcount = (int) db()->query("SELECT COUNT(*) FROM products WHERE category_id=".(int)$c['id'])->fetchColumn();
            ?>
                <div class="prod-cat-card" draggable="true" data-id="<?php echo (int)$c['id']; ?>" style="background-image: url('<?php echo $imgUrl; ?>');">
                    <div class="prod-cat-card__overlay">
                        <div style="display:flex; justify-content:flex-end; align-items:center; width:100%; position:relative; z-index:5;">
                            <div class="card-actions">
                                <span role="button" tabindex="0" class="card-action-btn card-action-btn--drag" title="Drag to reorder category" aria-label="Drag to reorder category">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="9" cy="6" r="1.5"></circle>
                                        <circle cx="15" cy="6" r="1.5"></circle>
                                        <circle cx="9" cy="12" r="1.5"></circle>
                                        <circle cx="15" cy="12" r="1.5"></circle>
                                        <circle cx="9" cy="18" r="1.5"></circle>
                                        <circle cx="15" cy="18" r="1.5"></circle>
                                    </svg>
                                </span>
                                <label class="card-action-btn card-action-btn--edit" title="Change category image" aria-label="Change category image">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                        <circle cx="12" cy="13" r="4"></circle>
                                    </svg>
                                    <input type="file" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none;" onchange="uploadCategoryImage(<?php echo (int)$c['id']; ?>, this)">
                                </label>
                                <button type="button" class="card-action-btn card-action-btn--delete" title="Delete category" aria-label="Delete category" onclick="deleteCategory(<?php echo (int)$c['id']; ?>, this)">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="prod-cat-card__content">
                            <input type="text" class="inline-cat-field inline-cat-title" data-id="<?php echo (int)$c['id']; ?>" data-field="name" value="<?php echo h($c['name']); ?>" required placeholder="Category name" onblur="saveCategoryInline(this)">
                            <textarea class="inline-cat-field inline-cat-desc auto-grow" data-id="<?php echo (int)$c['id']; ?>" data-field="card_description" placeholder="Short description or tagline" onblur="saveCategoryInline(this)"><?php echo h($c['card_description'] ?: $c['tagline']); ?></textarea>
                            <div class="cat-card-footer">
                                <a href="products.php?cat=<?php echo urlencode($c['slug']); ?>" class="cat-manage-btn">
                                    Manage Products (<?php echo $pcount; ?>) →
                                </a>
                            </div>
                            <div class="save-status cat-save-status" style="font-size:0.75rem; color:rgba(255,255,255,0.85); margin-top:0.25rem; min-height:1em;"></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Category modals -->
        <?php foreach (get_categories() as $c):
            $slug = preg_replace('/[^a-z0-9]+/', '_', strtolower(preg_replace('/\s*\(.*?\)\s*/', '', $c['name'])));
            $slug = trim($slug, '_');
            if (!empty($c['image_path'])) { $imgUrl = '../' . $c['image_path']; }
            else { $imgUrl = "../assets/images/products/all/{$slug}.png"; }
            $pcount = (int) db()->query("SELECT COUNT(*) FROM products WHERE category_id=".(int)$c['id'])->fetchColumn();
        ?>
            <div class="modal-overlay" id="modal-cat-<?php echo h($c['slug']); ?>" onclick="if(event.target===this)closeModal('cat-<?php echo h($c['slug']); ?>')">
                <div class="modal">
                    <button type="button" class="modal-close" onclick="closeModal('cat-<?php echo h($c['slug']); ?>')">×</button>
                    <h2 class="modal-title">Preview &amp; Edit: <?php echo h($c['name']); ?></h2>

                    <div class="modal-preview">
                        <div class="cat-preview" style="background-image: url('<?php echo $imgUrl; ?>');">
                            <div class="cat-preview__overlay">
                                <input class="inline-input inline-badge" type="text" name="count_label" form="cform-<?php echo (int)$c['id']; ?>" value="<?php echo h($c['count_label']); ?>" data-preview="count_label" placeholder="14 Varieties" style="align-self:flex-start; padding:0.3rem 0.75rem; border-radius:999px; background:rgba(255,255,255,0.92); font-size:0.7rem; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; color:var(--teal-950,#041410); border:1px solid transparent; width:auto; max-width:160px;">
                                <div>
                                    <input class="inline-input" type="text" name="name" form="cform-<?php echo (int)$c['id']; ?>" value="<?php echo h($c['name']); ?>" data-preview="name" required style="font-family:var(--font-display); font-size:1.4rem; font-weight:700; color:#fff; background:transparent; border:1px solid transparent; width:100%;">
                                    <textarea class="inline-input inline-textarea" name="card_description" form="cform-<?php echo (int)$c['id']; ?>" data-preview="card_description" style="font-size:0.82rem; color:rgba(255,255,255,0.9); background:transparent; border:1px solid transparent; width:100%; resize:none; min-height:2.4em;"><?php echo h($c['card_description'] ?: $c['tagline']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <p class="hint">Click a value in the preview to edit it. Changes reflect live.</p>
                    </div>

                    <form method="POST" id="cform-<?php echo (int)$c['id']; ?>" action="products.php" enctype="multipart/form-data">
                        <input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
                        <input type="hidden" name="action" value="save_category">
                        <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
                        <div class="field-row single">
                            <div class="field"><label>Title (page heading)</label><input type="text" name="title" value="<?php echo h($c['title']); ?>" required></div>
                        </div>
                        <div class="field-row">
                            <div class="field"><label>Tagline (hero badge)</label><input type="text" name="tagline" value="<?php echo h($c['tagline']); ?>"></div>
                        </div>
                        <div class="field-row single">
                            <div class="field"><label>Subtitle (hero)</label><textarea name="subtitle"><?php echo h($c['subtitle']); ?></textarea></div>
                        </div>
                        <div class="field-row single">
                            <div class="field">
                                <label>Category Image</label>
                                <?php if (!empty($c['image_path'])): ?>
                                    <div style="margin-bottom:0.5rem;"><img src="../<?php echo h($c['image_path']); ?>" alt="Current" style="max-width:220px; max-height:140px; border-radius:10px; border:1px solid rgba(14,59,51,0.12); object-fit:cover;"></div>
                                <?php endif; ?>
                                <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" style="padding:0.4rem 0;">
                                <small class="hint">Upload a JPG/PNG/WEBP/GIF (max 5MB). Replaces the current image.</small>
                            </div>
                        </div>
                        <div class="modal-actions">
                            <button type="submit" class="btn">Save Changes</button>
                            <a class="btn ghost" href="products.php?cat=<?php echo urlencode($c['slug']); ?>" style="text-decoration:none;">Manage Products →</a>
                            <button type="button" class="btn ghost" onclick="closeModal('cat-<?php echo h($c['slug']); ?>')">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <div style="margin-bottom:1rem;">
            <a href="products.php" style="color:var(--teal-950,#041410); text-decoration:none; font-weight:600;">← All Categories</a>
        </div>
        <h1 class="admin-title"><?php echo h($selectedCat['name']); ?></h1>
        <p class="admin-subtitle">Edit products inline. <?php echo count(get_products_by_category((int)$selectedCat['id'])); ?> products.</p>

        <link rel="stylesheet" href="../styles/products.css">
        <div class="products-grid" style="margin-top:1.5rem;">
            <!-- Add new product card (inline form) - moved to top -->
            <form method="POST" action="products.php?cat=<?php echo urlencode($selectedCat['slug']); ?>" enctype="multipart/form-data" class="product-card glass-panel" style="text-align:left; padding:1.25rem; background:rgba(255,255,255,0.4); border:2px dashed rgba(14,59,51,0.2); display:flex; flex-direction:column; justify-content:center; min-height:280px;">
                <input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
                <input type="hidden" name="action" value="add_product">
                <input type="hidden" name="category_id" value="<?php echo (int)$selectedCat['id']; ?>">
                <div style="text-align:center; color:var(--teal-950,#041410); margin-bottom:0.75rem;">
                    <div style="font-size:2.5rem; line-height:1;">+</div>
                    <div style="font-weight:700; font-size:1.05rem;">Add New Product</div>
                </div>
                <div style="margin-bottom:0.5rem;">
                    <input type="text" name="name" required placeholder="Product name" style="width:100%; padding:0.5rem 0.7rem; border:1px solid rgba(14,59,51,0.15); border-radius:8px; font-size:0.9rem;">
                </div>
                <div style="margin-bottom:0.5rem;">
                    <textarea name="description" required placeholder="Description" style="width:100%; min-height:50px; padding:0.5rem 0.7rem; border:1px solid rgba(14,59,51,0.15); border-radius:8px; font-size:0.9rem; resize:vertical;"></textarea>
                </div>
                <div style="margin-bottom:0.75rem;">
                    <label style="font-size:0.78rem; font-weight:600; color:var(--teal-950,#041410);">Image (optional)</label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" style="padding:0.3rem 0; width:100%;">
                </div>
                <button type="submit" class="btn" style="width:100%;">Add Product</button>
            </form>

            <?php $prods = get_products_by_category((int)$selectedCat['id']); ?>
            <?php foreach ($prods as $p):
                $pslug = preg_replace('/[^a-z0-9]+/', '_', strtolower(preg_replace('/\s*\(.*?\)\s*/', '', $p['name'])));
                $pslug = trim($pslug, '_');
                $pimgUrl = "../assets/images/products/{$selectedCat['slug']}/{$pslug}.png";
            ?>
                <div class="product-card glass-panel" draggable="true" style="text-align:left; padding:0; background:rgba(255,255,255,0.82); position:relative;" data-id="<?php echo (int)$p['id']; ?>">
                    <div class="product-card__image-wrap" style="position:relative;">
                        <?php
                        if (!empty($p['image_path'])) { $pImgSrc = '../' . $p['image_path']; } else { $pImgSrc = $pimgUrl; }
                        ?>
                        <img src="<?php echo $pImgSrc; ?>" alt="<?php echo h($p['name']); ?>" class="product-card__image" loading="lazy" width="280" height="220" draggable="false" onerror="this.src='../assets/images/products/all/whole_spices.png'">
                        <span class="product-card__grade-badge">Grade A</span>
                        <div class="card-actions">
                            <span role="button" tabindex="0" class="card-action-btn card-action-btn--drag" title="Drag to reorder" aria-label="Drag to reorder">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="9" cy="6" r="1.5"></circle>
                                    <circle cx="15" cy="6" r="1.5"></circle>
                                    <circle cx="9" cy="12" r="1.5"></circle>
                                    <circle cx="15" cy="12" r="1.5"></circle>
                                    <circle cx="9" cy="18" r="1.5"></circle>
                                    <circle cx="15" cy="18" r="1.5"></circle>
                                </svg>
                            </span>
                            <label class="card-action-btn card-action-btn--edit" title="Change image" aria-label="Change image">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                    <circle cx="12" cy="13" r="4"></circle>
                                </svg>
                                <input type="file" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none;" onchange="uploadProductImage(<?php echo (int)$p['id']; ?>, this)">
                            </label>
                            <button type="button" class="card-action-btn card-action-btn--delete" title="Delete product" aria-label="Delete product" onclick="deleteProduct(<?php echo (int)$p['id']; ?>, this)">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="product-card__body">
                        <input type="text" class="inline-field" data-id="<?php echo (int)$p['id']; ?>" data-field="name" value="<?php echo h($p['name']); ?>" required style="font-size:1.35rem; font-weight:700; color:var(--teal-950,#041410); background:transparent; border:1px solid transparent; border-radius:6px; width:100%; padding:0.1rem 0.25rem; margin:-0.1rem -0.25rem 0.3rem; outline:none;" onfocus="this.style.background='rgba(255,255,255,0.95)'; this.style.borderColor='rgba(14,59,51,0.3)';" onblur="this.style.background='transparent'; this.style.borderColor='transparent'; saveProduct(this);">
                        <textarea class="inline-field auto-grow" data-id="<?php echo (int)$p['id']; ?>" data-field="description" required style="font-size:0.88rem; color:var(--ink-secondary,#5a7a72); background:transparent; border:1px solid transparent; border-radius:6px; width:100%; padding:0.1rem 0.25rem; margin:-0.1rem -0.25rem; outline:none; resize:none; overflow:hidden; min-height:1.6em;" onfocus="this.style.background='rgba(255,255,255,0.95)'; this.style.borderColor='rgba(14,59,51,0.3)';" onblur="this.style.background='transparent'; this.style.borderColor='transparent'; saveProduct(this);"><?php echo h($p['description']); ?></textarea>
                        <div class="save-status" style="font-size:0.72rem; color:var(--ink-secondary,#5a7a72); margin-top:0.4rem; min-height:1em;"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <script>
    var CSRF_TOKEN = <?php echo json_encode($csrf); ?>;
    var CAT_SLUG = <?php echo json_encode($selectedCat['slug'] ?? ''); ?>;
    var CAT_ID = <?php echo json_encode((int)($selectedCat['id'] ?? 0)); ?>;
    function autoGrow(ta) {
        ta.style.height = 'auto';
        ta.style.height = (ta.scrollHeight) + 'px';
    }
    document.querySelectorAll('textarea.auto-grow').forEach(function(ta) {
        autoGrow(ta);
        ta.addEventListener('input', function(){ autoGrow(ta); });
    });
    function saveProduct(el) {
        var card = el.closest('.product-card');
        var id = card.getAttribute('data-id');
        var nameInp = card.querySelector('[data-field="name"]');
        var descInp = card.querySelector('[data-field="description"]');
        var name = nameInp ? nameInp.value.trim() : '';
        var desc = descInp ? descInp.value.trim() : '';
        var status = card.querySelector('.save-status');
        if (!name) { if (status) status.textContent = 'Name required'; return; }
        if (status) status.textContent = 'Saving…';
        fetch('api/product-save.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ csrf: CSRF_TOKEN, id: parseInt(id), name: name, description: desc })
        }).then(function(r){ return r.json(); }).then(function(j){
            if (status) status.textContent = j && j.success ? 'Saved ✓' : (j.error || 'Save failed');
            setTimeout(function(){ if (status) status.textContent = ''; }, 1800);
        }).catch(function(){ if (status) status.textContent = 'Save failed'; });
    }
    function uploadProductImage(id, input) {
        if (!input.files || !input.files[0]) return;
        var card = input.closest('.product-card');
        var img = card.querySelector('.product-card__image');
        var status = card.querySelector('.save-status');
        var fd = new FormData();
        fd.append('csrf', CSRF_TOKEN);
        fd.append('id', id);
        fd.append('image', input.files[0]);
        if (status) status.textContent = 'Uploading…';
        fetch('api/product-image.php', { method: 'POST', body: fd })
            .then(function(r){ return r.json(); })
            .then(function(j){
                if (j && j.success && j.image_path) {
                    if (img) img.src = '../' + j.image_path + '?t=' + Date.now();
                    if (status) status.textContent = 'Image updated ✓';
                } else {
                    if (status) status.textContent = (j && j.error) ? j.error : 'Upload failed';
                }
                setTimeout(function(){ if (status) status.textContent = ''; }, 2000);
            })
            .catch(function(){ if (status) status.textContent = 'Upload failed'; });
        input.value = '';
    }
    function deleteProduct(id, btn) {
        if (!confirm('Delete this product?')) return;
        var card = btn.closest('.product-card');
        fetch('api/product-delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ csrf: CSRF_TOKEN, id: id })
        }).then(function(r){ return r.json(); }).then(function(j){
            if (j && j.success) { card.remove(); }
            else { alert(j && j.error ? j.error : 'Delete failed'); }
        }).catch(function(){ alert('Delete failed'); });
    }

    function openModal(id) {
        var el = document.getElementById('modal-' + id);
        if (el) {
            el.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    }
    function closeModal(id) {
        var el = document.getElementById('modal-' + id);
        if (el) {
            el.classList.remove('open');
            document.body.style.overflow = '';
        }
    }
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.open').forEach(function(m) {
                m.classList.remove('open');
                document.body.style.overflow = '';
            });
        }
    });

    function saveCategoryInline(el) {
        var card = el.closest('.prod-cat-card');
        var id = card.getAttribute('data-id');
        var field = el.getAttribute('data-field');
        var val = el.value.trim();
        var status = card.querySelector('.cat-save-status');
        if (field === 'name' && !val) {
            if (status) status.textContent = 'Name required';
            return;
        }
        var payload = { csrf: CSRF_TOKEN, id: parseInt(id, 10) };
        payload[field] = val;
        if (status) status.textContent = 'Saving…';
        fetch('api/category-save.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(function(r){ return r.json(); })
        .then(function(j){
            if (status) status.textContent = j && j.success ? 'Saved ✓' : (j.error || 'Save failed');
            setTimeout(function(){ if (status) status.textContent = ''; }, 1800);
        })
        .catch(function(){ if (status) status.textContent = 'Save failed'; });
    }

    function uploadCategoryImage(id, input) {
        if (!input.files || !input.files[0]) return;
        var card = input.closest('.prod-cat-card');
        var status = card.querySelector('.cat-save-status');
        var fd = new FormData();
        fd.append('csrf', CSRF_TOKEN);
        fd.append('id', id);
        fd.append('image', input.files[0]);
        if (status) status.textContent = 'Uploading image…';
        fetch('api/category-image.php', { method: 'POST', body: fd })
            .then(function(r){ return r.json(); })
            .then(function(j){
                if (j && j.success && j.image_path) {
                    card.style.backgroundImage = 'url("../' + j.image_path + '?t=' + Date.now() + '")';
                    if (status) status.textContent = 'Image updated ✓';
                } else {
                    if (status) status.textContent = (j && j.error) ? j.error : 'Upload failed';
                }
                setTimeout(function(){ if (status) status.textContent = ''; }, 2000);
            })
            .catch(function(){ if (status) status.textContent = 'Upload failed'; });
        input.value = '';
    }

    function deleteCategory(id, btn) {
        if (!confirm('Delete this category? All products inside it will also be deleted.')) return;
        var card = btn.closest('.prod-cat-card');
        fetch('api/category-delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ csrf: CSRF_TOKEN, id: id })
        }).then(function(r){ return r.json(); }).then(function(j){
            if (j && j.success) {
                card.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(function(){ card.remove(); }, 250);
            } else {
                alert(j && j.error ? j.error : 'Delete failed');
            }
        }).catch(function(){ alert('Delete failed'); });
    }

    // Shared toast feedback
    var globalToast = document.createElement('div');
    globalToast.className = 'reorder-toast';
    document.body.appendChild(globalToast);
    var toastTimer = null;
    function showToast(msg, duration) {
        globalToast.textContent = msg;
        globalToast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function() {
            globalToast.classList.remove('show');
        }, duration || 2000);
    }

    /* Generic Grid Drag-and-Drop Reordering */
    var activeDragHandle = null;
    document.addEventListener('mousedown', function(e) {
        activeDragHandle = e.target.closest('.card-action-btn--drag');
    }, true);
    window.addEventListener('mouseup', function() {
        activeDragHandle = null;
    });

    function setupGridReorder(gridSelector, cardSelector, apiEndpoint, extraParams) {
        var grid = document.querySelector(gridSelector);
        if (!grid) return;

        var draggedCard = null;
        var initialOrder = [];

        function getIds() {
            return Array.from(grid.querySelectorAll(cardSelector)).map(function(c) {
                return parseInt(c.getAttribute('data-id'), 10);
            }).filter(function(id) {
                return !isNaN(id) && id > 0;
            });
        }

        function saveOrder() {
            var currentOrder = getIds();
            if (currentOrder.length === 0 || JSON.stringify(currentOrder) === JSON.stringify(initialOrder)) return;
            initialOrder = currentOrder.slice();
            showToast('Saving order…', 4000);

            var payload = Object.assign({ csrf: CSRF_TOKEN, order: currentOrder }, extraParams || {});

            fetch(apiEndpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(function(r) { return r.json(); })
            .then(function(j) {
                if (j && j.success) {
                    showToast('Order saved ✓', 2000);
                } else {
                    showToast(j && j.error ? j.error : 'Failed to save order', 3000);
                }
            })
            .catch(function() {
                showToast('Failed to save order', 3000);
            });
        }

        // Touch support for mobile/tablets
        grid.querySelectorAll(cardSelector).forEach(function(card) {
            var handle = card.querySelector('.card-action-btn--drag');
            if (!handle) return;

            handle.addEventListener('touchstart', function(e) {
                draggedCard = card;
                initialOrder = getIds();
                card.classList.add('is-dragging');
            }, { passive: true });

            handle.addEventListener('touchmove', function(e) {
                if (!draggedCard) return;
                var touch = e.touches[0];
                var target = document.elementFromPoint(touch.clientX, touch.clientY);
                if (!target) return;
                var targetCard = target.closest(cardSelector);
                if (targetCard && targetCard !== draggedCard) {
                    var rect = targetCard.getBoundingClientRect();
                    var isAfter = (touch.clientX - rect.left) > (rect.width / 2);
                    if (isAfter) {
                        if (targetCard.nextElementSibling !== draggedCard) {
                            targetCard.after(draggedCard);
                        }
                    } else {
                        if (targetCard.previousElementSibling !== draggedCard) {
                            targetCard.before(draggedCard);
                        }
                    }
                }
                e.preventDefault();
            }, { passive: false });

            handle.addEventListener('touchend', function() {
                if (draggedCard) {
                    draggedCard.classList.remove('is-dragging');
                    draggedCard = null;
                    saveOrder();
                }
            });
        });

        // Desktop HTML5 Drag & Drop
        grid.addEventListener('dragstart', function(e) {
            var card = e.target.closest(cardSelector);
            // Require drag gesture to begin on the card's drag handle
            if (!card || !activeDragHandle || !card.contains(activeDragHandle)) {
                e.preventDefault();
                return false;
            }
            draggedCard = card;
            initialOrder = getIds();
            e.dataTransfer.effectAllowed = 'move';
            try {
                e.dataTransfer.setData('text/plain', card.getAttribute('data-id') || '');
            } catch (err) {}
            setTimeout(function() {
                if (draggedCard) draggedCard.classList.add('is-dragging');
            }, 0);
        });

        grid.addEventListener('dragover', function(e) {
            if (!draggedCard) return;
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';

            var targetCard = e.target.closest(cardSelector);
            if (!targetCard || targetCard === draggedCard) return;

            var rect = targetCard.getBoundingClientRect();
            var isAfter = (e.clientX - rect.left) > (rect.width / 2);

            if (isAfter) {
                if (targetCard.nextElementSibling !== draggedCard) {
                    targetCard.after(draggedCard);
                }
            } else {
                if (targetCard.previousElementSibling !== draggedCard) {
                    targetCard.before(draggedCard);
                }
            }
        });

        grid.addEventListener('dragend', function() {
            activeDragHandle = null;
            if (!draggedCard) return;
            draggedCard.classList.remove('is-dragging');
            draggedCard = null;
            saveOrder();
        });
    }

    // Initialize drag-and-drop for products and categories
    setupGridReorder('.products-grid', '.product-card[data-id]', 'api/product-reorder.php', { category_id: CAT_ID });
    setupGridReorder('.prod-cat-grid', '.prod-cat-card[data-id]', 'api/category-reorder.php');
    </script>
<?php require __DIR__ . '/partials/footer.php'; ?>