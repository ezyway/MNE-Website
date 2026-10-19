<?php
if (!isset($pageTitle)) { $pageTitle = 'Admin'; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo h($pageTitle); ?> — <?php echo defined('SITE_NAME') ? SITE_NAME : 'Admin'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,400;1,9..144,600&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/global.css">
    <style>
        :root { color-scheme: light; }
        body.admin-body {
            background: var(--bg-base, #f4f7f5);
            min-height: 100vh;
            font-family: var(--font-body, 'Plus Jakarta Sans', sans-serif);
            color: var(--ink-primary, #0e3b33);
            position: relative;
            overflow-x: hidden;
        }
        /* Ambient background (mirrors client site) */
        .admin-ambient { position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden; }
        .admin-ambient .blob { position: absolute; border-radius: 50%; filter: blur(60px); opacity: 0.35; }
        .admin-ambient .blob-1 { width: 420px; height: 420px; background: radial-gradient(circle, rgba(39,165,136,0.4), transparent 70%); top: -120px; left: -80px; }
        .admin-ambient .blob-2 { width: 360px; height: 360px; background: radial-gradient(circle, rgba(212,163,39,0.3), transparent 70%); top: 20%; right: -100px; }
        .admin-ambient .blob-3 { width: 300px; height: 300px; background: radial-gradient(circle, rgba(14,59,51,0.25), transparent 70%); bottom: -80px; left: 30%; }
        .admin-topbar { position: sticky; top: 0; z-index: 50; background: rgba(4,20,16,0.85); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); color: #fff; padding: 0.85rem 1.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.08); }
        .admin-topbar .brand { font-family: var(--font-display); font-weight: 700; font-size: 1.15rem; letter-spacing: -0.01em; }
        .admin-topbar nav { display: flex; gap: 0.25rem; flex-wrap: wrap; }
        .admin-topbar nav a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 0.45rem 0.85rem; border-radius: 8px; font-size: 0.88rem; font-weight: 600; transition: background 0.2s, color 0.2s; }
        .admin-topbar nav a:hover { color: #fff; background: rgba(255,255,255,0.1); }
        .admin-topbar nav a.active { color: #fff; background: rgba(212,163,39,0.2); }
        .admin-wrap { position: relative; z-index: 1; max-width: 1280px; margin: 0 auto; padding: 2rem 1.5rem; }
        .glass-card { background: rgba(255,255,255,0.72); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); border: 1px solid rgba(255,255,255,0.9); border-radius: var(--radius-xl, 28px); box-shadow: 0 8px 32px rgba(14,59,51,0.06); padding: 1.5rem; }
        .card { background: rgba(255,255,255,0.72); backdrop-filter: blur(14px); border: 1px solid rgba(255,255,255,0.9); border-radius: var(--radius-xl, 28px); box-shadow: 0 8px 32px rgba(14,59,51,0.06); padding: 1.5rem; }
        .toolbar input, .toolbar select { padding: 0.6rem 0.9rem; border: 1px solid rgba(14,59,51,0.12); border-radius: 10px; font-size: 0.9rem; background: rgba(255,255,255,0.9); }
        .toolbar .btn { padding: 0.6rem 1.1rem; border: none; border-radius: 10px; background: var(--teal-950, #041410); color: #fff; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; font-size: 0.9rem; transition: transform 0.2s, box-shadow 0.2s; }
        .toolbar .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(14,59,51,0.2); }
        .toolbar .btn.secondary { background: rgba(14,59,51,0.08); color: var(--teal-950, #041410); }
        table.inq { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        table.inq th, table.inq td { padding: 0.8rem 0.6rem; text-align: left; border-bottom: 1px solid rgba(14,59,51,0.06); vertical-align: top; }
        table.inq th { font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--ink-secondary, #5a7a72); font-weight: 700; }
        table.inq tr:hover td { background: rgba(39,165,136,0.04); }
        .badge { display: inline-block; padding: 0.28rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; }
        .badge--new { background: rgba(21,101,192,0.12); color: #1565c0; }
        .badge--read { background: rgba(230,81,0,0.12); color: #e65100; }
        .badge--replied { background: rgba(46,125,50,0.12); color: #2e7d32; }
        .badge--archived { background: rgba(84,110,122,0.12); color: #546e7a; }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .detail-grid .field { background: rgba(245,247,245,0.8); padding: 0.85rem 1rem; border-radius: 12px; border: 1px solid rgba(14,59,51,0.06); }
        .detail-grid .field .label { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--ink-secondary, #5a7a72); font-weight: 700; margin-bottom: 0.25rem; }
        .detail-grid .field .value { font-size: 0.95rem; font-weight: 600; word-break: break-word; }
        .detail-grid .field.full { grid-column: 1 / -1; }
        .actions { display: flex; gap: 0.6rem; flex-wrap: wrap; margin-top: 1rem; }
        .actions .btn { padding: 0.65rem 1.2rem; border: none; border-radius: 10px; background: var(--teal-950, #041410); color: #fff; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; font-size: 0.88rem; transition: transform 0.2s; }
        .actions .btn:hover { transform: translateY(-1px); }
        .actions .btn.danger { background: #c62828; }
        .actions .btn.ghost { background: rgba(14,59,51,0.08); color: var(--teal-950, #041410); }
        .flash { padding: 0.85rem 1.1rem; border-radius: 12px; margin-bottom: 1rem; font-size: 0.9rem; backdrop-filter: blur(8px); }
        .flash.ok { background: rgba(46,125,50,0.12); color: #2e7d32; border: 1px solid rgba(46,125,50,0.2); }
        .flash.err { background: rgba(198,40,40,0.12); color: #b00020; border: 1px solid rgba(198,40,40,0.2); }
        textarea.notes { width: 100%; min-height: 120px; padding: 0.7rem 0.9rem; border: 1px solid rgba(14,59,51,0.12); border-radius: 12px; font-size: 0.9rem; font-family: inherit; background: rgba(255,255,255,0.9); }
        select.status-select { padding: 0.6rem 0.9rem; border: 1px solid rgba(14,59,51,0.12); border-radius: 10px; font-size: 0.9rem; background: rgba(255,255,255,0.9); }
        .pagination { display: flex; gap: 0.4rem; margin-top: 1rem; justify-content: center; }
        .pagination a, .pagination span { padding: 0.5rem 0.85rem; border-radius: 10px; text-decoration: none; font-size: 0.85rem; }
        .pagination a { background: rgba(14,59,51,0.08); color: var(--teal-950, #041410); }
        .pagination span.current { background: var(--teal-950, #041410); color: #fff; }
        .empty { text-align: center; padding: 3rem 1rem; color: var(--ink-secondary, #5a7a72); }
        h1.admin-title { font-family: var(--font-display); font-size: 2rem; color: var(--teal-950, #041410); margin: 0 0 0.25rem; letter-spacing: -0.02em; }
        h2.admin-subtitle { font-size: 0.95rem; color: var(--ink-secondary, #5a7a72); margin: 0 0 1.5rem; font-weight: 400; }
        @media (max-width: 720px) { .detail-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body class="admin-body">
    <div class="admin-ambient" aria-hidden="true">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>
    <header class="admin-topbar">
        <div class="brand">Maruti Nandan Exports · Admin</div>
        <nav>
            <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">Dashboard</a>
            <a href="inquiries.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'inquiries.php' ? 'active' : ''; ?>">Inquiries</a>
            <a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : ''; ?>">Products</a>
            <a href="logout.php">Logout (<?php echo h($_SESSION['admin_user'] ?? ''); ?>)</a>
        </nav>
    </header>
    <main class="admin-wrap">