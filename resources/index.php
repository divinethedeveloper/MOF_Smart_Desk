<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']) && isset($_SESSION['email']);
$user_email = $is_logged_in ? $_SESSION['email'] : null;
$page = 'resources';

// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');

// Helper function to format updated_at as relative time
function formatRelativeTime($updatedTime) {
    if (!$updatedTime) {
        return 'Never';
    }
    
    $now = new DateTime();
    $updated = new DateTime($updatedTime);
    $interval = $now->diff($updated);
    
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

// Fetch resources from database
$resources = [];
if (!$mysqli->connect_errno) {
    $query = "SELECT id, title, type, description, file_format, file_size, file_path, updated_at 
              FROM resources ORDER BY updated_at DESC";
    $result = $mysqli->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resources[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'category' => strtolower($row['type']),
                'description' => $row['description'],
                'type' => $row['file_format'],
                'size' => number_format($row['file_size'] / (1024 * 1024), 1) . ' MB', // Convert bytes to MB
                'file_path' => $row['file_path'] ? htmlspecialchars($row['file_path']) : '',
                'updated' => formatRelativeTime($row['updated_at']),
                'icon' => $row['type'] === 'Forms' ? 'file-alt' : ($row['type'] === 'Reports' ? 'chart-bar' : 'book')
            ];
        }
    }
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministry of Finance - Resources</title>
    <link rel="stylesheet" href="../styles/nav.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            --accent-secondary: #006048; /* Darker shade for hover */
            --border-color: #E0E0E0;
            --card-bg: #FFFFFF;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 10px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 20px rgba(0,0,0,0.12);
            
            /* Spacing */
            --space-xs: 0.5rem;
            --space-sm: 1rem;
            --space-md: 1.5rem;
            --space-lg: 2rem;
            --space-xl: 3rem;
            
            /* Border Radius */
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            
            /* Transition */
            --transition: all 0.2s ease-in-out;
        }

        /* Dark Mode */
        body.dark-mode {
            --primary-bg: #1A1A2E; /* Dark blue-grey */
            --secondary-bg: #16213E; /* Even darker blue-grey */
            --text-primary: #E0E0E0;
            --text-secondary: #A0A0A0;
            --accent-primary: #00C896;
            --accent-secondary: #00A07A; /* Darker shade for hover */
            --border-color: #2F3A5A;
            --card-bg: #1F2847; /* Slightly lighter than secondary-bg for cards */
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.25);
            --shadow-md: 0 4px 10px rgba(0,0,0,0.35);
            --shadow-lg: 0 10px 20px rgba(0,0,0,0.45);
        }

        /* ========== Base Styles ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--primary-bg);
            color: var(--text-primary);
            line-height: 1.6;
            transition: var(--transition);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4 {
            font-weight: 600;
            color: var(--text-primary);
        }

        p {
            color: var(--text-secondary);
        }

        a {
            color: var(--accent-primary);
            text-decoration: none;
            transition: var(--transition);
        }

        button {
            cursor: pointer;
            transition: var(--transition);
            border: none;
            background: none;
        }

        /* ========== Layout ========== */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 var(--space-lg);
        }

        /* ========== Header ========== */
        .header {
            background-color: var(--secondary-bg);
            height: 80px;
            display: flex;
            align-items: center;
            padding: 0 var(--space-lg);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
            border-bottom: 1px solid var(--border-color);
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--accent-primary);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }

        .header-title i {
            font-size: 1.8rem;
        }

        /* ========== Main Content ========== */
        .main-content {
            flex: 1; /* Allows main content to expand and push footer down */
            padding: var(--space-xl) 0;
        }

        .page-header {
            margin-bottom: var(--space-xl);
            text-align: center;
        }

        .page-title {
            font-size: 2.5rem;
            margin-bottom: var(--space-sm);
            letter-spacing: -0.02em;
        }

        .page-subtitle {
            font-size: 1.15rem;
            max-width: 800px;
            margin: 0 auto;
            color: var(--text-secondary);
        }

        /* ========== Control Bar ========== */
        .control-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-lg);
            gap: var(--space-md);
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            min-width: 300px;
            max-width: 500px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: var(--space-sm) var(--space-md) var(--space-sm) 3rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            background-color: var(--card-bg);
            color: var(--text-primary);
            font-size: 1rem;
            transition: var(--transition);
        }

        .search-input::placeholder {
            color: var(--text-secondary);
            opacity: 0.7;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(0, 127, 95, 0.2); /* More subtle focus ring */
        }

        .search-icon {
            position: absolute;
            left: var(--space-md);
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .filter-group {
            display: flex;
            gap: var(--space-sm);
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: var(--space-sm) var(--space-md);
            background-color: var(--card-bg);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: var(--space-xs);
            transition: var(--transition);
            box-shadow: var(--shadow-sm); /* Added subtle shadow to buttons */
        }

        .filter-btn:hover {
            border-color: var(--accent-primary);
            color: var(--accent-primary);
        }
        .filter-btn.active {
            background-color: var(--accent-primary);
            color: white;
            border-color: var(--accent-primary);
            box-shadow: var(--shadow-md);
        }

        /* ========== Resources Grid ========== */
        .resources-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: var(--space-lg);
        }

        .resource-card {
            background-color: var(--card-bg);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-md); /* Slightly more prominent shadow */
            border: 1px solid var(--border-color);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .resource-card:hover {
            transform: translateY(-7px); /* More pronounced lift */
            box-shadow: var(--shadow-lg);
            border-color: var(--accent-primary);
        }

        .card-header {
            padding: var(--space-md);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            background-color: var(--secondary-bg); /* Slightly different background for header */
        }

        .card-icon {
            width: 52px; /* Slightly larger icon */
            height: 52px;
            background-color: rgba(0, 127, 95, 0.1); /* Use primary accent for background */
            border-radius: 50%; /* Make icon circular */
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-primary);
            font-size: 1.8rem; /* Larger icon font size */
            flex-shrink: 0;
        }

        .card-title {
            font-size: 1.3rem; /* Slightly larger title */
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .card-category {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .card-body {
            padding: var(--space-md);
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-description {
            margin-bottom: var(--space-md);
            color: var(--text-secondary);
            font-size: 0.95rem;
            flex-grow: 1; /* Allow description to take available space */
        }

        .card-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem; /* Smaller meta info */
            color: var(--text-secondary);
            margin-top: var(--space-sm);
            padding-top: var(--space-sm);
            border-top: 1px dashed var(--border-color); /* Dashed separator */
        }

        .card-meta span {
            display: flex;
            align-items: center;
            gap: var(--space-xs);
        }

        .card-meta i {
            font-size: 0.9rem;
            color: var(--accent-primary);
        }

        .card-footer {
            padding: var(--space-sm) var(--space-md);
            background-color: var(--secondary-bg);
            border-top: 1px solid var(--border-color);
            text-align: center;
        }

        .download-btn {
            display: inline-flex; /* Use inline-flex for button, centers content */
            align-items: center;
            justify-content: center;
            gap: var(--space-xs);
            width: 100%;
            padding: var(--space-sm);
            background-color: var(--accent-primary);
            color: white;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .download-btn:hover {
            background-color: var(--accent-secondary);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        /* ========== Empty State ========== */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: var(--space-xl);
            color: var(--text-secondary);
            background-color: var(--secondary-bg);
            border-radius: var(--radius-md);
            border: 1px dashed var(--border-color);
        }

        .empty-state i {
            font-size: 4rem; /* Larger icon for empty state */
            margin-bottom: var(--space-sm);
            color: var(--text-secondary);
        }

        .empty-state h3 {
            font-size: 1.8rem;
            margin-bottom: var(--space-xs);
        }

        /* SweetAlert2 Customizations */
        .swal2-popup {
            background-color: var(--card-bg) !important;
            color: var(--text-primary) !important;
            border-radius: var(--radius-md) !important;
            box-shadow: var(--shadow-lg) !important;
        }

        .swal2-title {
            color: var(--text-primary) !important;
        }

        .swal2-html-container {
            color: var(--text-secondary) !important;
        }

        .swal2-styled.swal2-confirm {
            background-color: var(--accent-primary) !important;
            color: white !important;
            border-radius: var(--radius-sm) !important;
            transition: var(--transition) !important;
        }
        .swal2-styled.swal2-confirm:focus {
            box-shadow: 0 0 0 3px rgba(0, 127, 95, 0.4) !important;
        }

        .swal2-progress-steps .swal2-progress-step {
            background: var(--accent-primary) !important;
        }

        #progressBar {
            background: linear-gradient(90deg, var(--accent-primary) 0%, #00d4ff 100%) !important;
            box-shadow: 0 0 5px rgba(0, 127, 95, 0.5);
            transition: width 2.5s ease-out, background 1s ease-in-out !important;
        }


        /* ========== Responsive ========== */
        @media (max-width: 1024px) {
            .container, .header {
                padding: 0 var(--space-md);
            }
            .resources-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
            .page-title {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            .resources-grid {
                grid-template-columns: 1fr;
            }
            
            .control-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                max-width: 100%;
            }
            
            .filter-group {
                justify-content: center;
            }
            .page-title {
                font-size: 2rem;
            }
            .page-subtitle {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .header {
                height: 70px;
            }
            
            .page-header {
                margin-bottom: var(--space-lg);
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            .header-title {
                font-size: 1.3rem;
            }
            .search-input {
                padding: var(--space-sm) var(--space-md) var(--space-sm) 2.5rem;
            }
            .search-icon {
                left: var(--space-xs);
            }
            .filter-btn {
                font-size: 0.9rem;
                padding: 0.6rem 1rem;
            }
            .card-title {
                font-size: 1.15rem;
            }
            .card-icon {
                width: 44px;
                height: 44px;
                font-size: 1.4rem;
            }
            .card-description {
                font-size: 0.9rem;
            }
            .download-btn {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <?php require_once "../components/nav.php"?>

    <header class="header">
        <div class="container">
            <h1 class="header-title">
                <i class="fas fa-landmark"></i>
                Ministry Resources
            </h1>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Financial Resources Center</h1>
                <p class="page-subtitle">Access official forms, reports, and documents from the Ministry of Finance to stay informed and compliant.</p>
            </div>

            <div class="control-bar">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="searchInput" placeholder="Search resources by title or description...">
                </div>
                <div class="filter-group">
                    <button class="filter-btn active" data-filter="all">
                        <span>All</span>
                    </button>
                    <button class="filter-btn" data-filter="forms">
                        <i class="fas fa-file-alt"></i>
                        <span>Forms</span>
                    </button>
                    <button class="filter-btn" data-filter="reports">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </button>
                    <button class="filter-btn" data-filter="manuals">
                        <i class="fas fa-book"></i>
                        <span>Manuals</span>
                    </button>
                </div>
            </div>

            <div class="resources-grid" id="resourcesGrid">
                <?php
                // PHP rendering is now just a fallback/initial load, JS handles dynamic rendering
                // Keeping this for initial page load and SEO benefits if JS is slow to load
                if (empty($resources)) {
                    echo '
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <h3>No resources found</h3>
                        <p>Try adjusting your search or filter criteria</p>
                    </div>';
                } else {
                    foreach ($resources as $resource) {
                        echo '
                        <div class="resource-card" data-category="' . $resource['category'] . '">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-' . $resource['icon'] . '"></i>
                                </div>
                                <div>
                                    <h3 class="card-title">' . htmlspecialchars($resource['title']) . '</h3>
                                    <span class="card-category">' . ucfirst($resource['category']) . '</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-description">' . htmlspecialchars($resource['description']) . '</p>
                                <div class="card-meta">
                                    <span><i class="fas fa-file"></i> ' . $resource['type'] . '</span>
                                    <span><i class="fas fa-ruler"></i> ' . $resource['size'] . '</span>
                                    <span><i class="fas fa-sync-alt"></i> Updated ' . $resource['updated'] . '</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="../admin/resources/' . $resource['file_path'] . '" download="' . htmlspecialchars($resource['title']) . '.' . strtolower($resource['type']) . '" class="download-btn" data-file-path="' . ($resource['file_path'] ? '../admin/resources/' . $resource['file_path'] : '') . '" data-file-name="' . htmlspecialchars($resource['title']) . '.' . strtolower($resource['type']) . '">
                                    <i class="fas fa-download"></i>
                                    Download
                                </a>
                            </div>
                        </div>';
                    }
                }
                ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Resources data from PHP
            const resources = <?php echo json_encode($resources); ?>;

            // DOM elements
            const resourcesGrid = document.getElementById('resourcesGrid');
            const searchInput = document.getElementById('searchInput');
            const filterBtns = document.querySelectorAll('.filter-btn');

            // Current filter state
            let currentFilter = 'all';
            let currentSearch = '';

            // Render resources
            function renderResources() {
                resourcesGrid.innerHTML = '';

                const filteredResources = resources.filter(resource => {
                    const matchesFilter = currentFilter === 'all' || resource.category === currentFilter;
                    const matchesSearch = resource.title.toLowerCase().includes(currentSearch.toLowerCase()) || 
                                         resource.description.toLowerCase().includes(currentSearch.toLowerCase());
                    return matchesFilter && matchesSearch;
                });

                if (filteredResources.length === 0) {
                    resourcesGrid.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <h3>No resources found</h3>
                            <p>Try adjusting your search or filter criteria</p>
                        </div>`;
                    return;
                }

                filteredResources.forEach(resource => {
                    const card = document.createElement('div');
                    card.className = 'resource-card';
                    card.setAttribute('data-category', resource.category); // Add data-category for potential future use
                    card.innerHTML = `
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-${resource.icon}"></i>
                            </div>
                            <div>
                                <h3 class="card-title">${resource.title}</h3>
                                <span class="card-category">${resource.category.charAt(0).toUpperCase() + resource.category.slice(1)}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-description">${resource.description}</p>
                            <div class="card-meta">
                                <span><i class="fas fa-file"></i> ${resource.type}</span>
                                <span><i class="fas fa-ruler"></i> ${resource.size}</span>
                                <span><i class="fas fa-sync-alt"></i> Updated ${resource.updated}</span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="${resource.file_path ? '../admin/resources/' + resource.file_path : '#'}" download="${resource.title}.${resource.type.toLowerCase()}" class="download-btn" data-file-path="${resource.file_path ? '../admin/resources/' + resource.file_path : ''}" data-file-name="${resource.title}.${resource.type.toLowerCase()}">
                                <i class="fas fa-download"></i>
                                Download
                            </a>
                        </div>
                    `;
                    resourcesGrid.appendChild(card);
                });

                // Attach download button listeners to newly rendered elements
                attachDownloadListeners();
            }

            // Function to attach download listeners
            function attachDownloadListeners() {
                document.querySelectorAll('.download-btn').forEach(btn => {
                    // Remove existing listeners to prevent duplicates
                    btn.removeEventListener('click', handleDownloadClick); 
                    btn.addEventListener('click', handleDownloadClick);
                });
            }

            function handleDownloadClick(event) {
                const filePath = this.getAttribute('data-file-path');
                const fileName = this.getAttribute('data-file-name');

                if (filePath) {
                    event.preventDefault(); // Prevent default link behavior for custom animation

                    let timerInterval;
                    Swal.fire({
                        title: 'Initiating Download!',
                        html: 'Preparing your file: <strong>' + fileName + '</strong>.<br> Download will start in <b id="timer">5</b> seconds.',
                        timer: 5000,
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                            const timer = Swal.getPopup().querySelector('#timer');
                            timerInterval = setInterval(() => {
                                timer.textContent = `${Math.ceil(Swal.getTimerLeft() / 1000)}`;
                            }, 100);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                            // Trigger actual download
                            const tempLink = document.createElement('a');
                            tempLink.href = filePath;
                            tempLink.download = fileName; // Suggests a filename for the download
                            document.body.appendChild(tempLink);
                            tempLink.click();
                            document.body.removeChild(tempLink);
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Download Started!',
                                text: 'Your download should be starting now.',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            console.log('I was closed by the timer');
                        }
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No file available for download.',
                        confirmButtonText: 'Got it'
                    });
                }
            }

            // Filter functionality
            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    currentFilter = btn.dataset.filter;
                    renderResources();
                });
            });

            // Search functionality
            searchInput.addEventListener('input', () => {
                currentSearch = searchInput.value; // No need to toLowerCase here, it's done in filter
                renderResources();
            });

            // Initial render
            renderResources();

            // Dark mode toggle logic
            const darkModeButton = document.getElementById('darkModeToggle');
            if (darkModeButton) {
                // Set initial text
                if (document.body.classList.contains('dark-mode') || localStorage.getItem('darkMode') === 'enabled') {
                    darkModeButton.textContent = 'Light Mode';
                } else {
                    darkModeButton.textContent = 'Dark Mode';
                }

                darkModeButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.body.classList.toggle('dark-mode');
                    if (document.body.classList.contains('dark-mode')) {
                        localStorage.setItem('darkMode', 'enabled');
                        darkModeButton.textContent = 'Light Mode';
                    } else {
                        localStorage.setItem('darkMode', 'disabled');
                        darkModeButton.textContent = 'Dark Mode';
                    }
                });

                // On page load, apply dark mode if enabled
                if (localStorage.getItem('darkMode') === 'enabled') {
                    document.body.classList.add('dark-mode');
                    darkModeButton.textContent = 'Light Mode';
                }
            }
        });
    </script>
</body>
</html>