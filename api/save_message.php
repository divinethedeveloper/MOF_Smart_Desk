<?php
session_start();
header('Content-Type: application/json');

// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$chat_id = isset($_POST['chat_id']) ? (int)$_POST['chat_id'] : 0;
$role = isset($_POST['role']) ? $_POST['role'] : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

if (!$chat_id || !$role || !$content) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

if (!in_array($role, ['user', 'assistant'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid role']);
    exit;
}

// Directly insert the message without chat ownership check
$stmt = $mysqli->prepare('INSERT INTO chat_messages (chat_id, role, content) VALUES (?, ?, ?)');
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'DB error: prepare failed']);
    exit;
}
$stmt->bind_param('iss', $chat_id, $role, $content);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message_id' => $mysqli->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save message']);
}
$stmt->close();
$mysqli->close();
// --- SQL to create a guest user with id 1010 ---
// INSERT INTO users (id, email, password_hash, mof_staff_id, role, created_at, updated_at) VALUES (1010, 'guest@mof.gov.gh', '', 'guest', 'guest', NOW(), NOW()); 