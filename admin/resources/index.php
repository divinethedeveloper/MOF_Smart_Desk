<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']) && isset($_SESSION['email']);
$user_email = $is_logged_in ? $_SESSION['email'] : null;
$page = 'resources_admin';

// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');

// Check for database connection error
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

// Handle form submission for adding a resource
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_resource'])) {
    $title = $_POST['title'] ?? '';
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';
    $file_format = $_POST['file_format'] ?? '';
    $file_path = '';
    $file_size = 0;

    // File upload handling
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $allowed_extensions = ['pdf', 'docx'];
        $file_ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_extensions) && strtolower($file_format) === $file_ext) {
            // Sanitize file name
            $file_name = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $_FILES['file']['name']);
            $upload_dir = './files/';
            $file_path_full = $upload_dir . $file_name;

            // Ensure upload directory exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Move uploaded file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path_full)) {
                $file_path = 'files/' . $file_name; // Store relative path in DB
                $file_size = round(filesize($file_path_full) / (1024 * 1024), 1); // Size in MB
            } else {
                $response = ['status' => 'error', 'message' => 'Failed to upload file.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid file type or format mismatch. Only PDF and DOCX are allowed.'];
        }
    } else if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $response = ['status' => 'error', 'message' => 'File upload error: ' . $_FILES['file']['error']];
    }

    // Only proceed with DB insert if file upload was successful or not attempted
    if ($file_path || ($_FILES['file']['error'] === UPLOAD_ERR_NO_FILE)) { // Allow no file if edit doesn't require new upload
        // Basic validation
        if ($title && in_array($type, ['Forms', 'Reports', 'Manuals']) && $description &&
            in_array($file_format, ['PDF', 'DOCX']) && $file_path) { // file_path must exist for new resource
            $stmt = $mysqli->prepare("INSERT INTO resources (title, type, description, file_format, file_size, file_path) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssssds", $title, $type, $description, $file_format, $file_size, $file_path);
                if ($stmt->execute()) {
                    $response = ['status' => 'success', 'message' => 'Resource added successfully!', 'action' => 'add'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Database insert failed: ' . $stmt->error];
                }
                $stmt->close();
            } else {
                $response = ['status' => 'error', 'message' => 'Failed to prepare statement: ' . $mysqli->error];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Please fill in all required fields and ensure a file is uploaded.'];
        }
    }
    // Store response in session for SweetAlert
    $_SESSION['response'] = $response;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Handle fetching a single resource for editing
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_resource' && isset($_GET['id'])) {
    $resourceId = (int)$_GET['id'];
    $stmt = $mysqli->prepare("SELECT id, title, type, description, file_format, file_size, file_path FROM resources WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $resourceId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            echo json_encode(['status' => 'success', 'data' => $row]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Resource not found.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement.']);
    }
    exit(); // Important to exit after AJAX response
}

// Handle updating a resource
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_resource'])) {
    $id = $_POST['id'] ?? '';
    $title = $_POST['title'] ?? '';
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';
    $file_format = $_POST['file_format'] ?? '';
    $file_path = $_POST['existing_file_path'] ?? ''; // Keep existing path by default
    $file_size = $_POST['existing_file_size'] ?? 0;

    if (!empty($id)) {
        // Handle file upload for update
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $allowed_extensions = ['pdf', 'docx'];
            $file_ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed_extensions) && strtolower($file_format) === $file_ext) {
                // Sanitize new file name
                $file_name = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $_FILES['file']['name']);
                $upload_dir = './files/';
                $new_file_path_full = $upload_dir . $file_name;

                // Ensure upload directory exists
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Delete old file if it exists and a new one is uploaded
                if (!empty($_POST['existing_file_path']) && file_exists($_POST['existing_file_path'])) {
                     unlink($_POST['existing_file_path']);
                }

                if (move_uploaded_file($_FILES['file']['tmp_name'], $new_file_path_full)) {
                    $file_path = 'files/' . $file_name; // Store relative path in DB
                    $file_size = round(filesize($new_file_path_full) / (1024 * 1024), 1); // Size in MB
                } else {
                    $response = ['status' => 'error', 'message' => 'Failed to upload new file.'];
                    $_SESSION['response'] = $response;
                    header('Location: ' . $_SERVER['PHP_SELF']);
                    exit();
                }
            } else {
                $response = ['status' => 'error', 'message' => 'Invalid new file type or format mismatch. Only PDF and DOCX are allowed.'];
                $_SESSION['response'] = $response;
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            }
        } else if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
             $response = ['status' => 'error', 'message' => 'File upload error: ' . $_FILES['file']['error']];
             $_SESSION['response'] = $response;
             header('Location: ' . $_SERVER['PHP_SELF']);
             exit();
        }

        // Basic validation for update
        if ($title && in_array($type, ['Forms', 'Reports', 'Manuals']) && $description &&
            in_array($file_format, ['PDF', 'DOCX'])) {
            $stmt = $mysqli->prepare("UPDATE resources SET title = ?, type = ?, description = ?, file_format = ?, file_size = ?, file_path = ?, updated_at = NOW() WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("ssssdsi", $title, $type, $description, $file_format, $file_size, $file_path, $id);
                if ($stmt->execute()) {
                    $response = ['status' => 'success', 'message' => 'Resource updated successfully!', 'action' => 'edit'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Database update failed: ' . $stmt->error];
                }
                $stmt->close();
            } else {
                $response = ['status' => 'error', 'message' => 'Failed to prepare statement for update: ' . $mysqli->error];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Please fill in all required fields for update.'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Resource ID is missing for update.'];
    }
    // Store response in session for SweetAlert
    $_SESSION['response'] = $response;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Handle deleting a resource
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_resource'])) {
    $id = $_POST['id'] ?? '';

    if (!empty($id)) {
        // First, get the file path to delete the actual file
        $stmt = $mysqli->prepare("SELECT file_path FROM resources WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $fileToDelete = $row['file_path'];

                // Delete from database
                $deleteStmt = $mysqli->prepare("DELETE FROM resources WHERE id = ?");
                if ($deleteStmt) {
                    $deleteStmt->bind_param("i", $id);
                    if ($deleteStmt->execute()) {
                        // If DB deletion is successful, try to delete the file
                        if (!empty($fileToDelete) && file_exists($fileToDelete)) {
                            unlink($fileToDelete); // Delete the file
                        }
                        $response = ['status' => 'success', 'message' => 'Resource deleted successfully!', 'action' => 'delete'];
                    } else {
                        $response = ['status' => 'error', 'message' => 'Database deletion failed: ' . $deleteStmt->error];
                    }
                    $deleteStmt->close();
                } else {
                    $response = ['status' => 'error', 'message' => 'Failed to prepare delete statement: ' . $mysqli->error];
                }
            } else {
                $response = ['status' => 'error', 'message' => 'Resource not found for deletion.'];
            }
            $stmt->close();
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to prepare select statement for file path: ' . $mysqli->error];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Resource ID is missing for deletion.'];
    }
    // Store response in session for SweetAlert
    $_SESSION['response'] = $response;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}


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
            $resources[] = $row;
        }
        $result->free();
    }
}
$mysqli->close(); // Close the connection after all operations
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources Management | MOF Smart Desk</title>
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

        .type-badge {
            display: inline-block;
            padding: var(--space-xs) var(--space-sm);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-forms {
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--accent-purple);
        }

        .badge-reports {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
        }

        .badge-manuals {
            background-color: rgba(255, 193, 7, 0.1);
            color: #FFC107;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--accent-blue);
            font-size: 1rem;
            padding: var(--space-xs);
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            background-color: var(--light-gray);
        }

        .action-btn.delete {
            color: var(--accent-red);
        }

        /* SweetAlert specific styles */
        .swal2-container {
            font-family: 'Inter', sans-serif;
        }
        .swal2-title {
            font-weight: 600;
            color: var(--text-dark);
        }
        .swal2-input, .swal2-textarea, .swal2-select, .swal2-file {
            width: calc(100% - 20px);
            padding: var(--space-sm);
            margin-bottom: var(--space-md);
            border: 1px solid var(--medium-gray);
            border-radius: var(--btn-radius);
            font-size: 1rem;
            box-sizing: border-box;
        }
        .swal2-input:focus, .swal2-textarea:focus, .swal2-select:focus, .swal2-file:focus {
            border-color: var(--accent-blue);
            outline: none;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }
        .swal2-actions button {
            font-weight: 500;
        }
        .swal2-confirm {
            background-color: var(--accent-blue) !important;
        }
        .swal2-confirm:hover {
            background-color: var(--accent-blue-dark) !important;
        }
        .swal2-cancel {
            background-color: var(--light-gray) !important;
            color: var(--text-dark) !important;
        }
        .swal2-cancel:hover {
            background-color: var(--medium-gray) !important;
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
    </style>
</head>
<body>
    <?php require_once "../nav/index.php"?>

    <main class="main-content">
        <div class="header">
            <h1 class="page-title">Resources Management</h1>
            <div class="action-buttons">
                <button class="btn btn-primary" id="addResourceBtn">
                    <i class="fas fa-plus"></i> Add Resource
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table" id="resourcesTable">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>File Format</th>
                        <th>File Path</th>
                        <th>File Size</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($resources)) {
                        echo '<tr><td colspan="8">No resources found</td></tr>';
                    } else {
                        foreach ($resources as $resource) {
                            $typeClass = 'badge-' . strtolower($resource['type']);
                            $updatedAt = formatRelativeTime($resource['updated_at']);
                            // Ensure description is not empty before truncating
                            $description = !empty($resource['description']) ? (strlen($resource['description']) > 50
                                ? substr($resource['description'], 0, 47) . '...'
                                : $resource['description']) : '';
                            $filePath = $resource['file_path'] ? htmlspecialchars($resource['file_path']) : 'N/A';

                            echo "<tr>
                                <td>" . htmlspecialchars($resource['title']) . "</td>
                                <td><span class='type-badge {$typeClass}'>" . htmlspecialchars($resource['type']) . "</span></td>
                                <td>" . htmlspecialchars($description) . "</td>
                                <td>" . htmlspecialchars($resource['file_format']) . "</td>
                                <td>" . $filePath . "</td>
                                <td>" . number_format($resource['file_size'], 1) . " MB</td>
                                <td>{$updatedAt}</td>
                                <td>
                                    <button class='action-btn edit' data-id='{$resource['id']}'>
                                        <i class='fas fa-edit'></i>
                                    </button>
                                    <button class='action-btn delete' data-id='{$resource['id']}'>
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

            // Function to display SweetAlert messages
            function showAlert(response) {
                if (response && response.status && response.message) {
                    Swal.fire({
                        icon: response.status,
                        title: response.status === 'success' ? 'Success!' : 'Error!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }

            // Check for session messages from PHP after redirect
            <?php if (isset($_SESSION['response'])): ?>
                showAlert(<?php echo json_encode($_SESSION['response']); ?>);
                <?php unset($_SESSION['response']); // Clear the session variable ?>
            <?php endif; ?>

            // Add resource button click handler
            document.getElementById('addResourceBtn').addEventListener('click', showAddResourceModal);

            // Edit button click handlers (delegated to document for dynamic elements)
            document.addEventListener('click', function(event) {
                if (event.target.closest('.action-btn.edit')) {
                    const resourceId = event.target.closest('.action-btn.edit').getAttribute('data-id');
                    showEditResourceModal(resourceId);
                }
            });

            // Delete button click handlers (delegated to document for dynamic elements)
            document.addEventListener('click', function(event) {
                if (event.target.closest('.action-btn.delete')) {
                    const resourceId = event.target.closest('.action-btn.delete').getAttribute('data-id');
                    confirmDeleteResource(resourceId);
                }
            });

            function showAddResourceModal() {
                Swal.fire({
                    title: 'Add New Resource',
                    html: `
                        <form id="resourceForm" enctype="multipart/form-data">
                            <input type="text" id="title" name="title" class="swal2-input" placeholder="Title" required>
                            <select id="type" name="type" class="swal2-select" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="Forms">Forms</option>
                                <option value="Reports">Reports</option>
                                <option value="Manuals">Manuals</option>
                            </select>
                            <textarea id="description" name="description" class="swal2-input" placeholder="Description" style="height: 100px;" required></textarea>
                            <select id="file_format" name="file_format" class="swal2-select" required>
                                <option value="" disabled selected>Select File Format</option>
                                <option value="PDF">PDF</option>
                                <option value="DOCX">DOCX</option>
                            </select>
                            <input type="file" id="file" name="file" class="swal2-file" accept=".pdf,.docx" required>
                        </form>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Add Resource',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const title = document.getElementById('title').value;
                        const type = document.getElementById('type').value;
                        const description = document.getElementById('description').value;
                        const file_format = document.getElementById('file_format').value;
                        const fileInput = document.getElementById('file');
                        const file = fileInput.files[0];

                        if (!title || !type || !description || !file_format || !file) {
                            Swal.showValidationMessage('Please fill in all fields and upload a file.');
                            return false;
                        }

                        const allowedExtensions = ['pdf', 'docx'];
                        const fileExt = file.name.split('.').pop().toLowerCase();
                        if (!allowedExtensions.includes(fileExt) || file_format.toLowerCase() !== fileExt) {
                            Swal.showValidationMessage('File format must match the selected type (PDF or DOCX).');
                            return false;
                        }

                        const formData = new FormData();
                        formData.append('add_resource', '1');
                        formData.append('title', title);
                        formData.append('type', type);
                        formData.append('description', description);
                        formData.append('file_format', file_format);
                        formData.append('file', file);

                        // Use Fetch API to submit form data
                        return fetch('', { // Submit to the same PHP script
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText);
                            }
                            return response.text(); // Get raw text to check for redirect
                        })
                        .then(text => {
                            // If the server redirects, it means PHP handled the response via session.
                            // We just need to reload the page.
                            window.location.reload();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`);
                        });
                    }
                });
            }

            async function showEditResourceModal(resourceId) {
                let resourceData;
                try {
                    const response = await fetch(`?action=get_resource&id=${resourceId}`);
                    const result = await response.json();

                    if (result.status === 'success') {
                        resourceData = result.data;
                    } else {
                        showAlert(result);
                        return;
                    }
                } catch (error) {
                    showAlert({status: 'error', message: 'Failed to fetch resource data.'});
                    return;
                }

                Swal.fire({
                    title: 'Edit Resource',
                    html: `
                        <form id="editResourceForm" enctype="multipart/form-data">
                            <input type="hidden" name="edit_resource" value="1">
                            <input type="hidden" name="id" value="${resourceData.id}">
                            <input type="hidden" name="existing_file_path" value="${resourceData.file_path}">
                            <input type="hidden" name="existing_file_size" value="${resourceData.file_size}">

                            <input type="text" id="edit_title" name="title" class="swal2-input" placeholder="Title" value="${resourceData.title}" required>
                            <select id="edit_type" name="type" class="swal2-select" required>
                                <option value="Forms" ${resourceData.type === 'Forms' ? 'selected' : ''}>Forms</option>
                                <option value="Reports" ${resourceData.type === 'Reports' ? 'selected' : ''}>Reports</option>
                                <option value="Manuals" ${resourceData.type === 'Manuals' ? 'selected' : ''}>Manuals</option>
                            </select>
                            <textarea id="edit_description" name="description" class="swal2-input" placeholder="Description" style="height: 100px;" required>${resourceData.description}</textarea>
                            <select id="edit_file_format" name="file_format" class="swal2-select" required>
                                <option value="PDF" ${resourceData.file_format === 'PDF' ? 'selected' : ''}>PDF</option>
                                <option value="DOCX" ${resourceData.file_format === 'DOCX' ? 'selected' : ''}>DOCX</option>
                            </select>
                            <p style="text-align: left; margin-top: 5px; margin-bottom: 5px; font-size: 0.9em;">Current File: <strong>${resourceData.file_path.split('/').pop()}</strong> (${resourceData.file_size} MB)</p>
                            <input type="file" id="edit_file" name="file" class="swal2-file" accept=".pdf,.docx">
                            <p style="text-align: left; font-size: 0.8em; color: var(--text-light);">Leave file field empty to keep current file.</p>
                        </form>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Save Changes',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const form = document.getElementById('editResourceForm');
                        const title = document.getElementById('edit_title').value;
                        const type = document.getElementById('edit_type').value;
                        const description = document.getElementById('edit_description').value;
                        const file_format = document.getElementById('edit_file_format').value;
                        const fileInput = document.getElementById('edit_file');
                        const file = fileInput.files[0];

                        if (!title || !type || !description || !file_format) {
                            Swal.showValidationMessage('Please fill in all required fields.');
                            return false;
                        }

                        if (file) {
                            const allowedExtensions = ['pdf', 'docx'];
                            const fileExt = file.name.split('.').pop().toLowerCase();
                            if (!allowedExtensions.includes(fileExt) || file_format.toLowerCase() !== fileExt) {
                                Swal.showValidationMessage('New file format must match the selected type (PDF or DOCX).');
                                return false;
                            }
                        }

                        const formData = new FormData();
                        formData.append('edit_resource', '1');
                        formData.append('id', resourceData.id);
                        formData.append('title', title);
                        formData.append('type', type);
                        formData.append('description', description);
                        formData.append('file_format', file_format);
                        formData.append('existing_file_path', resourceData.file_path); // Pass existing path
                        formData.append('existing_file_size', resourceData.file_size); // Pass existing size

                        if (file) {
                            formData.append('file', file);
                        }

                        return fetch('', { // Submit to the same PHP script
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText);
                            }
                            return response.text();
                        })
                        .then(text => {
                            window.location.reload();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`);
                        });
                    }
                });
            }

            function confirmDeleteResource(resourceId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('delete_resource', '1');
                        formData.append('id', resourceId);

                        fetch('', { // Submit to the same PHP script
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText);
                            }
                            return response.text();
                        })
                        .then(text => {
                            window.location.reload();
                        })
                        .catch(error => {
                            showAlert({status: 'error', message: `Deletion failed: ${error}`});
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>