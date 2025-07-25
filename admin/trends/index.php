<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trends Analysis | MOF Smart Desk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style/main.css">
    <link rel="stylesheet" href="../style/nav.css">
    <style>
        .main-content {
            padding: var(--space-xxl);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-xl);
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
        }
        
        .action-buttons {
            display: flex;
            gap: var(--space-md);
        }
        
        .btn {
            padding: var(--space-sm) var(--space-md);
            border-radius: var(--btn-radius);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: var(--space-xs);
        }
        
        .btn-primary {
            background-color: var(--accent-blue);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: var(--accent-blue-dark);
        }
        
        .filter-section {
            margin-bottom: var(--space-lg);
            display: flex;
            gap: var(--space-md);
            align-items: center;
        }
        
        .filter-section select {
            padding: var(--space-sm);
            border: 1px solid var(--medium-gray);
            border-radius: var(--btn-radius);
            font-size: 1rem;
        }
        
        .filter-section select:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 5px rgba(99, 102, 241, 0.3);
        }
        
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: var(--space-lg);
            margin-bottom: var(--space-xl);
        }
        
        .metric-card {
            background-color: var(--white);
            border-radius: var(--card-radius);
            padding: var(--space-md);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-align: center;
        }
        
        .metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }
        
        .metric-card h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: var(--space-xs);
        }
        
        .metric-card p {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-blue);
        }
        
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
            position: sticky;
            top: 0;
        }
        
        .data-table tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tr:hover {
            background-color: var(--light-gray);
        }
        
        .category-badge {
            display: inline-block;
            padding: var(--space-xs) var(--space-sm);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-budget {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
        }
        
        .badge-policy {
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--accent-purple);
        }
        
        .badge-support {
            background-color: rgba(255, 193, 7, 0.1);
            color: #FFC107;
        }
        
        .chart-section {
            margin-top: var(--space-xl);
            background-color: var(--white);
            border-radius: var(--card-radius);
            padding: var(--space-lg);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .chart-section h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: var(--space-md);
        }
        
        .chart-placeholder {
            text-align: center;
            color: var(--dark-gray);
            font-size: 1rem;
            padding: var(--space-lg);
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--space-md);
            }
            
            .action-buttons {
                width: 100%;
                justify-content: flex-end;
            }
            
            .metrics-grid {
                grid-template-columns: 1fr;
            }
            
            .data-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <?php require_once "../nav/index.php"?>
    
    <main class="main-content">
        <div class="header">
            <h1 class="page-title">Trends Analysis</h1>
            <div class="action-buttons">
                <button class="btn btn-primary" id="refreshTrendsBtn">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
        
        <div class="filter-section">
            <label for="timeRange">Time Range:</label>
            <select id="timeRange" name="timeRange">
                <option value="7days" selected>Last 7 Days</option>
                <option value="30days">Last 30 Days</option>
                <option value="all">All Time</option>
            </select>
            <button class="btn btn-primary" id="applyFilterBtn">
                <i class="fas fa-filter"></i> Apply
            </button>
        </div>
        
        <div class="metrics-grid">
            <div class="metric-card">
                <h3>Total Queries</h3>
                <p>1,245</p>
            </div>
            <div class="metric-card">
                <h3>Unique Users</h3>
                <p>142</p>
            </div>
            <div class="metric-card">
                <h3>Avg. Response Time</h3>
                <p>1.2s</p>
            </div>
            <div class="metric-card">
                <h3>Most Active Hour</h3>
                <p>10:00 AM</p>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="data-table" id="keywordsTable">
                <thead>
                    <tr>
                        <th>Keyword</th>
                        <th>Frequency</th>
                        <th>Last Used</th>
                        <th>Category</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Simulated keyword data
                    $keywords = [
                        [
                            'keyword' => 'budget',
                            'frequency' => 320,
                            'last_used' => '2025-07-23 09:45:00',
                            'category' => 'budget'
                        ],
                        [
                            'keyword' => 'policy',
                            'frequency' => 180,
                            'last_used' => '2025-07-20 14:20:00',
                            'category' => 'policy'
                        ],
                        [
                            'keyword' => 'support',
                            'frequency' => 95,
                            'last_used' => '2025-07-15 11:30:00',
                            'category' => 'support'
                        ],
                        [
                            'keyword' => 'tax',
                            'frequency' => 210,
                            'last_used' => '2025-07-10 16:00:00',
                            'category' => 'policy'
                        ],
                        [
                            'keyword' => 'report',
                            'frequency' => 150,
                            'last_used' => '2025-06-24 08:15:00',
                            'category' => 'budget'
                        ]
                    ];
                    
                    // Helper function to format last used time as relative time
                    function formatRelativeTime($lastUsedTime) {
                        if (!$lastUsedTime) {
                            return 'Never';
                        }
                        
                        $now = new DateTime();
                        $lastUsed = new DateTime($lastUsedTime);
                        $interval = $now->diff($lastUsed);
                        
                        $days = $interval->days;
                        $months = $interval->y * 12 + $interval->m;
                        
                        if ($days <= 6) {
                            if ($days == 0) {
                                return 'Today';
                            }
                            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
                        } elseif ($days <= 28) {
                            $weeks = floor($days / 7);
                            return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
                        } else {
                            if ($months == 0) {
                                return 'This month';
                            }
                            return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
                        }
                    }
                    
                    foreach ($keywords as $keyword) {
                        $categoryClass = 'badge-' . $keyword['category'];
                        $lastUsed = formatRelativeTime($keyword['last_used']);
                        
                        echo "<tr>
                            <td>{$keyword['keyword']}</td>
                            <td>{$keyword['frequency']}</td>
                            <td>{$lastUsed}</td>
                            <td><span class='category-badge {$categoryClass}'>" . ucfirst($keyword['category']) . "</span></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="chart-section">
            <h2>Keyword Usage Trend</h2>
            <div class="chart-placeholder">
                <p>Chart placeholder: Visualize keyword usage over time (e.g., using Chart.js)</p>
                <canvas id="keywordTrendChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
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

            // Refresh button click handler
            document.getElementById('refreshTrendsBtn').addEventListener('click', function() {
                Swal.fire({
                    title: 'Refreshed!',
                    text: 'Trends data has been refreshed (simulated).',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });

            // Apply filter button click handler
            document.getElementById('applyFilterBtn').addEventListener('click', function() {
                const timeRange = document.getElementById('timeRange').value;
                Swal.fire({
                    title: 'Filter Applied!',
                    text: `Showing trends for ${timeRange.replace('days', ' days').replace('all', 'all time')} (simulated).`,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
</body>
</html>