<?php
define('ADMIN_ENTRY', true);
require_once __DIR__ . '/lib/auth.php';

if (admin_count() === 0) {
    header('Location: setup.php');
    exit;
}

$errors = [];
$csrf = csrf_token(); // prime session before output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $token    = $_POST['csrf'] ?? null;
    if (!csrf_verify($token)) {
        $errors[] = 'Invalid CSRF token. Please reload the page.';
    } elseif (!attempt_login($username, $password)) {
        $errors[] = 'Invalid username or password.';
    } else {
        header('Location: inquiries.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Login — <?php echo SITE_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,400;1,9..144,600&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/global.css">
    <style>
        body { background: var(--bg-base, #f4f7f5); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: var(--font-body, sans-serif); position: relative; overflow: hidden; }
        .ambient { position: fixed; inset: 0; pointer-events: none; z-index: 0; }
        .ambient .blob { position: absolute; border-radius: 50%; filter: blur(60px); opacity: 0.35; }
        .ambient .b1 { width: 420px; height: 420px; background: radial-gradient(circle, rgba(39,165,136,0.4), transparent 70%); top: -120px; left: -80px; }
        .ambient .b2 { width: 360px; height: 360px; background: radial-gradient(circle, rgba(212,163,39,0.3), transparent 70%); top: 20%; right: -100px; }
        .login-card { position: relative; z-index: 1; max-width: 420px; width: 100%; padding: 2.5rem; border-radius: 24px; background: rgba(255,255,255,0.72); backdrop-filter: blur(14px); border: 1px solid rgba(255,255,255,0.9); box-shadow: 0 16px 48px rgba(14,59,51,0.12); }
        .login-card h1 { font-family: var(--font-display); font-size: 1.9rem; margin-bottom: 0.25rem; color: var(--teal-950, #041410); letter-spacing: -0.02em; }
        .login-card p { color: var(--ink-secondary, #5a7a72); font-size: 0.9rem; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem; color: var(--teal-950, #041410); }
        .form-group input { width: 100%; padding: 0.7rem 0.9rem; border: 1px solid rgba(14,59,51,0.12); border-radius: 10px; font-size: 0.95rem; background: rgba(255,255,255,0.9); }
        .btn { width: 100%; padding: 0.85rem; border: none; border-radius: 10px; background: linear-gradient(135deg, var(--teal-950, #041410), #0a2e28); color: #fff; font-weight: 700; font-size: 1rem; cursor: pointer; transition: transform 0.2s; }
        .btn:hover { transform: translateY(-1px); }
        .errors { background: rgba(198,40,40,0.1); color: #b00020; padding: 0.75rem 1rem; border-radius: 10px; margin-bottom: 1rem; font-size: 0.85rem; border: 1px solid rgba(198,40,40,0.2); }
    </style>
</head>
<body>
    <div class="ambient" aria-hidden="true">
        <div class="blob b1"></div>
        <div class="blob b2"></div>
    </div>
    <div class="login-card">
        <h1>Admin Login</h1>
        <p>Sign in to manage inquiries and site content.</p>
        <?php if (!empty($errors)): ?>
            <div class="errors"><?php echo h(implode(' ', $errors)); ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn">Sign In</button>
        </form>
    </div>
</body>
</html>