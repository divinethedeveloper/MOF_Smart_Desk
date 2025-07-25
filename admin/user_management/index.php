<?php require_once "../components/session.php";?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | MOF Smart Desk</title>
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
        
        .role-badge {
            display: inline-block;
            padding: var(--space-xs) var(--space-sm);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-admin {
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--accent-purple);
        }
        
        .badge-staff {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
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
            <h1 class="page-title">User Management</h1>
            <div class="action-buttons">
                <button class="btn btn-primary" id="addUserBtn">
                    <i class="fas fa-plus"></i> Add User
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="data-table" id="usersTable">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>MOF Staff ID</th>
                        <th>Role</th>
                        <th>Last Login</th>
                        <th>Account Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
                    
                    // Helper function to format last login as relative time
                    function formatRelativeTime($lastLoginTime) {
                        if (!$lastLoginTime) {
                            return 'Never';
                        }
                        
                        $now = new DateTime();
                        $lastLogin = new DateTime($lastLoginTime);
                        $interval = $now->diff($lastLogin);
                        
                        $days = $interval->days;
                        $hours = $interval->h;
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
                    
                    if ($mysqli->connect_errno) {
                        echo '<tr><td colspan="7">Database connection failed</td></tr>';
                    } else {
                        $query = "SELECT id, fullname, email, mof_staff_id, role, last_login, created_at 
                                 FROM users ORDER BY created_at DESC";
                        $result = $mysqli->query($query);
                        
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $roleClass = $row['role'] === 'admin' ? 'badge-admin' : 'badge-staff';
                                $lastLogin = formatRelativeTime($row['last_login']);
                                $createdAt = date('M j, Y', strtotime($row['created_at']));
                                
                                echo "<tr>
                                    <td>{$row['fullname']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['mof_staff_id']}</td>
                                    <td><span class='role-badge {$roleClass}'>{$row['role']}</span></td>
                                    <td>{$lastLogin}</td>
                                    <td>{$createdAt}</td>
                                    <td>
                                        <button class='action-btn edit' data-id='{$row['id']}'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        <button class='action-btn delete' data-id='{$row['id']}'>
                                            <i class='fas fa-trash'></i>
                                        </button>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo '<tr><td colspan="7">No users found</td></tr>';
                        }
                        
                        $mysqli->close();
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

            // Add user button click handler
            document.getElementById('addUserBtn').addEventListener('click', showAddUserModal);
            
            // Edit button click handlers
            document.querySelectorAll('.action-btn.edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    showEditUserModal(userId);
                });
            });
            
            // Delete button click handlers
            document.querySelectorAll('.action-btn.delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    confirmDeleteUser(userId);
                });
            });
            
            function showAddUserModal() {
                Swal.fire({
                    title: 'Add New User',
                    html: `
                        <form id="userForm">
                            <input type="text" id="fullname" class="swal2-input" placeholder="Full Name" required>
                            <input type="email" id="email" class="swal2-input" placeholder="Email" required>
                            <input type="text" id="mof_staff_id" class="swal2-input" placeholder="MOF Staff ID">
                            <select id="role" class="swal2-select" required>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                            <input type="password" id="password" class="swal2-input" placeholder="Password" required>
                        </form>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Add User',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const fullname = document.getElementById('fullname').value;
                        const email = document.getElementById('email').value;
                        const mof_staff_id = document.getElementById('mof_staff_id').value;
                        const role = document.getElementById('role').value;
                        const password = document.getElementById('password').value;
                        
                        if (!fullname || !email || !password) {
                            Swal.showValidationMessage('Please fill in all required fields');
                            return false;
                        }
                        
                        return { fullname, email, mof_staff_id, role, password };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        addUser(result.value);
                    }
                });
            }
            
            function showEditUserModal(userId) {
                // In a real implementation, you would fetch user details from the server
                // For this example, we'll just show a placeholder modal
                Swal.fire({
                    title: 'Edit User',
                    text: 'Edit functionality would fetch user details and show them here',
                    icon: 'info'
                });
            }
            
            function confirmDeleteUser(userId) {
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
                        // In a real implementation, you would send a delete request to the server
                        Swal.fire(
                            'Deleted!',
                            'User has been deleted (simulated).',
                            'success'
                        );
                        // Then you would refresh the user list
                    }
                });
            }
            
            function addUser(userData) {
                // In a real implementation, you would send this to your server
                console.log('Adding user:', userData);
                Swal.fire(
                    'Added!',
                    'User has been added (simulated).',
                    'success'
                );
                // Then you would refresh the user list
            }
        });
    </script>
</body>
</html>