<?php
session_start();
header('Content-Type: application/json');

// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$title = isset($_POST['title']) ? trim($_POST['title']) : 'New Chat';
$chat_identifier = uniqid('chat_', true); // Generate unique identifier

// Use user_id = 1010 for guests
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 1010;

// Insert new chat session
$stmt = $mysqli->prepare('INSERT INTO chat_history (user_id, chat_identifier, title) VALUES (?, ?, ?)');
$stmt->bind_param('iss', $user_id, $chat_identifier, $title);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true, 
        'chat_id' => $mysqli->insert_id,
        'chat_identifier' => $chat_identifier
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create chat session']);
}
$stmt->close();
$mysqli->close(); 