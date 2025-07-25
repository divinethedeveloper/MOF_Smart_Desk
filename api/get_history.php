<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all chat history for this user
$stmt = $mysqli->prepare('SELECT id, title, created_at FROM chat_history WHERE user_id = ? ORDER BY created_at DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$today = [];
$week = [];
$all = [];
$now = new DateTime();

while ($row = $result->fetch_assoc()) {
    $created = new DateTime($row['created_at']);
    $diff = $now->diff($created);
    $days = (int)$diff->format('%a');
    if ($days === 0) {
        $today[] = $row;
    } elseif ($days <= 7) {
        $week[] = $row;
    }
    $all[] = $row;
}

echo json_encode([
    'success' => true,
    'today' => $today,
    'week' => $week,
    'all' => $all
]); 