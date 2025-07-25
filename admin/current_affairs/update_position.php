<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['position']) || !isset($data['name']) || !isset($data['from_date'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');

if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$stmt = $mysqli->prepare("UPDATE current_affairs SET position = ?, name = ?, from_date = ?, to_date = ?, updated_on = NOW() WHERE id = ?");
$stmt->bind_param('ssssi', 
    $data['position'],
    $data['name'],
    $data['from_date'],
    $data['to_date'],
    $data['id']
);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $mysqli->error]);
}

$mysqli->close();
?>