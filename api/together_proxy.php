<?php
header('Content-Type: application/json');

// Get the user message from POST
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['message'])) {
    echo json_encode(['success' => false, 'error' => 'No message provided']);
    exit;
}

// Fetch Together API key from database
$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}
$result = $mysqli->query("SELECT api_key FROM api_keys WHERE service_name = 'together' LIMIT 1");
if (!$result || $result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'API key not found']);
    exit;
}
$row = $result->fetch_assoc();
$api_key = $row['api_key'];

// Prepare Together API request
$api_url = 'https://api.together.xyz/v1/chat/completions';
$payload = [
    'model' => 'meta-llama/Llama-3-8b-chat-hf',
    'messages' => [
        [
            'role' => 'system',
            'content' => "You are MoF Smart Desk — a virtual assistant for the Ministry of Finance, Ghana.  
            \n\nGuidelines for responses:\n
            1. ALWAYS format step-by-step instructions with CLEAR numbering (1. 2. 3.) 
            and line breaks between each step\n
            2. For lists, use bullet points (•) with line breaks between items\n
            3. Keep responses concise and professional\n
            4. When providing multiple points, put each on a new line\n
            5. Use simple, clear language appropriate for government communications\n
            6. Maintain a respectful and formal tone at all times\n
            7. For procedures, break them down into distinct steps with spacing\n\nExample format for steps:\n
            1. First step description\n   \n
            2. Second step description\n   \n
            3. Third step description\n\nExample format for lists:\n• First item\n• Second item\n• Third item

            Try to avoid giving multiple alternatives. Just a the single best solution.

When asked about current affairs, political appointments, or government leadership, always clarify that your response may be outdated. Frame your answer like this:

    “As of my last update in [year], [answer]. However, this may be outdated. For the most accurate and up-to-date information, I recommend visiting [official website URL].”

Redirect the user to an official source depending on the subject:

    Ministry of Finance → https://mofep.gov.gh

    General Government Info → https://info.gov.gh

    Office of the President → https://presidency.gov.gh            "
            
        ],
        [
            'role' => 'user',
            'content' => $data['message']
        ]
    ],
    'temperature' => 0.7,
    'max_tokens' => 500
];

$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    echo json_encode(['success' => false, 'error' => 'Together API error', 'details' => $response]);
    exit;
}

$data = json_decode($response, true);
if (isset($data['choices'][0]['message']['content'])) {
    echo json_encode(['success' => true, 'response' => $data['choices'][0]['message']['content']]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid API response', 'details' => $data]);
}
?>