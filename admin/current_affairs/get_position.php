<?php
header('Content-Type: application/json');
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'No ID provided']);
    exit;
}

$id = (int)$_GET['id'];
$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');

if ($mysqli->connect_errno) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$stmt = $mysqli->prepare("SELECT * FROM current_affairs WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Position not found']);
}

$mysqli->close();
?>