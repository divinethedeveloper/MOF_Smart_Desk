
<?php require_once "../components/session.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mof Smart Desk Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
        <link rel="stylesheet" href="../style/main.css">
        <link rel="stylesheet" href="../style/nav.css">

    <style>
        

       
        .user-actions {
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: var(--accent-blue);
            color: var(--white);
            display: flex;
            justify-content: center;
            font-weight: 600;
        }

        /* Metrics Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: var(--space-xl);
            margin-bottom: var(--space-xxl);
        }

        .metric-card {
            background-color: var(--white);
            border-radius: var(--card-radius);
            padding: var(--space-xl);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
        }

        .metric-card:hover {
            transform: translateY(-4px);
        }

        .metric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-md);
        }

        .metric-title {
            font-size: 1rem;
            color: var(--dark-gray);
            font-weight: 500;
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 600;
            margin: var(--space-md) 0;
        }

        .metric-trend {
            display: flex;
            align-items: center;
            gap: var(--space-xs);
            font-size: 0.875rem;
        }

        .trend-up {
            color: var(--accent-green);
        }

        .trend-down {
            color: var(--accent-red);
        }

        /* Charts Section */
        .section {
            margin-bottom: var(--space-xxl);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-xl);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
        }


        .wbg{
            background: white;
            padding: 2rem;
         }
        .section-container {
    background-color: var(--white);
    border-radius: 5rem;
    padding: var(--space-xl);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    height: 400px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
    color: var(--text-light);
    font-size: 1.25rem;
    overflow-y: auto; /* Allow scrolling if list exceeds height */
}

#topWordsList {
    list-style: none;
    padding: 0;
    margin: 0;
    width: 100%;
}

#topWordsList li {
    font-size: 1.2rem;
    line-height: 2.5; /* Increased line height for better spacing */
    color: var(--text-dark); /* Darker text for better readability */
    padding: 8px 0; /* Vertical padding for each list item */
    border-bottom: 1px solid rgba(0,0,0,0.1); /* Subtle separator line */
    width: 100%;
    display: block; /* Ensure full-width list items */
}

#topWordsList li:last-child {
    border-bottom: none; /* Remove border from last item */
}

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--white);
            border-radius: var(--card-radius);
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .data-table th, .data-table td {
            padding: var(--space-md);
            text-align: left;
            border-bottom: 1px solid var(--medium-gray);
        }

        .data-table th {
            background-color: var(--light-gray);
            font-weight: 600;
            color: var(--dark-gray);
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .status-badge {
            display: inline-block;
            padding: var(--space-xs) var(--space-sm);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
        }

        .badge-warning {
            background-color: rgba(249, 115, 22, 0.1);
            color: var(--accent-orange);
        }

        .badge-error {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--accent-red);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            body {
                grid-template-columns: 1fr;
            }
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 100;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                grid-column: 1;
                padding: var(--space-xl);
            }
            .metrics-grid {
                grid-template-columns: 1fr;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--space-md);
            }
        }
    </style>
</head>
<body>

    <?php require_once "../nav/index.php"?>
   

    <!-- Main Content -->
    <main class="main-content">
        <div class="header">
            <h1 class="page-title">Admin Dashboard</h1>
            <div class="user-actions">
                <div class="user-info">
                    <div class="user-avatar">AD</div>
                    <div>
                        <div>Admin User</div>
                        <div style="font-size: 0.875rem; color: var(--dark-gray);">Super Admin</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics -->
        <?php
        $mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
        $totalUsers = 0;
        $activeChats = 0;
        $faqViews = 0; // No table found, so set to 0
        $supportRequests = 0; // No table found, so set to 0
        $activeSessions = 0; // No session tracking, so set to 0
        if (!$mysqli->connect_errno) {
            // Total users
            $res = $mysqli->query('SELECT COUNT(*) as cnt FROM users');
            if ($res && $row = $res->fetch_assoc()) $totalUsers = $row['cnt'];
            // Active chats (chats with at least 1 message in last 24h)
            $res = $mysqli->query("SELECT COUNT(DISTINCT ch.id) as cnt FROM chat_history ch JOIN chat_messages m ON ch.id = m.chat_id WHERE m.created_at >= NOW() - INTERVAL 1 DAY");
            if ($res && $row = $res->fetch_assoc()) $activeChats = $row['cnt'];
            // FAQ Views, Support Requests, Active Sessions: set to 0 (no data)
            $mysqli->close();
        }
        ?>
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-title">Total Users</div>
                    <i class="fas fa-users" style="color: var(--accent-blue);"></i>
                </div>
                <div class="metric-value"><?php echo $totalUsers; ?></div>
                <div class="metric-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>&nbsp;</span>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-title">Active Chats</div>
                    <i class="fas fa-comments" style="color: var(--accent-purple);"></i>
                </div>
                <div class="metric-value"><?php echo $activeChats; ?></div>
                <div class="metric-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>&nbsp;</span>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-title">FAQ Views</div>
                    <i class="fas fa-question-circle" style="color: var(--accent-green);"></i>
                </div>
                <div class="metric-value">0</div>
                <div class="metric-trend trend-down">
                    <i class="fas fa-arrow-down"></i>
                    <span>&nbsp;</span>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-title">Support Requests</div>
                    <i class="fas fa-headset" style="color: var(--accent-orange);"></i>
                </div>
                <div class="metric-value">0</div>
                <div class="metric-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>&nbsp;</span>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-title">Active Sessions</div>
                    <i class="fas fa-user-clock" style="color: var(--accent-blue);"></i>
                </div>
                <div class="metric-value">0</div>
                <div class="metric-trend">
                    <i class="fas fa-minus"></i>
                    <span>No change</span>
                </div>
            </div>
            
             
        </div>

        <div class="section wbg">
            <div class="section-header">
                <h2 class="section-title">Chat Word Cloud</h2>
            </div>
            <div class="chart-container">
                <ul id="topWordsList" style="font-size:1.2rem;line-height:2;">
                    <!-- Top words will be inserted here -->
                </ul>
            </div>
        </div>

        <!-- Chats Over Time -->
        <!-- <div class="section">
            <div class="section-header">
                <h2 class="section-title">Chat Activity</h2>
                <div style="display: flex; gap: var(--space-sm);">
                    <button style="padding: var(--space-xs) var(--space-sm); border-radius: 6px; background: var(--white); border: 1px solid var(--medium-gray);">Daily</button>
                    <button style="padding: var(--space-xs) var(--space-sm); border-radius: 6px; background: var(--accent-blue); color: var(--white); border: none;">Weekly</button>
                    <button style="padding: var(--space-xs) var(--space-sm); border-radius: 6px; background: var(--white); border: 1px solid var(--medium-gray);">Monthly</button>
                </div>
            </div>
            <div class="chart-container">
                Chats Over Time Chart Visualization
            </div>
        </div> -->

        <!-- Recent Support Requests -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Recent Support Requests</h2>
                <button style="padding: var(--space-xs) var(--space-md); border-radius: 6px; background: var(--accent-blue); color: var(--white); border: none;">View All</button>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Issue Type</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Response Time</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <tr>
                        <td>#IT-4872</td>
                        <td>Login Issues</td>
                        <td>john.doe@gov.gh</td>
                        <td><span class="status-badge badge-success">Resolved</span></td>
                        <td>12 min</td>
                    </tr>
                    <tr>
                        <td>#IT-4871</td>
                        <td>System Error</td>
                        <td>jane.smith@gov.gh</td>
                        <td><span class="status-badge badge-warning">In Progress</span></td>
                        <td>38 min</td>
                    </tr>
                    <tr>
                        <td>#IT-4870</td>
                        <td>Password Reset</td>
                        <td>michael.kwesi@gov.gh</td>
                        <td><span class="status-badge badge-success">Resolved</span></td>
                        <td>8 min</td>
                    </tr>
                    <tr>
                        <td>#IT-4869</td>
                        <td>Feature Request</td>
                        <td>sarah.amponsah@gov.gh</td>
                        <td><span class="status-badge badge-error">Pending</span></td>
                        <td>2 hrs</td>
                    </tr> -->
                </tbody>
            </table>
        </div>

        
    </main>

    <script>
        // Mobile menu toggle would be implemented here
        document.addEventListener('DOMContentLoaded', () => {
            // For demo purposes - in a real app this would be more robust
            if (window.innerWidth < 768) {
                const menuToggle = document.createElement('button');
                menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                menuToggle.style.position = 'fixed';
                menuToggle.style.top = '20px';
                menuToggle.style.left = '20px';
                menuToggle.style.zIndex = '101';
                menuToggle.style.background = 'var(--accent-blue)';
                menuToggle.style.color = 'white';
                menuToggle.style.border = 'none';
                menuToggle.style.borderRadius = '50%';
                menuToggle.style.width = '40px';
                menuToggle.style.height = '40px';
                menuToggle.style.display = 'flex';
                menuToggle.style.alignItems = 'center';
                menuToggle.style.justifyContent = 'center';
                menuToggle.style.cursor = 'pointer';
                
                menuToggle.addEventListener('click', () => {
                    document.querySelector('.sidebar').classList.toggle('active');
                });
                
                document.body.appendChild(menuToggle);
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wordcloud2.js/1.1.2/wordcloud2.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('analytics/wordcloud.php')
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('topWordsList');
                list.innerHTML = '';
                data.forEach((item, idx) => {
                    const li = document.createElement('li');
                    li.textContent = `${idx + 1}. ${item.word} (${item.count} times)`;
                    list.appendChild(li);
                });
            });
    });
    </script>
</body>
</html>