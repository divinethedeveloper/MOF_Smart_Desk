<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']) && isset($_SESSION['email']);
$user_email = $is_logged_in ? $_SESSION['email'] : null;
$page = 'faqs';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministry of Finance - Common Questions</title>
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="../styles/nav.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* ========== CSS Variables ========== */
        :root {
            /* Light Mode */
            --primary-bg: #FFFFFF;
            --secondary-bg: #F8F9FA;
            --text-primary: #2B2B2B;
            --text-secondary: #6C757D;
            --accent-primary: #007F5F;
            --accent-secondary: #635404;
            --border-color: #E0E0E0;
            --accent-green: #007F5F;
            --card-bg: #FFFFFF;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            
            /* Spacing */
            --space-xs: 0.5rem;
            --space-sm: 1rem;
            --space-md: 1.5rem;
            --space-lg: 2rem;
            --space-xl: 3rem;
            
            /* Border Radius */
            --radius-sm: 6px;
            --radius-md: 8px;
            
            /* Transition */
            --transition: all 0.2s ease;
        }

        /* Dark Mode */
        body.dark-mode {
            --primary-bg: #121212;
            --secondary-bg: #1E1E1E;
            --text-primary: #F0F0F0;
            --text-secondary: #B0B0B0;
            --accent-primary: #00C896;
            --accent-secondary: #FFD700;
            --border-color: #3A3A3A;
            --card-bg: #1E1E1E;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.3);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.3);
        }

        /* ========== Base Styles ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--primary-bg);
            color: var(--text-primary);
            line-height: 1.6;
            transition: var(--transition);
            overflow-y: auto;
        }

        .header {
            padding: 1.5rem;
        }

        /* ========== FAQ Container ========== */
        .faq-container {
            max-width: 800px;
            margin: var(--space-xl) auto;
            padding: 0 var(--space-md);
            overflow-y: auto;
        }

        /* ========== Category Styles ========== */
        .category {
            margin-bottom: var(--space-xl);
        }

        .category-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--accent-primary);
            margin-bottom: var(--space-md);
            padding-bottom: var(--space-xs);
            border-bottom: 2px solid var(--accent-secondary);
            position: relative;
        }

        .category-title::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: var(--accent-secondary);
            transition: width 0.3s ease;
        }

        /* ========== Question Items ========== */
        .question-item {
            background-color: var(--card-bg);
            border-radius: var(--radius-sm);
            margin-bottom: var(--space-sm);
            box-shadow: var(--shadow-sm);
            border-left: 3px solid transparent;
            transition: var(--transition);
        }

        .question-item:hover {
            box-shadow: var(--shadow-md);
            border-left-color: var(--accent-secondary);
        }

        .question-header {
            padding: var(--space-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 500;
        }

        .question-title {
            flex-grow: 1;
            position: relative;
            padding-left: var(--space-md);
        }

        .question-title::before {
            content: '•';
            position: absolute;
            left: 0;
            color: var(--accent-secondary);
        }

        .toggle-icon {
            transition: var(--transition);
            color: var(--accent-primary);
        }

        .question-item.expanded .toggle-icon {
            transform: rotate(180deg);
            color: var(--accent-secondary);
        }

        /* Answer Content */
        .answer-content {
            padding: 0 var(--space-md);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            color: var(--text-secondary);
        }

        .question-item.expanded .answer-content {
            padding: 0 var(--space-md) var(--space-md);
            max-height: 1000px;
            border-top: 1px solid var(--border-color);
        }

        /* Loading & Error States */
        .loading, .error-message {
            text-align: center;
            padding: var(--space-xl);
        }

        .loading {
            color: var(--text-secondary);
        }

        .error-message {
            color: #dc3545;
        }

        .loading::after {
            content: '...';
            display: inline-block;
            width: 1em;
            animation: ellipsis 1.5s infinite steps(4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .faq-container {
                padding: 0 var(--space-sm);
                margin: var(--space-lg) auto;
            }
            
            .category-title {
                font-size: 1.3rem;
            }
        }

        /* Animations */
        @keyframes ellipsis {
            0% { content: '.'; }
            33% { content: '..'; }
            66% { content: '...'; }
        }

        /* SweetAlert Dark Mode Support */
        body.dark-mode .swal2-popup {
            background: var(--primary-bg);
            color: var(--text-primary);
        }
    </style>
</head>
<body>
    <?php require_once "../components/nav.php"?>
    <main class="faq-container" id="faqContainer">
        <div class="loading">Loading frequently asked questions</div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const faqContainer = document.getElementById('faqContainer');
            
            // Load FAQ data from backend
            setTimeout(() => {
                fetch('./fetch_faqs.php')
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to load FAQ data');
                        return response.json();
                    })
                    .then(data => {
                        if (!data.categories) throw new Error('Invalid FAQ data format');
                        renderFAQ(data.categories);
                    })
                    .catch(error => {
                        console.error('Error loading FAQ:', error);
                        showError();
                    });
            }, 800);

            function showError() {
                faqContainer.innerHTML = `
                    <div class="error-message">
                        Could not load questions at this time. Please try again later.
                    </div>
                `;
            }

            function renderFAQ(categories) {
                let html = '';
                
                categories.forEach(category => {
                    html += `
                        <div class="category">
                            <h2 class="category-title">${category.title}</h2>
                    `;
                    
                    category.questions.forEach(question => {
                        // Replace \n with <br><br> for spacing between points
                        const formattedAnswer = question.answer.replace(/\n/g, '<br><br>');
                        html += `
                            <div class="question-item">
                                <div class="question-header">
                                    <div class="question-title">${question.question}</div>
                                    <svg class="toggle-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </div>
                                <div class="answer-content">${formattedAnswer}</div>
                            </div>
                        `;
                    });
                    
                    html += `</div>`;
                });
                
                faqContainer.innerHTML = html;
                
                // Add click handlers
                document.querySelectorAll('.question-header').forEach(header => {
                    header.addEventListener('click', () => {
                        const item = header.parentElement;
                        const isExpanding = !item.classList.contains('expanded');
                        
                        // Close other open items if this is expanding
                        if (isExpanding) {
                            document.querySelectorAll('.question-item.expanded').forEach(openItem => {
                                if (openItem !== item) {
                                    openItem.classList.remove('expanded');
                                }
                            });
                        }
                        
                        item.classList.toggle('expanded');
                    });
                });
            }

            // Dark mode toggle functionality
            const darkModeToggle = document.getElementById('darkModeToggle');
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', () => {
                    document.body.classList.toggle('dark-mode');
                    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
                });

                // Check for saved preference
                if (localStorage.getItem('darkMode') === 'true') {
                    document.body.classList.add('dark-mode');
                }
            }

            // QR code popup
            const floatingNewChatBtn = document.getElementById('floatingNewChatBtn');
            if (floatingNewChatBtn) {
                floatingNewChatBtn.addEventListener('click', () => {
                    Swal.fire({
                        title: 'Scan this to contact MoF IT Support',
                        html: `<img src="../qr_codes/qr.png" alt="MoF IT Support QR" style="width:220px;max-width:90vw;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,0.12);margin:18px 0 0 0;">`,
                        showConfirmButton: true,
                        confirmButtonText: 'Close',
                        background: 'var(--primary-bg)',
                        color: 'var(--text-primary)',
                        customClass: {
                            popup: 'swal2-animate swal2-fade-in',
                            title: 'swal2-title-custom',
                            confirmButton: 'swal2-confirm-custom'
                        }
                    });
                });
            }
        });
    </script>
    <script src="../scripts/nav.js"></script>
    <script src="./scripts/general.js"></script>
</body>
</html>