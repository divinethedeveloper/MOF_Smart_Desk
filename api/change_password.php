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
$old_password = isset($_POST['old_password']) ? $_POST['old_password'] : '';
$new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';

if (!$old_password || !$new_password) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}
if (strlen($new_password) < 6) {
    echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters']);
    exit;
}

// Get current password hash
$stmt = $mysqli->prepare('SELECT password_hash FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($password_hash);
if ($stmt->fetch()) {
    $stmt->close();
    if (!password_verify($old_password, $password_hash)) {
        echo json_encode(['success' => false, 'message' => 'Old password is incorrect']);
        exit;
    }
    // Update password
    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt2 = $mysqli->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $stmt2->bind_param('si', $new_hash, $user_id);
    if ($stmt2->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update password']);
    }
    $stmt2->close();
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
} 