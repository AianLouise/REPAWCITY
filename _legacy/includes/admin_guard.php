<?php
/**
 * Admin Guard — verifies that the current user is logged in AND is an admin.
 * Include this at the top of every admin-*.php page.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

// Check if logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: ../auth/loginpage.php');
    exit;
}

// Verify the user is an admin (user_type = 1)
$user_id = $_SESSION['auth_user']['id'];
$stmt = $conn->prepare("SELECT user_type FROM user WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // User no longer exists
    session_destroy();
    header('Location: ../auth/loginpage.php');
    exit;
}

$user = $result->fetch_assoc();

if ($user['user_type'] != 1) {
    // Not an admin — redirect to home
    header('Location: ../index.php');
    exit;
}

$stmt->close();