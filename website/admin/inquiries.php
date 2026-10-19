<?php
define('ADMIN_ENTRY', true);
require_once __DIR__ . '/lib/auth.php';
require_login();

$pageTitle = 'Inquiries';

$statusFilter = $_GET['status'] ?? 'all';
$categoryFilter = trim($_GET['category'] ?? '');
$search = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

$validStatuses = ['all', 'new', 'read', 'replied', 'archived'];
if (!in_array($statusFilter, $validStatuses, true)) {
    $statusFilter = 'all';
}

$where = [];
$params = [];
if ($statusFilter !== 'all') {
    $where[] = 'status = :status';
    $params[':status'] = $statusFilter;
}
if ($categoryFilter !== '') {
    $where[] = 'commodity_category = :cat';
    $params[':cat'] = $categoryFilter;
}
if ($search !== '') {
    $where[] = '(first_name LIKE :q OR last_name LIKE :q OR company LIKE :q OR email LIKE :q OR phone LIKE :q)';
    $params[':q'] = '%' . $search . '%';
}
$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

$countStmt = db()->prepare("SELECT COUNT(*) FROM inquiries $whereSql");
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));

$stmt = db()->prepare("SELECT id, first_name, last_name, company, email, phone, commodity_category, status, created_at FROM inquiries $whereSql ORDER BY created_at DESC, id DESC LIMIT :lim OFFSET :off");
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

// Distinct categories for filter
$catRows = db()->query("SELECT DISTINCT commodity_category FROM inquiries WHERE commodity_category IS NOT NULL AND commodity_category <> '' ORDER BY commodity_category")->fetchAll(PDO::FETCH_COLUMN);

// New count for badge
$newCount = (int) db()->query("SELECT COUNT(*) FROM inquiries WHERE status = 'new'")->fetchColumn();

// CSV export (must run before any output)
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $expStmt = db()->query("SELECT id, first_name, last_name, company, destination_port, phone, email, commodity_category, incoterm, message, status, notes, created_at FROM inquiries ORDER BY created_at DESC");
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="inquiries-' . date('Ymd-His') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID','First Name','Last Name','Company','Destination Port','Phone','Email','Commodity','Incoterm','Message','Status','Notes','Created At']);
    while ($r = $expStmt->fetch()) {
        fputcsv($out, array_values($r));
    }
    fclose($out);
    exit;
}

require __DIR__ . '/partials/header.php';
?>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:0.5rem;">
        <h1 class="admin-title">Inquiries <?php if ($newCount > 0): ?><span class="badge badge--new" style="vertical-align:middle; margin-left:0.5rem;"><?php echo $newCount; ?> new</span><?php endif; ?></h1>
        <p class="admin-subtitle">Manage quotation requests submitted through the contact form.</p>
        <a class="btn" style="background:#0e3b33; color:#fff; padding:0.55rem 1rem; border-radius:8px; text-decoration:none; font-weight:600; font-size:0.9rem;" href="inquiries.php?export=csv">Export CSV</a>
    </div>

    <form class="toolbar" method="GET" action="inquiries.php">
        <input type="text" name="q" placeholder="Search name, company, email, phone…" value="<?php echo h($search); ?>">
        <select name="status">
            <?php foreach ($validStatuses as $s): ?>
                <option value="<?php echo h($s); ?>" <?php echo $statusFilter === $s ? 'selected' : ''; ?>><?php echo h(ucfirst($s)); ?></option>
            <?php endforeach; ?>
        </select>
        <select name="category">
            <option value="">All commodities</option>
            <?php foreach ($catRows as $c): ?>
                <option value="<?php echo h($c); ?>" <?php echo $categoryFilter === $c ? 'selected' : ''; ?>><?php echo h($c); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn">Filter</button>
        <a class="btn secondary" href="inquiries.php">Reset</a>
    </form>

    <div class="card">
        <?php if (empty($rows)): ?>
            <div class="empty">No inquiries match your filters.</div>
        <?php else: ?>
            <div style="overflow-x:auto;">
                <table class="inq">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Commodity</th>
                            <th>Email / Phone</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?php echo (int)$r['id']; ?></td>
                                <td><?php echo h($r['first_name'] . ' ' . $r['last_name']); ?></td>
                                <td><?php echo h($r['company']); ?></td>
                                <td><?php echo h($r['commodity_category'] ?: '—'); ?></td>
                                <td>
                                    <div><?php echo h($r['email']); ?></div>
                                    <div style="color:#5a7a72; font-size:0.8rem;"><?php echo h($r['phone']); ?></div>
                                </td>
                                <td><span class="<?php echo status_badge_class($r['status']); ?>"><?php echo h($r['status']); ?></span></td>
                                <td style="white-space:nowrap; font-size:0.8rem; color:#5a7a72;"><?php echo h($r['created_at']); ?></td>
                                <td><a href="inquiry.php?id=<?php echo (int)$r['id']; ?>" style="color:#0e3b33; font-weight:600; text-decoration:none;">View →</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i === $page): ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php require __DIR__ . '/partials/footer.php'; ?>