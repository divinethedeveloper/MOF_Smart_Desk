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

        .chart-container {
            background-color: var(--white);
            border-radius: var(--card-radius);
            padding: var(--space-xl);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-size: 1.25rem;
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

        .data-table tr {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .data-table tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
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

        /* Add these new styles for the toggle switch */
        .toggle-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: var(--accent-green);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }

        .toggle-label {
            font-size: 0.8rem;
            color: var(--dark-gray);
            margin-top: 4px;
        }

        .toggle-label.reviewed {
            color: var(--accent-green);
        }

        .toggle-label.not-reviewed {
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
    <!-- Left Sidebar -->
    <?php require_once "../nav/index.php"?>

    <!-- Main Content -->
    <main class="main-content">
        <div class="header">
            <h1 class="page-title">Chat Analytics</h1>
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

        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Chat Records</h2>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Email</th>
                        <th>Chat ID</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Database connection
                    $mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
                    if ($mysqli->connect_errno) {
                        echo '<tr><td colspan="4">Database connection failed.</td></tr>';
                    } else {
                        // Fetch chat records with user info and status
                        $sql = "SELECT u.email, u.mof_staff_id, u.id as user_id, ch.chat_identifier, ch.status 
                                FROM chat_history ch 
                                JOIN users u ON ch.user_id = u.id 
                                ORDER BY ch.id DESC LIMIT 50";
                        $result = $mysqli->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $username = $row['mof_staff_id'] ? htmlspecialchars($row['mof_staff_id']) : 'User-' . $row['user_id'];
                                $email = htmlspecialchars($row['email']);
                                $chatId = htmlspecialchars($row['chat_identifier']);
                                $status = $row['status'] === 'reviewed' ? 'checked' : '';
                                $statusClass = $row['status'] === 'reviewed' ? 'reviewed' : 'not-reviewed';
                                $statusText = $row['status'] === 'reviewed' ? 'Reviewed' : 'Not Reviewed';
                                
                                echo "<tr data-chatid='{$chatId}'>
                                    <td>{$username}</td>
                                    <td>{$email}</td>
                                    <td>{$chatId}</td>
                                    <td>
                                        <div class='toggle-container'>
                                            <label class='toggle-switch'>
                                                <input type='checkbox' class='status-toggle' data-chatid='{$chatId}' {$status}>
                                                <span class='toggle-slider'></span>
                                            </label>
                                            <div class='toggle-label {$statusClass}'>{$statusText}</div>
                                        </div>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo '<tr><td colspan="4">No chat records found.</td></tr>';
                        }
                        $mysqli->close();
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', () => {
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

            // Add event listeners to all toggle switches
            document.querySelectorAll('.status-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const chatId = this.getAttribute('data-chatid');
                    const isReviewed = this.checked;
                    const statusLabel = this.closest('.toggle-container').querySelector('.toggle-label');
                    
                    // Update the UI immediately for better responsiveness
                    if (isReviewed) {
                        statusLabel.textContent = 'Reviewed';
                        statusLabel.classList.remove('not-reviewed');
                        statusLabel.classList.add('reviewed');
                    } else {
                        statusLabel.textContent = 'Not Reviewed';
                        statusLabel.classList.remove('reviewed');
                        statusLabel.classList.add('not-reviewed');
                    }

                    // Send AJAX request to update the database
                    fetch('update_chat_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            chatId: chatId,
                            status: isReviewed ? 'reviewed' : 'not reviewed'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            // Revert the UI if the update failed
                            this.checked = !isReviewed;
                            if (isReviewed) {
                                statusLabel.textContent = 'Not Reviewed';
                                statusLabel.classList.remove('reviewed');
                                statusLabel.classList.add('not-reviewed');
                            } else {
                                statusLabel.textContent = 'Reviewed';
                                statusLabel.classList.remove('not-reviewed');
                                statusLabel.classList.add('reviewed');
                            }
                            alert('Failed to update status. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert the UI if there was an error
                        this.checked = !isReviewed;
                        if (isReviewed) {
                            statusLabel.textContent = 'Not Reviewed';
                            statusLabel.classList.remove('reviewed');
                            statusLabel.classList.add('not-reviewed');
                        } else {
                            statusLabel.textContent = 'Reviewed';
                            statusLabel.classList.remove('not-reviewed');
                            statusLabel.classList.add('reviewed');
                        }
                        alert('An error occurred. Please try again.');
                    });
                });
            });

            // Make table rows clickable except when clicking the toggle
            document.querySelectorAll('.data-table tbody tr').forEach(row => {
                row.addEventListener('click', function(e) {
                    // If the click is on a toggle or inside the toggle container, do nothing
                    if (e.target.closest('.toggle-container')) return;
                    const chatId = this.getAttribute('data-chatid');
                    if (chatId) {
                        window.location.href = 'chat-details.php?chatId=' + chatId;
                    }
                });
            });
            // Prevent row click when toggling
            document.querySelectorAll('.status-toggle').forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
</body>
</html>