<?php
define('ADMIN_ENTRY', true);
require_once __DIR__ . '/lib/auth.php';

// Only allow setup when no admin exists yet
if (admin_count() > 0) {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = false;
$csrf = csrf_token(); // prime session before output

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';
    $token    = $_POST['csrf'] ?? null;

    if (!csrf_verify($token)) {
        $errors[] = 'Invalid CSRF token. Please reload the page.';
    }
    if ($username === '' || strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = db()->prepare('INSERT INTO admins (username, password_hash) VALUES (:u, :h)');
        $stmt->execute([':u' => $username, ':h' => $hash]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Setup — <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../styles/global.css">
    <style>
        body { background: var(--bg-base, #f4f7f5); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: var(--font-body, sans-serif); }
        .setup-card { max-width: 440px; width: 100%; padding: 2.5rem; border-radius: 16px; background: #fff; box-shadow: 0 12px 40px rgba(0,0,0,0.08); }
        .setup-card h1 { font-size: 1.5rem; margin-bottom: 0.5rem; color: #0e3b33; }
        .setup-card p { color: #555; font-size: 0.9rem; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem; color: #0e3b33; }
        .form-group input { width: 100%; padding: 0.7rem 0.9rem; border: 1px solid #d8e2de; border-radius: 10px; font-size: 0.95rem; }
        .btn { width: 100%; padding: 0.85rem; border: none; border-radius: 10px; background: #0e3b33; color: #fff; font-weight: 700; font-size: 1rem; cursor: pointer; }
        .btn:hover { background: #0a2e28; }
        .errors { background: #fdecea; color: #b00020; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.85rem; }
        .errors ul { margin: 0; padding-left: 1.2rem; }
        .success { background: #e6f4ea; color: #1e6b3a; padding: 1rem; border-radius: 8px; font-size: 0.9rem; }
        .success a { color: #1e6b3a; font-weight: 700; }
    </style>
</head>
<body>
    <div class="setup-card">
        <?php if ($success): ?>
            <h1>Admin Created</h1>
            <div class="success">
                Your admin account has been created. <a href="login.php">Go to login →</a>
            </div>
        <?php else: ?>
            <h1>First-time Admin Setup</h1>
            <p>Create the administrator account for the inquiry inbox. This page disables itself after the first admin is created.</p>
            <?php if (!empty($errors)): ?>
                <div class="errors"><ul><?php foreach ($errors as $e): ?><li><?php echo h($e); ?></li><?php endforeach; ?></ul></div>
            <?php endif; ?>
            <form method="POST" action="setup.php">
                <input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autocomplete="username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label for="confirm">Confirm Password</label>
                    <input type="password" id="confirm" name="confirm" required autocomplete="new-password">
                </div>
                <button type="submit" class="btn">Create Admin Account</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>