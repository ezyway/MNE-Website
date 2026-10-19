<?php
define('ADMIN_ENTRY', true);
require_once __DIR__ . '/lib/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: inquiries.php');
    exit;
}

$flash = null;
$flashType = 'ok';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf'] ?? null;
    if (!csrf_verify($token)) {
        $flash = 'Invalid CSRF token.';
        $flashType = 'err';
    } else {
        $action = $_POST['action'] ?? 'save';
        if ($action === 'delete') {
            db()->prepare('DELETE FROM inquiries WHERE id = :id')->execute([':id' => $id]);
            header('Location: inquiries.php');
            exit;
        }
        $status = $_POST['status'] ?? 'new';
        $notes  = trim($_POST['notes'] ?? '');
        $valid = ['new','read','replied','archived'];
        if (!in_array($status, $valid, true)) { $status = 'new'; }
        db()->prepare('UPDATE inquiries SET status = :s, notes = :n, updated_at = datetime(\'now\') WHERE id = :id')
            ->execute([':s' => $status, ':n' => $notes, ':id' => $id]);
        $flash = 'Inquiry updated.';
    }
}

$stmt = db()->prepare('SELECT * FROM inquiries WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $id]);
$inq = $stmt->fetch();
if (!$inq) {
    header('Location: inquiries.php');
    exit;
}

// Auto-mark as read on first view if still new
if ($inq['status'] === 'new' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    db()->prepare("UPDATE inquiries SET status = 'read', updated_at = datetime('now') WHERE id = :id AND status = 'new'")
        ->execute([':id' => $id]);
    $inq['status'] = 'read';
}

$pageTitle = 'Inquiry #' . $id;
require __DIR__ . '/partials/header.php';

$waText = rawurlencode("Hi {$inq['first_name']}, regarding your inquiry ({$inq['commodity_category']}) to " . SITE_NAME . ".");
$waLink = 'https://wa.me/' . preg_replace('/\D/', '', $inq['phone']) . '?text=' . $waText;
$mailSubject = rawurlencode("Re: Quotation request — " . ($inq['commodity_category'] ?: 'Inquiry'));
$mailLink = 'mailto:' . $inq['email'] . '?subject=' . $mailSubject;
?>
    <div style="margin-bottom:1rem;">
        <a href="inquiries.php" style="color:#0e3b33; text-decoration:none; font-weight:600;">← Back to inquiries</a>
    </div>

    <?php if ($flash): ?>
        <div class="flash <?php echo $flashType === 'err' ? 'err' : 'ok'; ?>"><?php echo h($flash); ?></div>
    <?php endif; ?>

    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem; margin-bottom:1rem;">
            <h1 style="font-size:1.4rem; margin:0;">Inquiry #<?php echo (int)$inq['id']; ?></h1>
            <span class="<?php echo status_badge_class($inq['status']); ?>"><?php echo h($inq['status']); ?></span>
        </div>

        <div class="detail-grid">
            <div class="field"><div class="label">First Name</div><div class="value"><?php echo h($inq['first_name']); ?></div></div>
            <div class="field"><div class="label">Last Name</div><div class="value"><?php echo h($inq['last_name']); ?></div></div>
            <div class="field"><div class="label">Company</div><div class="value"><?php echo h($inq['company']); ?></div></div>
            <div class="field"><div class="label">Destination Port</div><div class="value"><?php echo h($inq['destination_port']); ?></div></div>
            <div class="field"><div class="label">Phone / WhatsApp</div><div class="value"><?php echo h($inq['phone']); ?></div></div>
            <div class="field"><div class="label">Email</div><div class="value"><?php echo h($inq['email']); ?></div></div>
            <div class="field"><div class="label">Commodity</div><div class="value"><?php echo h($inq['commodity_category'] ?: '—'); ?></div></div>
            <div class="field"><div class="label">Incoterm</div><div class="value"><?php echo h($inq['incoterm'] ?: '—'); ?></div></div>
            <div class="field full"><div class="label">Message / Specifications</div><div class="value" style="white-space:pre-wrap; font-weight:400;"><?php echo h($inq['message'] ?: '—'); ?></div></div>
            <div class="field"><div class="label">Received</div><div class="value"><?php echo h($inq['created_at']); ?></div></div>
            <div class="field"><div class="label">IP Address</div><div class="value"><?php echo h($inq['ip_address'] ?: '—'); ?></div></div>
        </div>

        <div class="actions">
            <a class="btn ghost" href="<?php echo $mailLink; ?>">Reply by Email</a>
            <a class="btn ghost" href="<?php echo $waLink; ?>" target="_blank" rel="noopener">WhatsApp</a>
        </div>
    </div>

    <div class="card" style="margin-top:1rem;">
        <h2 style="font-size:1.15rem; margin:0 0 0.75rem;">Manage</h2>
        <form method="POST" action="inquiry.php?id=<?php echo (int)$id; ?>">
            <input type="hidden" name="csrf" value="<?php echo h(csrf_token()); ?>">
            <input type="hidden" name="action" value="save">
            <div style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center; margin-bottom:0.75rem;">
                <label for="status" style="font-weight:600;">Status</label>
                <select id="status" name="status" class="status-select">
                    <?php foreach (['new','read','replied','archived'] as $s): ?>
                        <option value="<?php echo h($s); ?>" <?php echo $inq['status'] === $s ? 'selected' : ''; ?>><?php echo h(ucfirst($s)); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <label for="notes" style="font-weight:600; display:block; margin-bottom:0.35rem;">Internal Notes</label>
            <textarea id="notes" name="notes" class="notes"><?php echo h($inq['notes'] ?? ''); ?></textarea>
            <div class="actions">
                <button type="submit" class="btn">Save Changes</button>
            </div>
        </form>

        <form method="POST" action="inquiry.php?id=<?php echo (int)$id; ?>" onsubmit="return confirm('Delete this inquiry permanently?');" style="margin-top:0.75rem;">
            <input type="hidden" name="csrf" value="<?php echo h(csrf_token()); ?>">
            <input type="hidden" name="action" value="delete">
            <button type="submit" class="btn danger">Delete Inquiry</button>
        </form>
    </div>
<?php require __DIR__ . '/partials/footer.php'; ?>