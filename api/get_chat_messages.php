<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$chat_id = isset($_GET['chat_id']) ? (int)$_GET['chat_id'] : 0;
if (!$chat_id) {
    echo json_encode(['success' => false, 'message' => 'Missing chat_id']);
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Check chat ownership
$stmt = $mysqli->prepare('SELECT id FROM chat_history WHERE id = ? AND user_id = ?');
$stmt->bind_param('ii', $chat_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Chat not found or access denied']);
    exit;
}

// Fetch messages
$stmt = $mysqli->prepare('SELECT role, content, created_at FROM chat_messages WHERE chat_id = ? ORDER BY created_at ASC');
$stmt->bind_param('i', $chat_id);
$stmt->execute();
$res = $stmt->get_result();
$messages = [];
while ($row = $res->fetch_assoc()) {
    $messages[] = $row;
}
echo json_encode(['success' => true, 'messages' => $messages]); 