<?php
header('Content-Type: application/json');
$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');

if ($mysqli->connect_errno) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$query = "SELECT * FROM current_affairs ORDER BY position ASC";
$result = $mysqli->query($query);

$positions = [];
while ($row = $result->fetch_assoc()) {
    $positions[] = $row;
}

echo json_encode($positions);
$mysqli->close();
?>