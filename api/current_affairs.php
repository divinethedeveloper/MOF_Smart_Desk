<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['question'])) {
    echo json_encode(['success' => false, 'error' => 'No question provided']);
    exit;
}

$question = trim($data['question']);
$question_lc = strtolower($question);
$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// 1. Ask Together API to analyze the question type
$analysis_prompt = "Analyze the following question and determine if it is:\n"
    . "1. Asking about CURRENT office holders (respond with CURRENT)\n"
    . "2. Asking about HISTORICAL information (respond with HISTORICAL)\n"
    . "3. Something else (respond with OTHER)\n\n"
    . "Question: \"$question\"\n\n"
    . "Respond with only one word: CURRENT, HISTORICAL, or OTHER";

$analysis_payload = [
    'model' => 'meta-llama/Llama-3-8b-chat-hf',
    'messages' => [
        ['role' => 'system', 'content' => 'You are an expert at analyzing questions about government officials.'],
        ['role' => 'user', 'content' => $analysis_prompt]
    ],
    'temperature' => 0.1,
    'max_tokens' => 10
];

$ch = curl_init('https://api.together.xyz/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer 5e33e8e11b89e8a9e67655927363c762c7b56f082a454e072458a4eecb5fa845'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($analysis_payload));
$analysis_response = curl_exec($ch);
$analysis_data = json_decode($analysis_response, true);
$question_type = trim(strtoupper($analysis_data['choices'][0]['message']['content'] ?? 'OTHER'));

// 2. Handle based on question type
if ($question_type === 'HISTORICAL') {
    // Let Together API handle historical questions directly
    $history_prompt = "You are an expert on Ghanaian political history. "
        . "Answer the following question accurately and concisely:\n\n"
        . "Question: $question";
    
    $history_payload = [
        'model' => 'meta-llama/Llama-3-8b-chat-hf',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a knowledgeable historian about Ghanaian politics.'],
            ['role' => 'user', 'content' => $history_prompt]
        ],
        'temperature' => 0.3,
        'max_tokens' => 150
    ];
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($history_payload));
    $history_response = curl_exec($ch);
    $history_data = json_decode($history_response, true);
    $answer = trim($history_data['choices'][0]['message']['content'] ?? '');
    
    echo json_encode([
        'success' => true,
        'answer' => $answer ?: "I couldn't find historical information about that.",
        'source' => 'Historical Records'
    ]);
    exit;
} 
else if ($question_type === 'CURRENT') {
    // Proceed with current position lookup
    $keywords_to_positions = [
        'president' => 'President',
        'vice president' => 'Vice President',
        'finance minister' => 'Minister, Finance',
        'minister of finance' => 'Minister, Finance',
        'finance' => 'Minister, Finance',
        'foreign affairs' => 'Minister, Foreign Affairs',
        'interior' => 'Minister, Interior',
        'defence' => 'Minister, Defence',
        'defense' => 'Minister, Defence',
        'attorney general' => 'Attorney General & Justice Minister',
        'justice minister' => 'Attorney General & Justice Minister',
        'education' => 'Minister, Education',
        'agriculture' => 'Minister, Food & Agriculture',
        'food' => 'Minister, Food & Agriculture',
        'fisheries' => 'Minister, Fisheries & Aquaculture',
        'trade' => 'Minister, Trade & Industry',
        'industry' => 'Minister, Trade & Industry',
        'health' => 'Minister, Health',
        'communications' => 'Minister, Communications, Digitalisation & Innovation',
        'digitalisation' => 'Minister, Communications, Digitalisation & Innovation',
        'energy' => 'Minister, Energy',
        'transport' => 'Minister, Transport',
        'roads' => 'Minister, Roads & Highways',
        'highways' => 'Minister, Roads & Highways',
        'lands' => 'Minister, Lands & Natural Resources',
        'natural resources' => 'Minister, Lands & Natural Resources',
        'environment' => 'Minister, Environment, Science & Technology',
        'science' => 'Minister, Environment, Science & Technology',
        'technology' => 'Minister, Environment, Science & Technology',
        'local government' => 'Minister, Local Government, Chieftaincy & Religious Affairs',
        'chieftaincy' => 'Minister, Local Government, Chieftaincy & Religious Affairs',
        'tourism' => 'Minister, Tourism, Culture & Creative Arts',
        'culture' => 'Minister, Tourism, Culture & Creative Arts',
        'creative arts' => 'Minister, Tourism, Culture & Creative Arts',
        'labour' => 'Minister, Labour, Jobs & Employment',
        'employment' => 'Minister, Labour, Jobs & Employment',
        'jobs' => 'Minister, Labour, Jobs & Employment',
        'works' => 'Minister, Works, Housing & Water Resources',
        'housing' => 'Minister, Works, Housing & Water Resources',
        'water' => 'Minister, Works, Housing & Water Resources',
        'youth' => 'Minister, Youth Development & Empowerment',
        'sports' => 'Minister, Sports & Recreation',
        'recreation' => 'Minister, Sports & Recreation',
        'gender' => 'Minister, Gender, Children & Social Protection',
        'children' => 'Minister, Gender, Children & Social Protection',
        'social protection' => 'Minister, Gender, Children & Social Protection'
    ];

    $matchedPosition = null;
    foreach ($keywords_to_positions as $keyword => $position) {
        if (strpos($question_lc, $keyword) !== false) {
            $matchedPosition = $position;
            break;
        }
    }

    // If no direct match, use Together API to match position
    if (!$matchedPosition) {
        $positions = [];
        $res = $mysqli->query("SELECT position FROM current_affairs");
        while ($row = $res->fetch_assoc()) {
            $positions[] = $row['position'];
        }

        $together_prompt = "Given the following user question: \"$question\"\nAnd the following list of positions: [\"" . implode('", "', $positions) . "\"]\nRespond ONLY with the exact position string from the list that best matches the user's intent. If none match, respond with NONE.";

        $together_payload = [
            'model' => 'meta-llama/Llama-3-8b-chat-hf',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an expert at mapping user questions to official government position titles. Be precise and match only if very confident.'],
                ['role' => 'user', 'content' => $together_prompt]
            ],
            'temperature' => 0.1,
            'max_tokens' => 50
        ];

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($together_payload));
        $together_response = curl_exec($ch);
        $together_data = json_decode($together_response, true);
        $best_position = trim($together_data['choices'][0]['message']['content'] ?? '');

        if ($best_position && $best_position !== 'NONE' && in_array($best_position, $positions)) {
            $matchedPosition = $best_position;
        }
    }

    // If we have a matched position, fetch the answer
    if ($matchedPosition) {
        $stmt = $mysqli->prepare("SELECT * FROM current_affairs WHERE position = ?");
        $stmt->bind_param('s', $matchedPosition);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            echo json_encode([
                'success' => true,
                'answer' => "The current {$row['position']} is {$row['name']} (since {$row['from_date']}).",
                'source' => 'Current Affairs'
            ]);
            exit;
        }
    }
}

// Fallback for finance-related questions
if (strpos($question_lc, 'finance') !== false) {
    echo json_encode([
        'success' => true,
        'answer' => "As of my last update, the Minister of Finance & Economic Planning is Cassiel Ato Forson (since 2025-01-22). For the most accurate information, please visit the official website: https://mofep.gov.gh",
        'source' => 'MOF Smart Desk'
    ]);
    exit;
}

// Final fallback
echo json_encode([
    'success' => false,
    'fallback' => true,
    'message' => "I couldn't find the information you requested. For historical questions, I'm still learning. For current information, please check official government sources."
]);
?>
