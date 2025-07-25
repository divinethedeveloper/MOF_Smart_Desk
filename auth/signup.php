<?php
header('Content-Type: application/json');

// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

$fullName = isset($_POST['fullName']) ? trim($_POST['fullName']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$staffId = isset($_POST['staffId']) ? trim($_POST['staffId']) : null;

if (!$fullName || !$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'All required fields must be filled.']);
    exit;
}

// Check if email already exists
$stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already registered.']);
    exit;
}
$stmt->close();

// Hash password
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$stmt = $mysqli->prepare('INSERT INTO users (email, password_hash, mof_staff_id, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())');
$role = 'staff';
$stmt->bind_param('ssss', $email, $passwordHash, $staffId, $role);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Account created successfully! Please log in.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create account.']);
} 