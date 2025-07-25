<?php
// Prevent any output before JSON
ob_start();

// Set JSON header
header('Content-Type: application/json');

// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');

if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $mysqli->connect_error]);
    ob_end_flush();
    exit;
}

// Fetch FAQs with categories
$query = "
    SELECT c.id AS category_id, c.title AS category_title, f.question, f.answer
    FROM faq_categories c
    LEFT JOIN faqs f ON c.id = f.category_id
    ORDER BY c.title, f.question
";
$result = $mysqli->query($query);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed: ' . $mysqli->error]);
    $mysqli->close();
    ob_end_flush();
    exit;
}

// Organize data into the required JSON structure
$categories = [];
$current_category = null;
while ($row = $result->fetch_assoc()) {
    if (!$current_category || $current_category['id'] !== $row['category_id']) {
        if ($current_category) {
            $categories[] = $current_category;
        }
        $current_category = [
            'id' => $row['category_id'],
            'title' => $row['category_title'],
            'questions' => []
        ];
    }
    if ($row['question']) {
        $current_category['questions'][] = [
            'question' => $row['question'],
            'answer' => $row['answer']
        ];
    }
}
if ($current_category) {
    $categories[] = $current_category;
}

$mysqli->close();

// Clear output buffer and send JSON
ob_end_clean();
echo json_encode(['categories' => $categories], JSON_PRETTY_PRINT);
?>