<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$chat_id = isset($_POST['chat_id']) ? (int)$_POST['chat_id'] : 0;
$title = isset($_POST['title']) ? trim($_POST['title']) : '';

if (!$chat_id || !$title) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

// Verify chat belongs to current user and update title
$stmt = $mysqli->prepare('UPDATE chat_history SET title = ? WHERE id = ? AND user_id = ?');
$stmt->bind_param('sii', $title, $chat_id, $_SESSION['user_id']);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update chat title']);
} 