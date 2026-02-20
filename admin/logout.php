<?php
/**
 * AlfaisalX Admin Logout
 */
require_once __DIR__ . '/includes/auth.php';

$auth->logout();
header('Location: login.php');
exit;
