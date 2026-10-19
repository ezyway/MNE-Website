<?php
/**
 * Maruti Nandan Exports — Shared database layer (SQLite)
 * File-based storage; no external DB service required.
 */
define('DB_PATH', __DIR__ . '/../data/inquiries.db');

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $dir = dirname(DB_PATH);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    @chmod($dir, 0777);
    if (file_exists(DB_PATH)) {
        @chmod(DB_PATH, 0666);
    }
    $pdo = new PDO('sqlite:' . DB_PATH, null, null, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec('PRAGMA journal_mode = WAL;');
    $pdo->exec('PRAGMA foreign_keys = ON;');
    if (file_exists(DB_PATH . '-wal')) {
        @chmod(DB_PATH . '-wal', 0666);
    }
    if (file_exists(DB_PATH . '-shm')) {
        @chmod(DB_PATH . '-shm', 0666);
    }
    init_schema($pdo);
    return $pdo;
}

function init_schema(PDO $pdo): void {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS inquiries (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            company TEXT NOT NULL,
            destination_port TEXT NOT NULL,
            phone TEXT NOT NULL,
            email TEXT NOT NULL,
            commodity_category TEXT,
            incoterm TEXT,
            message TEXT,
            status TEXT NOT NULL DEFAULT 'new',
            notes TEXT,
            ip_address TEXT,
            user_agent TEXT,
            created_at TEXT NOT NULL DEFAULT (datetime('now')),
            updated_at TEXT
        );
        CREATE INDEX IF NOT EXISTS idx_inq_status  ON inquiries(status);
        CREATE INDEX IF NOT EXISTS idx_inq_created ON inquiries(created_at);
        CREATE INDEX IF NOT EXISTS idx_inq_ip      ON inquiries(ip_address);

        CREATE TABLE IF NOT EXISTS admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            created_at TEXT NOT NULL DEFAULT (datetime('now'))
        );

        CREATE TABLE IF NOT EXISTS product_categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            slug TEXT NOT NULL UNIQUE,
            name TEXT NOT NULL,
            title TEXT,
            tagline TEXT,
            subtitle TEXT,
            card_description TEXT,
            count_label TEXT,
            image_path TEXT,
            sort_order INTEGER NOT NULL DEFAULT 0,
            created_at TEXT NOT NULL DEFAULT (datetime('now')),
            updated_at TEXT
        );

        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            category_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            description TEXT,
            spec TEXT,
            image_path TEXT,
            sort_order INTEGER NOT NULL DEFAULT 0,
            created_at TEXT NOT NULL DEFAULT (datetime('now')),
            updated_at TEXT,
            FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE CASCADE
        );
        CREATE INDEX IF NOT EXISTS idx_products_cat ON products(category_id);
    ");
    // Lightweight migration: ensure card_description & image_path columns exist
    $cols = $pdo->query("PRAGMA table_info(product_categories)")->fetchAll(PDO::FETCH_COLUMN, 1);
    if (!in_array('card_description', $cols, true)) {
        $pdo->exec("ALTER TABLE product_categories ADD COLUMN card_description TEXT");
    }
    if (!in_array('image_path', $cols, true)) {
        $pdo->exec("ALTER TABLE product_categories ADD COLUMN image_path TEXT");
    }
    // Lightweight migration: ensure products.image_path exists
    $pcols = $pdo->query("PRAGMA table_info(products)")->fetchAll(PDO::FETCH_COLUMN, 1);
    if (!in_array('image_path', $pcols, true)) {
        $pdo->exec("ALTER TABLE products ADD COLUMN image_path TEXT");
    }
}

function admin_count(): int {
    return (int) db()->query('SELECT COUNT(*) FROM admins')->fetchColumn();
}

function seed_products_if_empty(): void {
    $pdo = db();
    $n = (int) $pdo->query('SELECT COUNT(*) FROM product_categories')->fetchColumn();
    if ($n > 0) {
        return;
    }
    $seedPath = __DIR__ . '/products-data.php';
    if (!file_exists($seedPath)) {
        return;
    }
    $data = require $seedPath;
    if (!is_array($data)) {
        return;
    }
    $mapping = $data['category_mapping'] ?? [];
    $all = $data['all'] ?? [];
    $allNames = $all['name'] ?? [];
    $allDescs = $all['description'] ?? [];
    $allCounts = $all['count'] ?? [];

    $catStmt = $pdo->prepare('INSERT INTO product_categories (slug, name, title, tagline, subtitle, card_description, count_label, sort_order) VALUES (:slug, :name, :title, :tagline, :subtitle, :card_desc, :count, :so)');
    $prodStmt = $pdo->prepare('INSERT INTO products (category_id, name, description, spec, sort_order) VALUES (:cid, :name, :desc, :spec, :so)');

    $pdo->beginTransaction();
    foreach ($mapping as $displayName => $slug) {
        $idx = array_search($displayName, $allNames, true);
        $desc = ($idx !== false && isset($allDescs[$idx])) ? $allDescs[$idx] : '';
        $count = ($idx !== false && isset($allCounts[$idx])) ? $allCounts[$idx] : '';
        $catData = $data[$slug] ?? [];
        $catStmt->execute([
            ':slug'      => $slug,
            ':name'      => $displayName,
            ':title'     => $catData['title'] ?? $displayName,
            ':tagline'   => $catData['tagline'] ?? '',
            ':subtitle'  => $catData['subtitle'] ?? '',
            ':card_desc' => $desc,
            ':count'     => $count,
            ':so'        => $idx === false ? 0 : $idx,
        ]);
        $catId = (int) $pdo->lastInsertId();
        $names = $catData['name'] ?? [];
        $descs = $catData['description'] ?? [];
        $specs = $catData['specs'] ?? [];
        foreach ($names as $i => $pname) {
            $prodStmt->execute([
                ':cid'  => $catId,
                ':name' => $pname,
                ':desc' => $descs[$i] ?? '',
                ':spec' => $specs[$i] ?? '',
                ':so'   => $i,
            ]);
        }
    }
    $pdo->commit();
}

function get_categories(): array {
    seed_products_if_empty();
    return db()->query('SELECT * FROM product_categories ORDER BY sort_order, id')->fetchAll();
}

function get_category_by_slug(string $slug): ?array {
    seed_products_if_empty();
    $stmt = db()->prepare('SELECT * FROM product_categories WHERE slug = :s LIMIT 1');
    $stmt->execute([':s' => $slug]);
    $r = $stmt->fetch();
    return $r ?: null;
}

function get_products_by_category(int $categoryId): array {
    seed_products_if_empty();
    $stmt = db()->prepare('SELECT * FROM products WHERE category_id = :c ORDER BY sort_order, id');
    $stmt->execute([':c' => $categoryId]);
    return $stmt->fetchAll();
}