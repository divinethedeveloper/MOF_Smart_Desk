<?php require_once "../components/session.php";?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

 $is_logged_in = isset($_SESSION['user_id']) && isset($_SESSION['email']);
$user_email = $is_logged_in ? $_SESSION['email'] : null;
$page = 'faqs';

// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
if ($mysqli->connect_errno) {
    die('Database connection failed: ' . $mysqli->connect_error);
}

// Handle form submission for adding an FAQ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_faq'])) {
    $category_id = $_POST['category_id'] ?? 0;
    $question = $_POST['question'] ?? '';
    $answer = $_POST['answer'] ?? '';

    if ($category_id && $question && $answer) {
        $stmt = $mysqli->prepare("INSERT INTO faqs (category_id, question, answer) VALUES (?, ?, ?)");
        if (!$stmt) {
            die('Prepare failed: ' . $mysqli->error);
        }
        $stmt->bind_param("iss", $category_id, $question, $answer);
        if (!$stmt->execute()) {
            die('Execute failed: ' . $stmt->error);
        }
        $stmt->close();
        header('Location: ./'); // Redirect to refresh the page
        exit;
    }
}

// Handle form submission for editing an FAQ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_faq'])) {
    $faq_id = $_POST['faq_id'] ?? 0;
    $category_id = $_POST['category_id'] ?? 0;
    $question = $_POST['question'] ?? '';
    $answer = $_POST['answer'] ?? '';

    if ($faq_id && $category_id && $question && $answer) {
        $stmt = $mysqli->prepare("UPDATE faqs SET category_id = ?, question = ?, answer = ? WHERE id = ?");
        if (!$stmt) {
            die('Prepare failed: ' . $mysqli->error);
        }
        $stmt->bind_param("issi", $category_id, $question, $answer, $faq_id);
        if (!$stmt->execute()) {
            die('Execute failed: ' . $stmt->error);
        }
        $stmt->close();
        header('Location: ./'); // Redirect to refresh the page
        exit;
    }
}

// Handle FAQ deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_faq'])) {
    $faq_id = $_POST['faq_id'] ?? 0;
    if ($faq_id) {
        $stmt = $mysqli->prepare("DELETE FROM faqs WHERE id = ?");
        if (!$stmt) {
            die('Prepare failed: ' . $mysqli->error);
        }
        $stmt->bind_param("i", $faq_id);
        if (!$stmt->execute()) {
            die('Execute failed: ' . $stmt->error);
        }
        $stmt->close();
        header('Location: ./'); // Redirect to refresh the page
        exit;
    }
}

// Fetch FAQs and categories from database
$faqs = [];
$categories = [];
$cat_query = "SELECT id, title FROM faq_categories ORDER BY title";
$cat_result = $mysqli->query($cat_query);
if ($cat_result && $cat_result->num_rows > 0) {
    while ($row = $cat_result->fetch_assoc()) {
        $categories[] = $row;
    }
} elseif (!$cat_result) {
    die('Category query failed: ' . $mysqli->error);
}

$faq_query = "SELECT f.id, f.category_id, c.title AS category, f.question, f.answer, f.updated_at 
              FROM faqs f JOIN faq_categories c ON f.category_id = c.id 
              ORDER BY f.updated_at DESC";
$faq_result = $mysqli->query($faq_query);
if ($faq_result && $faq_result->num_rows > 0) {
    while ($row = $faq_result->fetch_assoc()) {
        $faqs[] = $row;
    }
} elseif (!$faq_result) {
    die('FAQ query failed: ' . $mysqli->error);
}
$mysqli->close();

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs Management | MOF Smart Desk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style/main.css">
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            /* Fallback CSS variables if not defined in main.css */
            --accent-blue: #007bff;
            --accent-blue-dark: #0056b3;
            --accent-red: #dc3545;
            --text-dark: #2B2B2B;
            --light-gray: #f8f9fa;
            --medium-gray: #e0e0e0;
            --dark-gray: #6c757d;
            --white: #ffffff;
            --btn-radius: 4px;
            --card-radius: 8px;
            --space-xs: 0.5rem;
            --space-sm: 1rem;
            --space-md: 1.5rem;
            --space-xl: 3rem;
            --transition: all 0.2s ease;
        }

        body.dark-mode {
            --text-dark: #f0f0f0;
            --light-gray: #1e1e1e;
            --medium-gray: #3a3a3a;
            --dark-gray: #b0b0b0;
            --white: #121212;
        }

        .main-content {
            padding: var(--space-xl);
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
            transition: var(--transition);
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
        
        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--accent-blue);
            font-size: 1rem;
            padding: var(--space-xs);
            border-radius: 4px;
            transition: var(--transition);
        }
        
        .action-btn:hover {
            background-color: var(--light-gray);
        }
        
        .action-btn.delete {
            color: var(--accent-red);
        }
        
        .truncate {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            
            .data-table {
                display: block;
                overflow-x: auto;
            }
        }

        /* SweetAlert Dark Mode Support */
        body.dark-mode .swal2-popup {
            background: var(--white);
            color: var(--text-dark);
        }
    </style>
</head>
<body>
    <?php require_once "../nav/index.php"?>
    
    <main class="main-content">
        <div class="header">
            <h1 class="page-title">FAQs Management</h1>
            <div class="action-buttons">
                <button class="btn btn-primary" id="addFaqBtn">
                    <i class="fas fa-plus"></i> Add FAQ
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="data-table" id="faqsTable">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Question</th>
                        <th>Answer</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($faqs)) {
                        echo '<tr><td colspan="5">No FAQs found</td></tr>';
                    } else {
                        foreach ($faqs as $faq) {
                            $updatedAt = formatRelativeTime($faq['updated_at']);
                            $question = strlen($faq['question']) > 50 
                                ? substr($faq['question'], 0, 47) . '...' 
                                : $faq['question'];
                            $answer = strlen($faq['answer']) > 50 
                                ? substr($faq['answer'], 0, 47) . '...' 
                                : $faq['answer'];
                            
                            echo "<tr>
                                <td>" . htmlspecialchars($faq['category']) . "</td>
                                <td class='truncate'>" . htmlspecialchars($question) . "</td>
                                <td class='truncate'>" . htmlspecialchars($answer) . "</td>
                                <td>{$updatedAt}</td>
                                <td>
                                    <button class='action-btn edit' data-id='{$faq['id']}' data-category-id='{$faq['category_id']}' data-question='" . htmlspecialchars($faq['question'], ENT_QUOTES) . "' data-answer='" . htmlspecialchars($faq['answer'], ENT_QUOTES) . "'>
                                        <i class='fas fa-edit'></i>
                                    </button>
                                    <button class='action-btn delete' data-id='{$faq['id']}'>
                                        <i class='fas fa-trash'></i>
                                    </button>
                                </td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
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

            // Add FAQ button click handler
            document.getElementById('addFaqBtn').addEventListener('click', showAddFaqModal);
            
            // FAQ edit/delete button handlers
            document.querySelectorAll('#faqsTable .action-btn.edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    const faqId = this.getAttribute('data-id');
                    const categoryId = this.getAttribute('data-category-id');
                    const question = this.getAttribute('data-question');
                    const answer = this.getAttribute('data-answer');
                    showEditFaqModal(faqId, categoryId, question, answer);
                });
            });
            
            document.querySelectorAll('#faqsTable .action-btn.delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    const faqId = this.getAttribute('data-id');
                    confirmDeleteFaq(faqId);
                });
            });
            
            function showAddFaqModal() {
                Swal.fire({
                    title: 'Add New FAQ',
                    html: `
                        <form id="faqForm" action="" method="POST">
                            <input type="hidden" name="add_faq" value="1">
                            <select id="category_id" name="category_id" class="swal2-select" required>
                                <option value="" disabled selected>Select Category</option>
                                <?php foreach ($categories as $category) { ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['title']); ?></option>
                                <?php } ?>
                            </select>
                            <input type="text" id="question" name="question" class="swal2-input" placeholder="Question" required>
                            <textarea id="answer" name="answer" class="swal2-input" placeholder="Answer" style="height: 100px;" required></textarea>
                        </form>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Add FAQ',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const form = document.getElementById('faqForm');
                        const categoryId = document.getElementById('category_id').value;
                        const question = document.getElementById('question').value;
                        const answer = document.getElementById('answer').value;
                        
                        if (!categoryId || !question || !answer) {
                            Swal.showValidationMessage('Please fill in all fields');
                            return false;
                        }
                        
                        return new Promise((resolve) => {
                            form.submit();
                        });
                    }
                });
            }
            
            function showEditFaqModal(faqId, categoryId, question, answer) {
                Swal.fire({
                    title: 'Edit FAQ',
                    html: `
                        <form id="editFaqForm" action="" method="POST">
                            <input type="hidden" name="edit_faq" value="1">
                            <input type="hidden" name="faq_id" value="${faqId}">
                            <select id="category_id" name="category_id" class="swal2-select" required>
                                <option value="" disabled>Select Category</option>
                                <?php foreach ($categories as $category) { ?>
                                    <option value="<?php echo $category['id']; ?>" ${categoryId === '<?php echo $category['id']; ?>' ? 'selected' : ''}>
                                        <?php echo htmlspecialchars($category['title']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <input type="text" id="question" name="question" class="swal2-input" value="${question.replace(/"/g, '&quot;')}" required>
                            <textarea id="answer" name="answer" class="swal2-input" style="height: 100px;" required>${answer.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</textarea>
                        </form>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Save Changes',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const form = document.getElementById('editFaqForm');
                        const categoryId = document.getElementById('category_id').value;
                        const question = document.getElementById('question').value;
                        const answer = document.getElementById('answer').value;
                        
                        if (!categoryId || !question || !answer) {
                            Swal.showValidationMessage('Please fill in all fields');
                            return false;
                        }
                        
                        return new Promise((resolve) => {
                            form.submit();
                        });
                    }
                });
            }
            
            function confirmDeleteFaq(faqId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This FAQ will be permanently deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '';
                        form.innerHTML = `
                            <input type="hidden" name="delete_faq" value="1">
                            <input type="hidden" name="faq_id" value="${faqId}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            // Dark mode toggle
            const darkModeToggle = document.getElementById('darkModeToggle');
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', () => {
                    document.body.classList.toggle('dark-mode');
                    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
                });
                if (localStorage.getItem('darkMode') === 'true') {
                    document.body.classList.add('dark-mode');
                }
            }
        });
    </script>
</body>
</html>