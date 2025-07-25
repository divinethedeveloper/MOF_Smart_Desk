<?php
session_start();
$is_logged_in = "guess";
$is_logged_in = isset($_SESSION['user_id']) && isset($_SESSION['email']);
$user_email = $is_logged_in ? $_SESSION['email'] : null;

// Only run chat cleanup if logged in
if ($is_logged_in) {
    $mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
    if (!$mysqli->connect_errno) {
        $user_id = $_SESSION['user_id'];
        // Delete ALL 'New Chat' rows for this user
        $mysqli->query("DELETE FROM chat_history WHERE user_id = $user_id AND title = 'New Chat'");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Desk - Ministry of Finance - AI Assistant</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Stylesheets -->
  <link rel="stylesheet" href="styles/home.css">
  <link rel="stylesheet" href="styles/main.css">
  <link rel="stylesheet" href="styles/history.css">
  <link rel="stylesheet" href="styles/button.css">
  <link rel="stylesheet" href="styles/nav.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <!-- Favicons and Manifest -->
  <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
  <link rel="manifest" href="site.webmanifest">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="icon" type="image/png" sizes="192x192" href="android-chrome-192x192.png">
  <link rel="icon" type="image/png" sizes="512x512" href="android-chrome-512x512.png">

  <!-- Dark mode script -->
  <script>
    if (localStorage.getItem('darkMode') === 'enabled') {
      document.documentElement.classList.add('dark-mode');
      document.body.classList.add('dark-mode');
    }
  </script>
</head>

<body>
<div class="button-container">
    <button class="new-chat-button" id="floatingNewChatBtn">
      <img src="./qr_codes/qr-code.png" alt="QR Code Icon" style="width:32px;height:32px;object-fit:contain;display:block;" />
      <span class="tooltip">New Chat</span>
    </button>
</div>

    <?php require_once "./components/nav.php"?>
   

    <main class="chat-container-wrapper">
    <div class="history-sidebar" id="historySidebar">
    <div class="history-header">
        <h3>Chat History</h3>
        <button class="close-tab-button" id="toggleHistoryButton" title="Hide History">
            <svg id="arrowIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
    </div>
    
    <div class="action-buttons">
        <button class="action-btn active" id="newChatBtn">New chat</button>
        <button class="action-btn" id="faqsBtn" onclick="location.href='./faqs/'">FAQs</button>
        <button class="action-btn" id="resourcesBtn" onclick="location.href='./resources/'">Resources</button>
    </div>
    
    <div class="history-period-tabs">
        <button class="period-tab active" data-period="today">Today</button>
        <button class="period-tab" data-period="week">7 Days ago</button>
        <button class="period-tab" data-period="all">All time</button>
    </div>
    
    <div class="history-list-container">
        <div class="history-list active" id="todayHistory">
            <div class="history-item">
                <div class="history-title">How to restart my PC</div>
                <div class="history-time">2:45 PM</div>
            </div>
            <div class="history-item">
                <div class="history-title">Best practices for React state management</div>
                <div class="history-time">10:30 AM</div>
            </div>
        </div>
        
        <div class="history-list" id="weekHistory">
            <div class="history-item">
                <div class="history-title">JavaScript closure examples</div>
                <div class="history-time">Yesterday</div>
            </div>
            <div class="history-item">
                <div class="history-title">CSS grid vs flexbox</div>
                <div class="history-time">3 days ago</div>
            </div>
        </div>
        
        <div class="history-list" id="allHistory">
            <div class="history-item">
                <div class="history-title">Python data structures</div>
                <div class="history-time">2 weeks ago</div>
            </div>
            <div class="history-item">
                <div class="history-title">Machine learning basics</div>
                <div class="history-time">1 month ago</div>
            </div>
        </div>
        </div>
        </div>
 
        <div class="chat-interface">
            <!-- Welcome Screen -->
            <div class="welcome-screen" id="welcomeScreen">
                <img class="chat-logo" src="https://upload.wikimedia.org/wikipedia/commons/5/59/Coat_of_arms_of_Ghana.svg" alt="Ghana Coat of Arms" style="width:80px;height:80px;object-fit:contain;" />
                <h2 class="chat-headline">MOF Smart Desk</h2>
                <h4 class="chat-subheadline">
                    Providing Seamless Assistance to Ministry of Finance Staff, Every Step of the Way.
                </h4>
                <div class="button-row">
                    <a href="./faqs/" class="action-button">
                        <span class="button-icon-left">📄</span> Common Questions
                        <span class="button-arrow-right">→</span>
                    </a>
                    <a href="./resources/" class="action-button">
                        <span class="button-icon-left">📁</span> Resources 
                        <span class="button-arrow-right">→</span>
                    </a>
                </div>
            </div>

            <!-- Chat Messages Area (Hidden initially) -->
            <div class="chat-messages" id="chatMessages"></div>

            <!-- Typing Indicator -->
            <div class="typing-indicator" id="typingIndicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>

            <!-- Input Section -->
            <div class="input-section">
                <div class="message-input-wrapper">
                    <input type="text" class="message-input" id="messageInput" placeholder="Type your message">
                    <button class="send-button" id="sendButton" aria-label="Send Message">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <div class="custom-modal" id="privacyModal">
        <div class="custom-modal-content">
            <span class="custom-modal-close" id="privacyModalClose">&times;</span>
            <h2>Privacy Policy</h2>
            <section>
                <h3>Introduction</h3>
                <p>MoF SmartDesk is an application developed by the ICT Division of the Ministry of Finance to provide seamless support and information to staff and citizens. This policy explains how we handle your data and privacy.</p>
            </section>
            <section>
                <h3>Data Collection</h3>
                <p>We collect only the information necessary to provide our services, such as your login credentials, chat history, and preferences. No sensitive personal data is collected without your consent.</p>
            </section>
            <section>
                <h3>Usage of Data</h3>
                <p>Your data is used solely to enhance your experience, improve support, and ensure secure access to the platform. We do not share your data with third parties except as required by law.</p>
            </section>
            <section>
                <h3>Security</h3>
                <p>We implement industry-standard security measures to protect your information from unauthorized access, alteration, or disclosure.</p>
            </section>
            <section>
                <h3>Contact</h3>
                <p>If you have questions about this policy, please contact the ICT Division at the Ministry of Finance.</p>
            </section>
        </div>
    </div>


    <script src="./scripts/nav.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const welcomeScreen = document.getElementById('welcomeScreen');
            const chatMessages = document.getElementById('chatMessages');
            const messageInput = document.getElementById('messageInput');
            const sendButton = document.getElementById('sendButton');
            const typingIndicator = document.getElementById('typingIndicator');

            
            let isFirstMessage = true;
            let faqData = {}; // Will store our FAQ data
            let visualStepsData = {}; // Will store our Visual Steps data
            let currentChatId = null; // Store the current chat session ID
            let titleUpdated = false; // Track if chat title has been updated

            // Load Visual Steps data from JSON file
            async function loadVisualStepsData() {
                try {
                    const response = await fetch('./visual_steps.json');
                    if (!response.ok) {
                        throw new Error('Failed to load Visual Steps data');
                    }
                    visualStepsData = await response.json();
                    console.log('Visual Steps data loaded successfully');
                } catch (error) {
                    console.error('Error loading Visual Steps data:', error);
                    visualStepsData = {};
                }
            }

            // Load FAQ data from JSON file
            async function loadFAQData() {
                try {
                    const response = await fetch('./faq.json');
                    if (!response.ok) {
                        throw new Error('Failed to load FAQ data');
                    }
                    faqData = await response.json();
                    console.log('FAQ data loaded successfully');
                } catch (error) {
                    console.error('Error loading FAQ data:', error);
                    // Initialize with empty object if loading fails
                    faqData = {};
                }
            }

            // Create a new chat session when page loads
            async function createChatSession() {
                try {
                    const formData = new FormData();
                    formData.append('title', 'New Chat');
                    
                    const response = await fetch('./api/create_chat.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        currentChatId = data.chat_id;
                        console.log('Chat session created:', currentChatId);
                    } else {
                        console.error('Failed to create chat session:', data.message);
                    }
                } catch (error) {
                    console.error('Error creating chat session:', error);
                }
            }

            // Save message to database
            async function saveMessage(role, content) {
                if (!currentChatId) return;
                
                try {
                    const formData = new FormData();
                    formData.append('chat_id', currentChatId);
                    formData.append('role', role);
                    formData.append('content', content);
                    
                    const response = await fetch('./api/save_message.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        console.log('Message saved:', data.message_id);
                    } else {
                        console.error('Failed to save message:', data.message);
                    }
                } catch (error) {
                    console.error('Error saving message:', error);
                }
            }

            // Update chat title
            async function updateChatTitle(title) {
                if (!currentChatId || titleUpdated) return;
                
                try {
                    const formData = new FormData();
                    formData.append('chat_id', currentChatId);
                    formData.append('title', title);
                    
                    const response = await fetch('./api/update_chat_title.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        titleUpdated = true;
                        console.log('Chat title updated:', title);
                    } else {
                        console.error('Failed to update chat title:', data.message);
                    }
                } catch (error) {
                    console.error('Error updating chat title:', error);
                }
            }

            // Initialize by loading Visual Steps, FAQ data, and creating chat session
            loadVisualStepsData();
            loadFAQData();
            createChatSession();

            // Function to format the response with proper spacing for steps
            function formatResponse(response) {
                // Convert numbered lists to properly spaced items
                response = response.replace(/(\d+\.\s)/g, '\n$1');
                
                // Convert bullet points to properly spaced items
                response = response.replace(/(•\s)/g, '\n$1');
                
                // Convert dash points to properly spaced items
                response = response.replace(/(-\s)/g, '\n$1');
                
                // Convert asterisk points to properly spaced items
                response = response.replace(/(\*\s)/g, '\n$1');
                
                // Trim any excess whitespace
                return response.trim();
            }

            // Modified typeMessage function to handle formatted responses
            async function typeMessage(message, element) {
                element.innerHTML = '';
                const formattedMessage = formatResponse(message);
                
                // Create a temporary element to parse the formatted message
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = formattedMessage.replace(/\n/g, '<br>');
                
                // Process each character with HTML tags preserved
                let htmlToAdd = '';
                let tagBuffer = '';
                let inTag = false;
                
                for (let i = 0; i < formattedMessage.length; i++) {
                    const char = formattedMessage[i];
                    
                    if (char === '<') {
                        inTag = true;
                        tagBuffer += char;
                    } else if (char === '>' && inTag) {
                        tagBuffer += char;
                        htmlToAdd += tagBuffer;
                        tagBuffer = '';
                        inTag = false;
                    } else if (inTag) {
                        tagBuffer += char;
                    } else {
                        htmlToAdd += char;
                        element.innerHTML = htmlToAdd.replace(/\n/g, '<br>') + (tagBuffer ? tagBuffer : '');
                        await new Promise(resolve => setTimeout(resolve, 20));
                    }
                }
                
                // Add any remaining tag buffer
                if (tagBuffer) {
                    element.innerHTML += tagBuffer;
                }
            }

            // Function to check for keyword matches in Visual Steps
            function checkVisualStepsKeywords(message) {
                if (!visualStepsData || Object.keys(visualStepsData).length === 0) {
                    return null;
                }
                const lowerMessage = message.toLowerCase();
                for (const keywordGroup in visualStepsData) {
                    const config = visualStepsData[keywordGroup];
                    const keywords = keywordGroup.split(',');
                    let matches = 0;
                    for (const keyword of keywords) {
                        if (lowerMessage.includes(keyword.trim().toLowerCase())) {
                            matches++;
                        }
                    }
                    if (matches >= config.required_keywords) {
                        return {
                            steps: config.steps,
                            source: 'visual-steps'
                        };
                    }
                }
                return null;
            }

            // Function to check for keyword matches in FAQ
            function checkFAQKeywords(message) {
                if (!faqData || Object.keys(faqData).length === 0) {
                    return null;
                }

                const lowerMessage = message.toLowerCase();
                
                // First check standard single keyword responses
                if (faqData.standard_responses) {
                    for (const keyword in faqData.standard_responses) {
                        if (lowerMessage.includes(keyword.toLowerCase())) {
                            return {
                                response: faqData.standard_responses[keyword],
                                source: 'knowledge-base'
                            };
                        }
                    }
                }
                
                // Then check multi-keyword responses
                if (faqData.multi_keyword_responses) {
                    for (const keywordGroup in faqData.multi_keyword_responses) {
                        const config = faqData.multi_keyword_responses[keywordGroup];
                        const keywords = keywordGroup.split(',');
                        let matches = 0;
                        for (const keyword of keywords) {
                            if (lowerMessage.includes(keyword.trim().toLowerCase())) {
                                matches++;
                            }
                        }
                        if (matches >= config.required_keywords) {
                            return {
                                response: config.response,
                                source: 'knowledge-base'
                            };
                        }
                    }
                }
                
                return null;
            }

            // Function to render visual steps with images
            async function renderVisualSteps(steps, container) {
                container.innerHTML = '';
                for (let i = 0; i < steps.length; i++) {
                    const step = steps[i];
                    const stepDiv = document.createElement('div');
                    stepDiv.className = 'visual-step';
                    stepDiv.innerHTML = `<div class="visual-step-index">${i + 1}.</div><div class="visual-step-content">${step.text}${step.image ? `<br><img src='${step.image}' alt='Step ${i + 1}' class='visual-step-image'>` : ''}</div>`;
                    container.appendChild(stepDiv);
                    await new Promise(resolve => setTimeout(resolve, 200)); // Animate step-by-step
                }
            }

            // Modified getAIResponse function with enhanced system prompt
            async function getAIResponse(message) {
                try {
                    const response = await fetch('./api/together_proxy.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ message })
                    });

                    if (!response.ok) {
                        throw new Error(`API request failed with status ${response.status}`);
                    }

                    const data = await response.json();
                    if (data.success && data.response) {
                        return {
                            response: data.response,
                            source: 'ai-response'
                        };
                    } else {
                        throw new Error(data.error || 'Unexpected API response format');
                    }
                } catch (error) {
                    console.error('Error fetching AI response:', error);
                    return {
                        response: "I'm sorry, I couldn't process your request at this time. Please try again later.",
                        source: 'ai-error'
                    };
                }
            }

            // Function to send a message
            async function sendMessage() {
                const messageText = messageInput.value.trim();
                if (!messageText) return;
                // Clear input
                messageInput.value = '';
                // If first message, transition to chat interface
                if (isFirstMessage) {
                    isFirstMessage = false;
                    welcomeScreen.style.display = 'none';
                    chatMessages.style.display = 'flex';
                }
                // Add user message
                const userMessage = document.createElement('div');
                userMessage.className = 'message user-message';
                userMessage.textContent = messageText;
                chatMessages.appendChild(userMessage);
                // Save user message to database
                await saveMessage('user', messageText);
                // Update chat title with first user message (truncate if too long)
                if (!titleUpdated) {
                    const title = messageText.length > 50 ? messageText.substring(0, 50) + '...' : messageText;
                    await updateChatTitle(title);
                }
                // Scroll to bottom
                chatMessages.scrollTop = chatMessages.scrollHeight;
                // Show typing indicator
                typingIndicator.style.display = 'flex';
                chatMessages.appendChild(typingIndicator);
                chatMessages.scrollTop = chatMessages.scrollHeight;

                // 0. Check current affairs first
                let handled = false;
                try {
                    const affairsRes = await fetch('./api/current_affairs.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ question: messageText })
                    });
                    const affairsData = await affairsRes.json();
                    if (affairsData.success && affairsData.answer) {
                        typingIndicator.style.display = 'none';
                        const botMessage = document.createElement('div');
                        botMessage.className = 'message bot-message';
                        chatMessages.appendChild(botMessage);
                        await typeMessage(affairsData.answer, botMessage);
                        await saveMessage('assistant', affairsData.answer);
                        const sourceTag = document.createElement('div');
                        sourceTag.className = 'message-source knowledge-base';
                        sourceTag.textContent = 'Current Affairs';
                        botMessage.appendChild(sourceTag);
                        handled = true;
                    }
                } catch (e) {
                    // Ignore and fall back
                }
                if (handled) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                    return;
                }
                // 1. Check Visual Steps first
                const visualMatch = checkVisualStepsKeywords(messageText);
                if (visualMatch) {
                    await new Promise(resolve => setTimeout(resolve, 1000)); // Simulate processing
                    typingIndicator.style.display = 'none';
                    const botMessage = document.createElement('div');
                    botMessage.className = 'message bot-message visual-steps-message';
                    chatMessages.appendChild(botMessage);
                    await renderVisualSteps(visualMatch.steps, botMessage);
                    // Save AI response to database (as a single string for now)
                    const stepsText = visualMatch.steps.map((s, i) => `${i + 1}. ${s.text}`).join('\n');
                    await saveMessage('assistant', stepsText);
                    // Add source tag
                    const sourceTag = document.createElement('div');
                    sourceTag.className = `message-source ${visualMatch.source}`;
                    sourceTag.textContent = 'Step-by-Step Guide';
                    botMessage.appendChild(sourceTag);
                } else {
                    // 2. Check FAQ for keyword matches
                    const faqMatch = checkFAQKeywords(messageText);
                    if (faqMatch) {
                        await new Promise(resolve => setTimeout(resolve, 1000)); // Simulate processing
                        typingIndicator.style.display = 'none';
                        const botMessage = document.createElement('div');
                        botMessage.className = 'message bot-message';
                        chatMessages.appendChild(botMessage);
                        await typeMessage(faqMatch.response, botMessage);
                        await saveMessage('assistant', faqMatch.response);
                        const sourceTag = document.createElement('div');
                        sourceTag.className = `message-source ${faqMatch.source}`;
                        sourceTag.textContent = 'From Knowledge Base';
                        botMessage.appendChild(sourceTag);
                    } else {
                        // 3. No FAQ match - call Together API
                        try {
                            const aiResponse = await getAIResponse(messageText);
                            typingIndicator.style.display = 'none';
                            const botMessage = document.createElement('div');
                            botMessage.className = 'message bot-message';
                            chatMessages.appendChild(botMessage);
                            await typeMessage(aiResponse.response, botMessage);
                            await saveMessage('assistant', aiResponse.response);
                            const sourceTag = document.createElement('div');
                            sourceTag.className = `message-source ${aiResponse.source}`;
                            sourceTag.textContent = 'MOF Smart Desk';
                            botMessage.appendChild(sourceTag);
                        } catch (error) {
                            console.error('Error getting AI response:', error);
                            typingIndicator.style.display = 'none';
                            const botMessage = document.createElement('div');
                            botMessage.className = 'message bot-message';
                            botMessage.textContent = "I'm having trouble connecting to the assistance service. Please try again later.";
                            chatMessages.appendChild(botMessage);
                            await saveMessage('assistant', "I'm having trouble connecting to the assistance service. Please try again later.");
                            const sourceTag = document.createElement('div');
                            sourceTag.className = 'message-source ai-error';
                            sourceTag.textContent = 'System Error';
                            botMessage.appendChild(sourceTag);
                        }
                    }
                }
                // Scroll to bottom
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Event listeners
            sendButton.addEventListener('click', sendMessage);
            messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') sendMessage();
            });

            const darkModeButton = document.querySelector('.upgrade-button');
            darkModeButton.addEventListener('click', () => {
                document.body.classList.toggle('dark-mode');
                if (document.body.classList.contains('dark-mode')) {
                    darkModeButton.textContent = 'Light Mode';
                } else {
                    darkModeButton.textContent = 'Dark Mode';
                }
            });

            // Fetch and render chat history
            async function fetchAndRenderHistory() {
                try {
                    const response = await fetch('./api/get_history.php');
                    const data = await response.json();
                    if (!data.success) return;

                    // Helper to format time
                    function formatTime(ts) {
                        const date = new Date(ts.replace(' ', 'T'));
                        const now = new Date();
                        if (date.toDateString() === now.toDateString()) {
                            // Today: show HH:MM
                            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        } else if ((now - date) < 7 * 24 * 60 * 60 * 1000) {
                            // Last 7 days: show weekday
                            return date.toLocaleDateString([], { weekday: 'short' });
                        } else {
                            // All time: show date
                            return date.toLocaleDateString();
                        }
                    }

                    // Render a list
                    function renderList(list, containerId) {
                        const container = document.getElementById(containerId);
                        container.innerHTML = '';
                        if (!list.length) {
                            container.innerHTML = '<div class="history-item"><div class="history-title">No chats</div></div>';
                            return;
                        }
                        for (const chat of list) {
                            const item = document.createElement('div');
                            item.className = 'history-item';
                            item.dataset.chatId = chat.id;
                            item.innerHTML = `<div class="history-title">${chat.title}</div><div class="history-time">${formatTime(chat.created_at)}</div>`;
                            container.appendChild(item);
                        }
                    }

                    renderList(data.today, 'todayHistory');
                    renderList(data.week, 'weekHistory');
                    renderList(data.all, 'allHistory');
                } catch (error) {
                    console.error('Failed to fetch chat history:', error);
                }
            }

            // Call this on page load
            fetchAndRenderHistory();

            // Load and display messages for a given chat
            async function loadChatMessages(chatId) {
                try {
                    const response = await fetch(`./api/get_chat_messages.php?chat_id=${chatId}`);
                    const data = await response.json();
                    if (!data.success) return;

                    // Clear chat interface
                    chatMessages.innerHTML = '';
                    welcomeScreen.style.display = 'none';
                    chatMessages.style.display = 'flex';

                    // Show all messages
                    for (const msg of data.messages) {
                        const msgDiv = document.createElement('div');
                        msgDiv.className = 'message ' + (msg.role === 'user' ? 'user-message' : 'bot-message');
                        msgDiv.textContent = msg.content;
                        chatMessages.appendChild(msgDiv);
                    }
                    // Scroll to bottom
                    chatMessages.scrollTop = chatMessages.scrollHeight;

                    // Set current chat id
                    currentChatId = chatId;
                    titleUpdated = true; // Prevent title update for loaded chats
                } catch (error) {
                    console.error('Failed to load chat messages:', error);
                }
            }

            // Add click event to history items (delegated)
            document.querySelector('.history-sidebar').addEventListener('click', function(e) {
                const item = e.target.closest('.history-item');
                if (item && item.dataset.chatId) {
                    loadChatMessages(item.dataset.chatId);
                }
            });

            // New chat button refreshes the page
            document.getElementById('newChatBtn').addEventListener('click', function() {
                window.location.reload();
            });

            // Settings dropdown animation and toggle
            const settingsButton = document.getElementById('settingsButton');
            const settingsDropdown = document.getElementById('settingsDropdown');
            let settingsOpen = false;
            settingsButton.addEventListener('click', (e) => {
                e.stopPropagation();
                settingsOpen = !settingsOpen;
                if (settingsOpen) {
                    settingsDropdown.classList.add('open');
                } else {
                    settingsDropdown.classList.remove('open');
                }
            });
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (settingsOpen && !settingsDropdown.contains(e.target) && e.target !== settingsButton) {
                    settingsDropdown.classList.remove('open');
                    settingsOpen = false;
                }
            });

            // --- Settings Option Handlers ---
            // 1. Change Password
            document.getElementById('changePasswordOption').addEventListener('click', async () => {
                settingsDropdown.classList.remove('open');
                settingsOpen = false;
                const { value: formValues } = await Swal.fire({
                    title: 'Change Password',
                    html:
                        '<input id="swal-old-password" type="password" class="swal2-input" placeholder="Old Password">' +
                        '<input id="swal-new-password" type="password" class="swal2-input" placeholder="New Password">' +
                        '<input id="swal-confirm-password" type="password" class="swal2-input" placeholder="Confirm New Password">',
                    focusConfirm: false,
                    showCancelButton: true,
                    preConfirm: () => {
                        const oldPass = document.getElementById('swal-old-password').value;
                        const newPass = document.getElementById('swal-new-password').value;
                        const confirmPass = document.getElementById('swal-confirm-password').value;
                        if (!oldPass || !newPass || !confirmPass) {
                            Swal.showValidationMessage('All fields are required');
                            return false;
                        }
                        if (newPass !== confirmPass) {
                            Swal.showValidationMessage('New passwords do not match');
                            return false;
                        }
                        if (newPass.length < 6) {
                            Swal.showValidationMessage('New password must be at least 6 characters');
                            return false;
                        }
                        return { oldPass, newPass };
                    }
                });
                if (formValues) {
                    // Call PHP endpoint
                    const formData = new FormData();
                    formData.append('old_password', formValues.oldPass);
                    formData.append('new_password', formValues.newPass);
                    const response = await fetch('./api/change_password.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    if (data.success) {
                        Swal.fire('Success', 'Password changed successfully!', 'success');
                    } else {
                        Swal.fire('Error', data.message || 'Failed to change password', 'error');
                    }
                }
            });

            // 2. Clear Chat History
            document.getElementById('clearHistoryOption').addEventListener('click', async () => {
                settingsDropdown.classList.remove('open');
                settingsOpen = false;
                const result = await Swal.fire({
                    title: 'Clear Chat History?',
                    text: 'This will permanently delete all your chat history. This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, clear it!',
                    cancelButtonText: 'Cancel'
                });
                if (result.isConfirmed) {
                    const response = await fetch('./api/clear_history.php', { method: 'POST' });
                    const data = await response.json();
                    if (data.success) {
                        Swal.fire('Cleared!', 'Your chat history has been deleted.', 'success');
                        fetchAndRenderHistory();
                    } else {
                        Swal.fire('Error', data.message || 'Failed to clear history', 'error');
                    }
                }
            });

           
            // --- History Sidebar Minimize/Expand ---
            const historySidebar = document.getElementById('historySidebar');
            const toggleHistoryButton = document.getElementById('toggleHistoryButton');
            let isHistoryMinimized = false;
            // Create expand button
            const expandBtn = document.createElement('button');
            expandBtn.className = 'expand-history-btn';
            expandBtn.title = 'Show History';
            expandBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>`;
            historySidebar.parentNode.insertBefore(expandBtn, historySidebar);
            function updateSidebarState() {
                if (isHistoryMinimized) {
                    historySidebar.classList.add('minimized');
                    expandBtn.classList.add('visible');
                } else {
                    historySidebar.classList.remove('minimized');
                    expandBtn.classList.remove('visible');
                }
            }
            toggleHistoryButton.addEventListener('click', () => {
                isHistoryMinimized = true;
                updateSidebarState();
            });
            expandBtn.addEventListener('click', () => {
                isHistoryMinimized = false;
                updateSidebarState();
            });
            // Initial state
            updateSidebarState();

            // Login button for guest users
            const loginBtn = document.getElementById('loginBtn');
            if (loginBtn) {
                loginBtn.addEventListener('click', () => {
                    window.location.href = './auth/';
                });
            }

            // Floating New Chat Button refreshes the page
            document.getElementById('floatingNewChatBtn').addEventListener('click', function() {
                Swal.fire({
                    title: 'Scan this to contact MoF IT Support',
                    html: '<img src="./qr_codes/qr.png" alt="MoF IT Support QR Code" style="max-width: 200px; display: block; margin: 0 auto;">',
                    showConfirmButton: false,
                    showCloseButton: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeIn'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOut'
                    },
                    didOpen: () => {
                        // The QR code image is already in the HTML, so no need to fetch it here.
                        // If you want to fetch it dynamically, you'd do it here.
                    }
                });
            });

            // Hide history sidebar if not logged in
            const isLoggedIn = <?php echo $is_logged_in ? 'true' : 'false'; ?>;
            if (!isLoggedIn) {
                historySidebar.style.display = 'none';
            }
        });
    </script>
    <script src="scripts/hisotry.js"></script>
    <style>
        .settings-dropdown {
            position: absolute;
            top: 60px;
            right: 32px;
            min-width: 220px;
            background: var(--primary-bg);
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius-md);
            box-shadow: 0 8px 24px var(--shadow-medium);
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transform: translateY(-10px);
            transition: opacity 0.25s, transform 0.25s;
        }
        .settings-dropdown.open {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }
        .settings-dropdown ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .settings-dropdown li {
            border-bottom: 1px solid var(--border-light);
        }
        .settings-dropdown li:last-child {
            border-bottom: none;
        }
        .settings-option {
            width: 100%;
            background: none;
            border: none;
            padding: 16px 20px;
            text-align: left;
            font-size: 1rem;
            color: var(--text-dark);
            cursor: pointer;
            transition: background 0.15s;
        }
        .settings-option:hover {
            background: var(--header-bg);
        }
        body, .chat-content, .chat-interface, .message-input, .action-button, .settings-option, .history-title, .history-time {
            font-size: var(--mof-font-size, 16px) !important;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            cursor: pointer;
        }
        .avatar-dropdown {
            position: absolute;
            top: 48px;
            right: 0;
            min-width: 180px;
            background: var(--primary-bg);
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius-md);
            box-shadow: 0 8px 24px var(--shadow-medium);
            z-index: 1001;
            opacity: 0;
            pointer-events: none;
            transform: translateY(-10px);
            transition: opacity 0.25s, transform 0.25s;
        }
        .avatar-dropdown.open {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }
        .avatar-option {
            width: 100%;
            background: none;
            border: none;
            padding: 16px 20px;
            text-align: left;
            font-size: 1rem;
            color: var(--text-dark);
            cursor: pointer;
            transition: background 0.15s;
        }
        .avatar-option:hover {
            background: var(--header-bg);
        }
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            overflow: auto;
            background: rgba(0,0,0,0.35);
            align-items: center;
            justify-content: center;
        }
        .custom-modal.open {
            display: flex;
        }
        .custom-modal-content {
            background: var(--primary-bg);
            margin: auto;
            padding: 32px 24px;
            border-radius: var(--border-radius-md);
            max-width: 480px;
            width: 90vw;
            box-shadow: 0 8px 32px var(--shadow-medium);
            position: relative;
            max-height: 80vh;
            overflow-y: auto;
        }
        .custom-modal-content h2 {
            margin-top: 0;
        }
        .custom-modal-content section {
            margin-bottom: 18px;
        }
        .custom-modal-close {
            position: absolute;
            top: 16px;
            right: 18px;
            font-size: 1.5rem;
            color: #888;
            cursor: pointer;
        }
        .custom-modal-close:hover {
            color: #333;
        }
        .history-sidebar {
            width: 320px;
            min-width: 260px;
            max-width: 400px;
            background: var(--primary-bg);
            border-right: 1px solid var(--border-light);
            box-shadow: 2px 0 12px var(--shadow-light);
            transition: transform 0.3s cubic-bezier(.4,2,.6,1), opacity 0.3s;
            will-change: transform, opacity;
            position: relative;
            z-index: 10;
        }
        .history-sidebar.minimized {
            transform: translateX(-100%);
            opacity: 0;
            pointer-events: none;
        }
        .expand-history-btn {
            position: fixed;
            top: 13vh;
            left: 8px;
            z-index: 2002;
            background: var(--primary-bg);
            border: 1px solid var(--border-light);
            border-radius: 50%;
            box-shadow: 0 2px 8px var(--shadow-light);
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }
        .expand-history-btn.visible {
            opacity: 1;
            pointer-events: auto;
        }
        /* Add styles for visual steps */
        .visual-step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 18px;
            font-size: 1em;
        }
        .visual-step-index {
            font-weight: bold;
            margin-right: 12px;
            color: var(--accent-green, #007a3d);
            font-size: 1.1em;
        }
        .visual-step-content {
            flex: 1;
        }
        .visual-step-image {
            display: block;
            margin-top: 8px;
            max-width: 220px;
            border-radius: 8px;
            box-shadow: 0 2px 8px var(--shadow-light, #0001);
        }
    </style>
</body>
</html>