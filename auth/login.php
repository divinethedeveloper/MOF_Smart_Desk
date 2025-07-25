<?php
session_start();
header('Content-Type: application/json');

// === Configuration ===
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mof_smartdesk');

// === Security Headers ===
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

// === Input Sanitization ===
function sanitize_email($email) {
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}

// === Rate Limiting / Bruteforce Prevention (Optional but recommended) ===
// This can be implemented via Redis, MySQL logs, or file tracking IPs with timestamps

// === Database Connection with Error Handling ===
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    error_log('DB Connection failed: ' . $mysqli->connect_error);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Internal server error.']);
    exit;
}

// === Securely Fetching Input ===
$email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

// === Fetch User Securely ===
$stmt = $mysqli->prepare('SELECT id, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
if (!$stmt) {
    error_log('Prepare failed: ' . $mysqli->error);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Internal server error.']);
    exit;
}
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// === Verify Password ===
if ($user && password_verify($password, $user['password_hash'])) {
    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    // Update last login timestamp
    $updateStmt = $mysqli->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
    if ($updateStmt) {
        $updateStmt->bind_param('i', $user['id']);
        $updateStmt->execute();
    }

    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role']
        ]
    ]);
} else {
    // Sleep briefly to slow down brute-force attempts
    usleep(300000); // 0.3 seconds

    echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
}
