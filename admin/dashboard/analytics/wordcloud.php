<?php
$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed']);
    exit;
}

$result = $mysqli->query("SELECT content FROM chat_messages");
$allText = '';
while ($row = $result->fetch_assoc()) {
    $allText .= ' ' . $row['content'];
}
$mysqli->close();

// Convert to lowercase, remove punctuation, and split into words
$allText = strtolower($allText);
$allText = preg_replace('/[^\p{L}\p{N}\s]/u', '', $allText); // Remove punctuation
$words = preg_split('/\s+/', $allText);

// Define stopwords to ignore
$stopwords = [
    'the','and','is','to','of','in','a','for','on','with','at','by','an','be','this','that','it','as','are','was','from','or','but','not','your','you','i','my','me','do','how','can','if','so','we','our','us','will','all','has','have','had','what','which','when','where','who','why','about','into','out','up','down','over','under','then','than','too','very','just','now','more','most','some','such','no','nor','only','own','same','so','than','too','very','s','t','can','will','don','should','ll'
];

// Count word frequencies
$freq = [];
foreach ($words as $word) {
    $word = trim($word);
    if ($word === '' || in_array($word, $stopwords)) continue;
    if (!isset($freq[$word])) $freq[$word] = 0;
    $freq[$word]++;
}

// Prepare for top 15 words
arsort($freq);
$topWords = [];
foreach (array_slice($freq, 0, 15, true) as $word => $count) {
    $topWords[] = ['word' => $word, 'count' => $count];
}

header('Content-Type: application/json');
echo json_encode($topWords);
