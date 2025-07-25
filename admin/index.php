<?php
session_start();

// Handle login POST
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    if (!$email || !$password) {
        $login_error = 'Please enter both email and password.';
    } else {
        // IMPORTANT: Replace 'root' and '' with your actual database credentials.
        // For production, use more secure methods to store and access credentials.
        $mysqli = new mysqli('localhost', 'root', '', 'mof_smartdesk');
        if ($mysqli->connect_errno) {
            $login_error = 'Database connection failed. Please try again later.';
            // Log the actual error for debugging, but don't show to user
            // error_log("Failed to connect to MySQL: " . $mysqli->connect_error);
        } else {
            $stmt = $mysqli->prepare('SELECT id, password FROM admin WHERE email = ? LIMIT 1');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();

            // IMPORTANT: Use password_verify() for hashed passwords in a real application.
            // For this example, assuming plain text password in DB as per original code.
            // In a real scenario, it should be: password_verify($password, $admin['password'])
            if ($admin && $password === $admin['password']) { // Changed for demonstration to match original behavior
            // if ($admin && password_verify($password, $admin['password'])) { // Correct way for hashed passwords
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_email'] = $email;
                header('Location: dashboard/');
                exit;
            } else {
                $login_error = 'Invalid credentials. Please try again.';
            }
            $stmt->close();
            $mysqli->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministry of Finance - Admin Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-bg: #FFFFFF;
            --secondary-bg: #F0F4F8; /* Lighter background for body */
            --header-bg: #E3F2FD; /* Light blue header */
            --text-dark: #2C3E50; /* Darker text for contrast */
            --text-light: #7F8C8D; /* Lighter text for subtle elements */
            --accent-blue: #2196F3; /* Primary accent blue */
            --accent-blue-dark: #1976D2; /* Darker blue for hover */
            --error-red: #EF5350;
            --border-light: #CFD8DC; /* Subtle border color */
            --shadow-light: rgba(0, 0, 0, 0.08);
            --shadow-medium: rgba(0, 0, 0, 0.15);
            --transition-speed: 0.3s;
            --border-radius-sm: 8px;
            --border-radius-md: 12px;
            --padding-lg: 60px; /* More spacious padding */
            --padding-md: 32px;
            --padding-sm: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--secondary-bg);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            width: 100%;
            height: 80px; /* Slightly taller header */
            background-color: var(--header-bg);
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 var(--padding-md);
            box-shadow: 0 2px 8px var(--shadow-light);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px; /* Increased gap */
        }

        .header-title {
            font-weight: 700;
            font-size: 1.4rem; /* Larger title */
            color: var(--accent-blue-dark);
        }

        .security-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            color: var(--accent-blue-dark);
            background-color: rgba(33, 150, 243, 0.1); /* Lighter blue background */
            padding: 6px 12px;
            border-radius: 20px; /* More rounded */
            font-weight: 500;
        }

        /* Main login container */
        .login-container {
            display: flex;
            flex: 1;
            align-items: center;
            justify-content: center;
            padding: var(--padding-lg);
            animation: fadeIn 0.8s ease-out; /* Slower fade-in */
        }

        .login-card {
            background-color: var(--primary-bg);
            border-radius: var(--border-radius-md);
            box-shadow: 0 10px 40px var(--shadow-medium); /* More prominent shadow */
            width: 100%;
            max-width: 480px; /* Slightly wider card */
            overflow: hidden;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            position: relative;
        }

        .login-card:hover {
            transform: translateY(-8px); /* More pronounced lift */
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
        }

        .login-header {
            background-color: var(--accent-blue);
            color: white;
            padding: var(--padding-md);
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .login-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px; /* Thicker accent line */
            background: linear-gradient(90deg, var(--accent-blue), #64B5F6); /* Softer gradient */
        }

        .login-title {
            font-size: 1.8rem; /* Larger title */
            font-weight: 700;
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-size: 1rem; /* Slightly larger subtitle */
            opacity: 0.95;
            line-height: 1.4;
        }

        .login-body {
            padding: var(--padding-md);
        }

        /* Form elements */
        .form-group {
            margin-bottom: var(--padding-sm);
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 10px; /* More space */
            font-weight: 600; /* Bolder label */
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 48px; /* More padding and space for icon */
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius-sm);
            font-size: 1rem;
            transition: all var(--transition-speed) ease;
            background-color: var(--primary-bg);
            color: var(--text-dark);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.2); /* Softer shadow */
        }

        .input-icon {
            position: absolute;
            left: 15px; /* Adjusted icon position */
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .password-toggle {
            position: absolute;
            right: 15px; /* Adjusted toggle position */
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            cursor: pointer;
            transition: color 0.2s ease;
            font-size: 1.1rem;
        }

        .password-toggle:hover {
            color: var(--accent-blue);
        }

        /* Login button */
        .login-btn {
            width: 100%;
            padding: 16px; /* Larger button */
            background-color: var(--accent-blue);
            color: white;
            border: none;
            border-radius: var(--border-radius-sm);
            font-size: 1.1rem; /* Larger font */
            font-weight: 600;
            cursor: pointer;
            margin-top: var(--padding-sm);
            transition: all var(--transition-speed) ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.5px;
        }

        .login-btn:hover {
            background-color: var(--accent-blue-dark);
            transform: translateY(-3px); /* More pronounced lift */
            box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
            box-shadow: none;
        }

        /* Additional options */
        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: var(--padding-sm);
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-checkbox {
            accent-color: var(--accent-blue);
            width: 18px;
            height: 18px;
        }

        .forgot-password {
            color: var(--accent-blue);
            text-decoration: none;
            transition: color 0.2s ease;
            font-weight: 500;
        }

        .forgot-password:hover {
            color: var(--accent-blue-dark);
            text-decoration: underline;
        }

        /* Status messages */
        .status-message {
            padding: 14px; /* More padding */
            border-radius: var(--border-radius-sm);
            margin-bottom: var(--padding-sm);
            font-size: 0.95rem; /* Slightly larger font */
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05); /* Subtle shadow */
        }

        .error-message {
            background-color: #FFEBEE; /* Lighter red background */
            color: var(--error-red);
            border-left: 4px solid var(--error-red); /* Thicker border */
        }

        /* Keyframe animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .login-container {
                padding: var(--padding-md);
            }
            
            .header {
                height: 70px;
                padding: 0 var(--padding-sm);
            }

            .header-title {
                font-size: 1.1rem;
            }

            .security-badge {
                font-size: 0.8rem;
                padding: 4px 10px;
            }
            
            .login-card {
                max-width: 100%;
                margin: 0;
            }

            .login-title {
                font-size: 1.6rem;
            }

            .login-subtitle {
                font-size: 0.9rem;
            }

            .form-input {
                padding: 12px 16px 12px 45px;
            }

            .login-btn {
                padding: 14px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-left">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/59/Coat_of_arms_of_Ghana.svg" alt="Ghana Coat of Arms" style="height:45px;width:auto;vertical-align:middle;" />
            <span class="header-title">Ministry of Finance - SmartDesk Admin Portal</span>
        </div>
        <div class="security-badge">
            <i class="fas fa-lock"></i>
            <span>Secure Connection</span>
        </div>
    </header>

    <main class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2 class="login-title">Administrator Login</h2>
                <p class="login-subtitle">Access to sensitive government resources.<br>Authorized Personnel Only.</p>
            </div>
            <div class="login-body">
                <?php if ($login_error): ?>
                    <div class="status-message error-message" style="display:flex;">
                        <i class="fas fa-exclamation-triangle"></i> <span><?php echo htmlspecialchars($login_error); ?></span>
                    </div>
                <?php endif; ?>
                <form id="loginForm" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i> <input type="email" id="email" name="email" class="form-input" placeholder="e.g., your.email@mofep.gov.gh" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-key input-icon"></i> <input type="password" id="password" name="password" class="form-input" placeholder="Enter your secret password" required>
                            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                        </div>
                    </div>
                    <div class="login-options">
                        <label class="remember-me">
                            <input type="checkbox" class="remember-checkbox" id="rememberMe">
                            Keep me logged in
                        </label>
                        <a href="#" class="forgot-password">Forgot password?</a>
                    </div>
                    <button type="submit" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        Secure Login
                    </button>
                </form>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            togglePassword.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                // Toggle icons based on current state
                if (type === 'password') {
                    togglePassword.classList.remove('fa-eye-slash');
                    togglePassword.classList.add('fa-eye');
                } else {
                    togglePassword.classList.remove('fa-eye');
                    togglePassword.classList.add('fa-eye-slash');
                }
            });
        });
    </script>
</body>
</html>