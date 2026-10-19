<?php
define('ADMIN_ENTRY', true);
require_once __DIR__ . '/lib/auth.php';
require_login();

seed_products_if_empty();

$pageTitle = 'Dashboard';
$inquiryTotal = (int) db()->query('SELECT COUNT(*) FROM inquiries')->fetchColumn();
$inquiryNew   = (int) db()->query("SELECT COUNT(*) FROM inquiries WHERE status = 'new'")->fetchColumn();
$catCount     = (int) db()->query('SELECT COUNT(*) FROM product_categories')->fetchColumn();
$prodCount    = (int) db()->query('SELECT COUNT(*) FROM products')->fetchColumn();
$adminCount   = (int) db()->query('SELECT COUNT(*) FROM admins')->fetchColumn();

require __DIR__ . '/partials/header.php';
?>
    <style>
        .dash-hero { margin-bottom: 2rem; }
        .dash-hero__eyebrow { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.12em; color: var(--ink-secondary, #5a7a72); font-weight: 700; margin-bottom: 0.5rem; }
        .dash-hero__title { font-family: var(--font-display); font-size: 2.4rem; line-height: 1.1; color: var(--teal-950, #041410); margin: 0 0 0.5rem; letter-spacing: -0.02em; }
        .dash-hero__sub { font-size: 1rem; color: var(--ink-secondary, #5a7a72); margin: 0; }

        .dash-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; margin-top: 1.5rem; margin-bottom: 2rem; }
        @media (max-width: 900px) { .dash-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 560px) { .dash-grid { grid-template-columns: 1fr; } }
        .dash-card { background: rgba(255,255,255,0.6); backdrop-filter: blur(14px); border: 1px solid rgba(255,255,255,0.9); border-radius: 24px; padding: 1.5rem; text-decoration: none; color: inherit; display: flex; flex-direction: column; gap: 0.4rem; transition: transform 0.3s var(--ease-apple, ease), box-shadow 0.3s ease, border-color 0.3s; position: relative; overflow: hidden; }
        .dash-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(14,59,51,0.12); border-color: rgba(212,163,39,0.4); }
        .dash-card__icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 0.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .dash-card__title { font-size: 0.95rem; font-weight: 700; color: var(--teal-950, #041410); text-transform: uppercase; letter-spacing: 0.06em; }
        .dash-card__stat { font-family: var(--font-display); font-size: 2.6rem; font-weight: 700; color: var(--teal-950, #041410); line-height: 1; letter-spacing: -0.03em; }
        .dash-card__meta { font-size: 0.85rem; color: var(--ink-secondary, #5a7a72); }
        .dash-card__badge { position: absolute; top: 1.25rem; right: 1.25rem; padding: 0.3rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 700; background: linear-gradient(135deg, #42a5f5, #1565c0); color: #fff; box-shadow: 0 2px 8px rgba(21,101,192,0.3); }
        .dash-card--disabled { opacity: 0.55; cursor: not-allowed; pointer-events: none; }

        .ic-inq { background: linear-gradient(135deg, #e3f2fd, #90caf9); }
        .ic-prod { background: linear-gradient(135deg, #e8f5e9, #a5d6a7); }
        .ic-content { background: linear-gradient(135deg, #fff3e0, #ffcc80); }
        .ic-settings { background: linear-gradient(135deg, #f3e5f5, #ce93d8); }
        .ic-users { background: linear-gradient(135deg, #fce4ec, #f48fb1); }

        .dash-section { margin-bottom: 2rem; }
        .dash-section__title { font-family: var(--font-display); font-size: 1.5rem; color: var(--teal-950, #041410); margin: 0 0 1rem; letter-spacing: -0.01em; }
        .dash-quick { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        .dash-quick a { padding: 0.75rem 1.3rem; border-radius: 12px; background: rgba(255,255,255,0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.9); color: var(--teal-950, #041410); text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: transform 0.2s, background 0.2s; }
        .dash-quick a:hover { transform: translateY(-2px); background: rgba(255,255,255,0.85); }
        .dash-quick a.primary { background: linear-gradient(135deg, var(--teal-950, #041410), #0a2e28); color: #fff; border-color: transparent; }

        .recent-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        .recent-table th, .recent-table td { padding: 0.85rem 0.6rem; text-align: left; border-bottom: 1px solid rgba(14,59,51,0.06); vertical-align: middle; }
        .recent-table th { font-size: 0.74rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--ink-secondary, #5a7a72); font-weight: 700; }
        .recent-table tr:hover td { background: rgba(39,165,136,0.04); }
        .recent-table a.view-link { color: var(--teal-950, #041410); font-weight: 600; text-decoration: none; }
    </style>

    <div class="dash-hero">
        <div class="dash-hero__eyebrow">Admin Dashboard</div>
        <h1 class="dash-hero__title">Welcome back, <?php echo h($_SESSION['admin_user']); ?>.</h1>
        <p class="dash-hero__sub">Here's an overview of your site today.</p>
    </div>

    <div class="dash-grid">
        <a href="inquiries.php" class="dash-card">
            <div class="dash-card__icon ic-inq">📥</div>
            <div class="dash-card__title">Inquiries</div>
            <div class="dash-card__stat"><?php echo $inquiryTotal; ?></div>
            <div class="dash-card__meta">Total received</div>
            <?php if ($inquiryNew > 0): ?>
                <span class="dash-card__badge"><?php echo $inquiryNew; ?> new</span>
            <?php endif; ?>
        </a>

        <a href="products.php" class="dash-card">
            <div class="dash-card__icon ic-prod">📦</div>
            <div class="dash-card__title">Products</div>
            <div class="dash-card__stat"><?php echo $prodCount; ?></div>
            <div class="dash-card__meta"><?php echo $catCount; ?> categories</div>
        </a>

        <div class="dash-card dash-card--disabled">
            <div class="dash-card__icon ic-content">📝</div>
            <div class="dash-card__title">Site Content</div>
            <div class="dash-card__stat">—</div>
            <div class="dash-card__meta">Coming soon</div>
        </div>

        <div class="dash-card dash-card--disabled">
            <div class="dash-card__icon ic-settings">⚙️</div>
            <div class="dash-card__title">Settings</div>
            <div class="dash-card__stat">—</div>
            <div class="dash-card__meta">Coming soon</div>
        </div>

        <div class="dash-card dash-card--disabled">
            <div class="dash-card__icon ic-users">👥</div>
            <div class="dash-card__title">Admin Users</div>
            <div class="dash-card__stat"><?php echo $adminCount; ?></div>
            <div class="dash-card__meta">Manage admins (soon)</div>
        </div>
    </div>

<?php require __DIR__ . '/partials/footer.php'; ?>