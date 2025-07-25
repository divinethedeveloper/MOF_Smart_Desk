<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['chatId']) || !isset($data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');

if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$chatId = $mysqli->real_escape_string($data['chatId']);
$status = $mysqli->real_escape_string($data['status']);

$stmt = $mysqli->prepare("UPDATE chat_history SET status = ?, updated_at = NOW() WHERE chat_identifier = ?");
$stmt->bind_param('ss', $status, $chatId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $mysqli->error]);
}

$mysqli->close();
?>