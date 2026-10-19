<?php
define('ADMIN_ENTRY', true);
require_once __DIR__ . '/lib/auth.php';
logout();
header('Location: login.php');
exit;