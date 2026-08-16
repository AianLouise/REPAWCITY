<?php
/**
 * User Guard — verifies that the current user is logged in AND is a regular user.
 * Admins are redirected to the admin dashboard. Include at the top of every
 * user-only page (profile, notifications, booking flows).
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: ../auth/loginpage.php');
    exit;
}

if (!empty($_SESSION['auth_user']['user_type']) && $_SESSION['auth_user']['user_type'] == 1) {
    header('Location: ../admin/admin-dashboard.php');
    exit;
}
