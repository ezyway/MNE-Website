<?php
define('ADMIN_ENTRY', true);
require_once __DIR__ . '/../admin/lib/db.php';

header('Content-Type: application/json; charset=utf-8');

function json_out(array $payload, int $code = 200): void {
    http_response_code($code);
    echo json_encode($payload);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(['success' => false, 'error' => 'Method not allowed.'], 405);
}

// Accept both JSON and form-encoded payloads
$raw = file_get_contents('php://input');
$data = [];
if ($raw !== '' && str_starts_with(trim($raw), '{')) {
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $data = $decoded;
    }
} else {
    $data = $_POST;
}

// Honeypot: bots fill this hidden field
if (!empty($data['website'] ?? '')) {
    // Pretend success to avoid tipping off the bot
    json_out(['success' => true, 'id' => null]);
}

$firstName = trim((string)($data['firstName'] ?? ''));
$lastName  = trim((string)($data['lastName'] ?? ''));
$company   = trim((string)($data['company'] ?? ''));
$destinationPort = trim((string)($data['destinationPort'] ?? ''));
$phone     = trim((string)($data['phone'] ?? ''));
$email     = trim((string)($data['email'] ?? ''));
$category  = trim((string)($data['commodityCategory'] ?? ''));
$incoterm  = trim((string)($data['incoterm'] ?? ''));
$message   = trim((string)($data['message'] ?? ''));

$errors = [];
if ($firstName === '' || mb_strlen($firstName) > 80) $errors[] = 'First name is required.';
if ($lastName === '' || mb_strlen($lastName) > 80) $errors[] = 'Last name is required.';
if ($company === '' || mb_strlen($company) > 120) $errors[] = 'Company name is required.';
if ($destinationPort === '' || mb_strlen($destinationPort) > 120) $errors[] = 'Destination port is required.';
if ($phone === '' || mb_strlen($phone) > 40) $errors[] = 'Phone number is required.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
if (mb_strlen($message) > 2000) $errors[] = 'Message is too long.';

if (!empty($errors)) {
    json_out(['success' => false, 'errors' => $errors], 422);
}

// Simple rate limit: max 5 submissions per IP per 10 minutes
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
$stmt = db()->prepare("SELECT COUNT(*) FROM inquiries WHERE ip_address = :ip AND created_at >= datetime('now', '-10 minutes')");
$stmt->execute([':ip' => $ip]);
if ((int)$stmt->fetchColumn() >= 5) {
    json_out(['success' => false, 'error' => 'Too many submissions. Please try again later.'], 429);
}

$insert = db()->prepare("
    INSERT INTO inquiries (first_name, last_name, company, destination_port, phone, email,
                           commodity_category, incoterm, message, ip_address, user_agent)
    VALUES (:fn, :ln, :co, :dp, :ph, :em, :cc, :ic, :msg, :ip, :ua)
");
$insert->execute([
    ':fn'  => $firstName,
    ':ln'  => $lastName,
    ':co'  => $company,
    ':dp'  => $destinationPort,
    ':ph'  => $phone,
    ':em'  => $email,
    ':cc'  => $category,
    ':ic'  => $incoterm,
    ':msg' => $message,
    ':ip'  => $ip,
    ':ua'  => $ua,
]);
$id = (int) db()->lastInsertId();

// Optional email notification (disabled by default; enable by creating mail config)
// mail_notification($id, ...); // implement when SMTP creds are available

json_out(['success' => true, 'id' => $id]);