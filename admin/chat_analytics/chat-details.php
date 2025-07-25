<?php
// Get chatId from query string
$chatId = isset($_GET['chatId']) ? $_GET['chatId'] : '';
if (!$chatId) {
    die('No chat specified.');
}

// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
if ($mysqli->connect_errno) {
    die('Database connection failed.');
}

// Fetch chat and user info
$stmt = $mysqli->prepare('SELECT ch.id as chat_db_id, ch.chat_identifier, ch.title, ch.created_at, u.email, u.mof_staff_id, u.id as user_id FROM chat_history ch JOIN users u ON ch.user_id = u.id WHERE ch.chat_identifier = ? LIMIT 1');
$stmt->bind_param('s', $chatId);
$stmt->execute();
$chatInfo = $stmt->get_result()->fetch_assoc();
if (!$chatInfo) {
    die('Chat not found.');
}

// Fetch messages
$stmt = $mysqli->prepare('SELECT role, content, created_at FROM chat_messages WHERE chat_id = ? ORDER BY created_at ASC');
$stmt->bind_param('i', $chatInfo['chat_db_id']);
$stmt->execute();
$messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Details - MoF Smart Desk Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="../style/main.css">
    <link rel="stylesheet" href="../style/nav.css">
    <style>
        :root {
            --primary-blue: #3B82F6;
            --secondary-green: #10B981;
            --light-gray-bg: #F9FAFB;
            --border-color: #E5E7EB;
            --text-color-dark: #1F2937;
            --text-color-light: #6B7280;
            --white: #FFFFFF;
            --card-radius: 8px;
            --space-xs: 4px;
            --space-sm: 8px;
            --space-md: 16px;
            --space-lg: 24px;
            --space-xl: 32px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-gray-bg);
            color: var(--text-color-dark);
            line-height: 1.6;
        }

      

        .back-link {
            display: inline-flex; /* Use flex for alignment */
            align-items: center;
            gap: var(--space-xs); /* Space between arrow and text */
            margin-bottom: var(--space-lg);
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        .back-link:hover {
            color: var(--text-color-dark);
            text-decoration: underline;
        }

        .chat-details-container {
            background: var(--white);
            border-radius: var(--card-radius);
            box-shadow: 0 10px 20px rgba(0,0,0,0.04); /* More subtle, spread out shadow */
            padding: var(--space-xl);
            border: 1px solid var(--border-color); /* Add a subtle border */
        }

        .chat-meta {
            background-color: var(--light-gray-bg);
            border-radius: var(--card-radius);
            padding: var(--space-md) var(--space-lg);
            margin-bottom: var(--space-xl);
            border: 1px solid var(--border-color);
            color: var(--text-color-dark);
        }
        .chat-meta div {
            margin-bottom: var(--space-sm);
        }
        .chat-meta div:last-child {
            margin-bottom: 0;
        }
        .chat-meta strong {
            color: var(--primary-blue);
            font-weight: 600;
            min-width: 80px; /* Align colons */
            display: inline-block;
        }
        .chat-meta span { /* For the actual data */
            color: var(--text-color-light);
        }

        .chat-thread {
            display: flex;
            flex-direction: column;
            gap: var(--space-lg); /* Increased gap between messages */
        }

        .chat-message {
            display: flex; /* Use flexbox for message layout */
            align-items: flex-start; /* Align meta and content to the top */
            padding: var(--space-md);
            border-radius: 12px; /* Slightly more rounded corners */
            max-width: 85%; /* Allow messages to be a bit wider */
            word-break: break-word;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02); /* Very subtle shadow for messages */
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .chat-message:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .chat-message.user {
            align-self: flex-start;
            background: #EBF5FF; /* Lighter blue for user messages */
            border: 1px solid #D1E7FF;
            color: var(--text-color-dark);
            margin-right: auto; /* Push to the left */
        }
        .chat-message.assistant {
            align-self: flex-end;
            background: #E6FFF6; /* Lighter green for assistant messages */
            border: 1px solid #CCF7E4;
            color: var(--text-color-dark);
            margin-left: auto; /* Push to the right */
        }

        .chat-message .meta {
            font-size: 0.8rem; /* Slightly smaller font for meta */
            color: var(--text-color-light);
            margin-bottom: var(--space-xs); /* Smaller margin below meta */
            display: block; /* Ensure meta takes its own line */
        }
        .chat-message .content {
            font-size: 0.95rem; /* Slightly larger content font */
            color: var(--text-color-dark);
        }

        .chat-message.user .meta {
            color: #5587d6; /* Darker blue for user meta */
        }
        .chat-message.assistant .meta {
            color: #0d9263; /* Darker green for assistant meta */
        }

        .chat-message.empty-chat {
            text-align: center;
            width: 100%;
            background: var(--light-gray-bg);
            color: var(--text-color-light);
            font-style: italic;
            border: 1px dashed var(--border-color);
            padding: var(--space-md);
            border-radius: var(--card-radius);
            box-shadow: none;
        }
    </style>
</head>
<body>
<?php require_once "../nav/index.php"; ?>
<main class="main-content">
    <a href="./" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
        </svg>
        Back to Chat Analytics
    </a>
    <div class="chat-details-container">
        <div class="chat-meta">
            <div><strong>Chat ID:</strong> <span><?php echo htmlspecialchars($chatInfo['chat_identifier']); ?></span></div>
            <div><strong>User:</strong> <span><?php echo $chatInfo['mof_staff_id'] ? htmlspecialchars($chatInfo['mof_staff_id']) : 'User-' . $chatInfo['user_id']; ?> (<?php echo htmlspecialchars($chatInfo['email']); ?>)</span></div>
            <div><strong>Started:</strong> <span><?php echo htmlspecialchars($chatInfo['created_at']); ?></span></div>
            <div><strong>Title:</strong> <span><?php echo htmlspecialchars($chatInfo['title']); ?></span></div>
        </div>
        <div class="chat-thread">
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="chat-message <?php echo $msg['role']; ?>">
                        <div>
                            <div class="meta"><?php echo ucfirst($msg['role']); ?> &middot; <?php echo htmlspecialchars($msg['created_at']); ?></div>
                            <div class="content"><?php echo nl2br(htmlspecialchars($msg['content'])); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="chat-message empty-chat">
                    No messages in this chat yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
</body>
</html>