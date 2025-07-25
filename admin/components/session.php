<?php
// session.php
ini_set('session.use_only_cookies', 1); // Prevent session ID in URLs
ini_set('session.cookie_secure', 1); // Cookies only over HTTPS (enable in production with HTTPS)
ini_set('session.cookie_httponly', 1); // Prevent JavaScript access to session cookies
ini_set('session.cookie_samesite', 'Strict'); // Prevent CSRF by restricting cross-site requests
ini_set('session.cookie_lifetime', 0); // Cookie expires when browser closes
session_start();

// Regenerate session ID periodically or on privilege change
if (!isset($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 300) { // Regenerate every 5 minutes
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Check if admin session is not set
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    header('Location: ../');
    exit;
}
?>