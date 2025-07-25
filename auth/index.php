<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoF Smart Desk - Login / Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Variables for consistent styling, matching the chat UI */
        :root {
            --primary-bg: #FFFFFF;
            --header-bg: #F8F9FA; /* Light gray for subtle backgrounds */
            --text-dark: #2B2B2B;
            --text-light: #6c757d; /* Muted text for subheadings/placeholders */
            --accent-green: #007F5F; /* Ghana Green for primary actions */
            --accent-gold: #FFD700; /* Ghana Gold for secondary accents/buttons */
            --button-bg: #EAEAEA; /* Slightly darker light gray for general buttons */
            --button-text: #343A40;
            --border-light: #DCDCDC; /* Slightly darker light border color */
            --shadow-light: rgba(0, 0, 0, 0.08); /* Subtle shadow */
            --shadow-medium: rgba(0, 0, 0, 0.15); /* Medium shadow for cards */
            --error-red: #dc3545; /* Standard error color */
            --transition-speed: 0.3s; /* Slightly slower transition for smoother feel */
            --border-radius-sm: 10px; /* Slightly more rounded */
            --border-radius-md: 16px; /* Slightly more rounded */
            --padding-lg: 48px; /* Increased padding */
            --padding-md: 32px; /* Increased padding */
            --padding-sm: 20px; /* Increased padding */
        }

        /* Dark mode variables */
        body.dark-mode {
            --primary-bg: #1E1E1E; /* Darker background */
            --header-bg: #282828; /* Darker subtle background */
            --text-dark: #E0E0E0;
            --text-light: #A0A0A0;
            --accent-green: #00C896; /* Brighter green for dark mode */
            --accent-gold: #FFD700; /* Gold remains bright */
            --button-bg: #3A3A3A;
            --button-text: #E0E0E0;
            --border-light: #4A4A4A;
            --shadow-light: rgba(0, 0, 0, 0.4);
            --shadow-medium: rgba(0, 0, 0, 0.5);
            --error-red: #FF7B7B;
        }

        /* Base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--header-bg); /* Use a slightly darker background for the whole page */
            color: var(--text-dark);
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: var(--padding-md);
            transition: background-color var(--transition-speed) ease;
            /* Subtle radial gradient background */
            background-image: radial-gradient(circle at 10% 20%, var(--border-light) 0%, transparent 25%),
                              radial-gradient(circle at 90% 80%, var(--border-light) 0%, transparent 25%);
            background-size: 100% 100%;
            background-repeat: no-repeat;
        }

        body.dark-mode {
            background-image: radial-gradient(circle at 10% 20%, var(--border-light) 0%, transparent 25%),
                              radial-gradient(circle at 90% 80%, var(--border-light) 0%, transparent 25%);
        }

        .auth-container {
            background-color: var(--primary-bg);
            border-radius: var(--border-radius-md);
            box-shadow: 0 15px 40px var(--shadow-medium); /* Enhanced shadow */
            padding: var(--padding-lg);
            width: 100%;
            max-width: 480px; /* Slightly wider */
            text-align: center;
            border: 1px solid var(--border-light);
            transition: background-color var(--transition-speed) ease, border-color var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
        }

        .logo {
            margin-bottom: var(--padding-md);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px; /* Slightly more space */
        }

        .logo img {
            height: 65px; /* Slightly larger logo */
            width: auto;
        }

        .logo-text {
            font-size: 2rem; /* Slightly larger text */
            font-weight: 700;
            color: var(--text-dark);
            transition: color var(--transition-speed) ease;
        }

        h2 {
            font-size: 2rem; /* Larger heading */
            font-weight: 700; /* Bolder */
            margin-bottom: 10px; /* Reduced margin */
            color: var(--accent-green);
            transition: color var(--transition-speed) ease;
        }

        .subtitle {
            font-size: 1.05rem; /* Slightly larger subtitle */
            color: var(--text-light);
            margin-bottom: var(--padding-lg);
            transition: color var(--transition-speed) ease;
        }

        .form-toggle {
            display: flex;
            justify-content: center;
            margin-bottom: var(--padding-lg);
            background-color: var(--button-bg);
            border-radius: var(--border-radius-sm);
            padding: 6px; /* Slightly more padding */
            transition: background-color var(--transition-speed) ease;
        }

        .toggle-button {
            flex: 1;
            padding: 12px 18px; /* More padding */
            border: none;
            background-color: transparent;
            color: var(--text-dark);
            font-size: 1rem; /* Slightly larger font */
            font-weight: 600; /* Bolder */
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            transition: all var(--transition-speed) ease;
        }

        .toggle-button.active {
            background-color: var(--accent-green);
            color: white;
            box-shadow: 0 4px 8px var(--shadow-light); /* More pronounced shadow */
            transform: translateY(-2px); /* Slight lift */
        }

        .form-group {
            margin-bottom: var(--padding-sm);
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px; /* More space */
            font-size: 0.95rem; /* Slightly larger */
            font-weight: 500;
            color: var(--text-dark);
            transition: color var(--transition-speed) ease;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 14px 18px; /* More padding */
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius-sm);
            font-size: 1.05rem; /* Slightly larger font */
            color: var(--text-dark);
            background-color: var(--primary-bg);
            transition: border-color var(--transition-speed) ease, box-shadow var(--transition-speed) ease, background-color var(--transition-speed) ease, color var(--transition-speed) ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: var(--accent-green);
            box-shadow: 0 0 0 4px rgba(0, 127, 95, 0.25); /* More pronounced focus ring */
        }

        .error-message {
            color: var(--error-red);
            font-size: 0.85rem; /* Slightly larger error text */
            margin-top: 6px; /* More space */
            display: none; /* Hidden by default */
            font-weight: 500; /* Bolder error text */
        }

        button[type="submit"] {
            width: 100%;
            padding: 15px 25px; /* More padding */
            background-color: var(--accent-green);
            color: white;
            border: none;
            border-radius: var(--border-radius-sm);
            font-size: 1.1rem; /* Slightly larger font */
            font-weight: 600;
            cursor: pointer;
            margin-top: var(--padding-sm);
            transition: background-color var(--transition-speed) ease, transform var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
        }

        button[type="submit"]:hover {
            background-color: #006B3F; /* Slightly darker green */
            transform: translateY(-3px); /* More pronounced lift */
            box-shadow: 0 8px 15px var(--shadow-light); /* Shadow on hover */
        }

        button[type="submit"]:disabled {
            background-color: var(--text-light);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .loading-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px; /* More space */
            margin-top: var(--padding-sm);
            color: var(--accent-green);
            font-size: 1rem; /* Slightly larger font */
            display: none; /* Hidden by default */
            font-weight: 500;
        }

        .spinner {
            width: 22px; /* Slightly larger spinner */
            height: 22px;
            border: 3px solid rgba(0, 127, 95, 0.2);
            border-top-color: var(--accent-green);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .toggle-dark-mode {
            position: absolute;
            top: var(--padding-md);
            right: var(--padding-md);
            background-color: var(--button-bg);
            color: var(--text-dark);
            border: 1px solid var(--border-light);
            padding: 10px 18px; /* More padding */
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            font-size: 0.9rem; /* Slightly larger */
            font-weight: 500;
            transition: all var(--transition-speed) ease;
        }

        .toggle-dark-mode:hover {
            background-color: var(--border-light);
            box-shadow: 0 4px 8px var(--shadow-light);
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            body {
                padding: var(--padding-sm);
                background-image: none; /* Remove gradient on small screens */
            }
            .auth-container {
                padding: var(--padding-md);
                border-radius: var(--border-radius-sm);
                box-shadow: none; /* Remove shadow on small screens for a cleaner look */
                border: none; /* Remove border on small screens */
            }

            .logo img {
                height: 50px;
            }

            .logo-text {
                font-size: 1.6rem;
            }

            h2 {
                font-size: 1.6rem;
            }

            .subtitle {
                font-size: 0.95rem;
                margin-bottom: var(--padding-md);
            }

            .form-toggle {
                margin-bottom: var(--padding-md);
            }

            .toggle-button {
                padding: 10px 12px;
                font-size: 0.9rem;
            }

            input[type="email"],
            input[type="password"],
            input[type="text"] {
                padding: 12px 15px;
                font-size: 0.95rem;
            }

            button[type="submit"] {
                padding: 12px 20px;
                font-size: 1rem;
            }

            .toggle-dark-mode {
                top: 10px;
                right: 10px;
                padding: 8px 15px;
                font-size: 0.8rem;
            }

            .error-message {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <button class="toggle-dark-mode" id="darkModeToggle">Dark Mode</button>

    <div class="auth-container">
        <div class="logo">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/59/Coat_of_arms_of_Ghana.svg" alt="Ghana Coat of Arms">
            <span class="logo-text">MoF Smart Desk</span>
        </div>

        <h2 id="formTitle">Login to Your Account</h2>
        <p class="subtitle" id="formSubtitle">Access seamless assistance for citizens and staff.</p>

        <div class="form-toggle">
            <button class="toggle-button active" id="loginTab">Login</button>
            <button class="toggle-button" id="signupTab">Sign Up</button>
        </div>

        <!-- Login Form -->
        <form id="loginForm" class="auth-form">
            <div class="form-group">
                <label for="loginEmail">Email Address</label>
                <input type="email" id="loginEmail" placeholder="e.g., your.name@mof.gov.gh" required>
                <div class="error-message" id="loginEmailError">Please enter a valid email address.</div>
            </div>
            <div class="form-group">
                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" placeholder="Enter your password" required>
                <div class="error-message" id="loginPasswordError">Password is required.</div>
            </div>
            <button type="submit" id="loginSubmitBtn">Login</button>
            <div class="loading-indicator" id="loginLoading">
                <div class="spinner"></div>
                <span>Logging in...</span>
            </div>
        </form>

        <!-- Sign Up Form (hidden by default) -->
        <form id="signupForm" class="auth-form" style="display: none;">
            <div class="form-group">
                <label for="signupFullName">Full Name</label>
                <input type="text" id="signupFullName" placeholder="e.g., John Doe" required>
                <div class="error-message" id="signupFullNameError">Full name is required.</div>
            </div>
            <div class="form-group">
                <label for="signupEmail">Email Address</label>
                <input type="email" id="signupEmail" placeholder="e.g., your.name@mof.gov.gh" required>
                <div class="error-message" id="signupEmailError">Please enter a valid email address.</div>
            </div>
            <div class="form-group">
                <label for="signupPassword">Password</label>
                <input type="password" id="signupPassword" placeholder="Create a password" required>
                <div class="error-message" id="signupPasswordError">Password must be at least 6 characters.</div>
            </div>
            <div class="form-group">
                <label for="signupConfirmPassword">Confirm Password</label>
                <input type="password" id="signupConfirmPassword" placeholder="Confirm your password" required>
                <div class="error-message" id="signupConfirmPasswordError">Passwords do not match.</div>
            </div>
            <div class="form-group">
                <label for="signupStaffId">MoF Staff ID (Optional)</label>
                <input type="text" id="signupStaffId" placeholder="e.g., GHMof12345">
            </div>
            <button type="submit" id="signupSubmitBtn">Sign Up</button>
            <div class="loading-indicator" id="signupLoading">
                <div class="spinner"></div>
                <span>Creating account...</span>
            </div>
        </form>

        <div style="margin-top: 24px; text-align: center;">
            <a href="#" id="guestLoginLink" style="color: var(--accent-green); font-weight: 500; text-decoration: underline; cursor: pointer;">Log in as a Guest</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loginTab = document.getElementById('loginTab');
            const signupTab = document.getElementById('signupTab');
            const loginForm = document.getElementById('loginForm');
            const signupForm = document.getElementById('signupForm');
            const formTitle = document.getElementById('formTitle');
            const formSubtitle = document.getElementById('formSubtitle');
            const darkModeToggle = document.getElementById('darkModeToggle');

            // Form elements for Login
            const loginEmailInput = document.getElementById('loginEmail');
            const loginPasswordInput = document.getElementById('loginPassword');
            const loginSubmitBtn = document.getElementById('loginSubmitBtn');
            const loginLoading = document.getElementById('loginLoading');
            const loginEmailError = document.getElementById('loginEmailError');
            const loginPasswordError = document.getElementById('loginPasswordError');

            // Form elements for Sign Up
            const signupFullNameInput = document.getElementById('signupFullName');
            const signupEmailInput = document.getElementById('signupEmail');
            const signupPasswordInput = document.getElementById('signupPassword');
            const signupConfirmPasswordInput = document.getElementById('signupConfirmPassword');
            const signupStaffIdInput = document.getElementById('signupStaffId');
            const signupSubmitBtn = document.getElementById('signupSubmitBtn');
            const signupLoading = document.getElementById('signupLoading');
            const signupFullNameError = document.getElementById('signupFullNameError');
            const signupEmailError = document.getElementById('signupEmailError');
            const signupPasswordError = document.getElementById('signupPasswordError');
            const signupConfirmPasswordError = document.getElementById('signupConfirmPasswordError');

            // Guest login link
            const guestLoginLink = document.getElementById('guestLoginLink');
            if (guestLoginLink) {
                guestLoginLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    window.location.href = '../';
                });
            }

            // --- Utility Functions ---

            const isValidEmail = (email) => {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(String(email).toLowerCase());
            };

            const showError = (element, message) => {
                element.textContent = message;
                element.style.display = 'block';
            };

            const hideError = (element) => {
                element.style.display = 'none';
            };

            const setFormState = (isLoading, submitBtn, loadingIndicator) => {
                submitBtn.disabled = isLoading;
                loadingIndicator.style.display = isLoading ? 'flex' : 'none';
            };

            // --- Form Switching Logic ---

            const showLoginForm = () => {
                loginTab.classList.add('active');
                signupTab.classList.remove('active');
                loginForm.style.display = 'block';
                signupForm.style.display = 'none';
                formTitle.textContent = 'Login to Your Account';
                formSubtitle.textContent = 'Access seamless assistance for citizens and staff.';
                // Clear errors when switching forms
                hideError(loginEmailError);
                hideError(loginPasswordError);
                hideError(signupFullNameError);
                hideError(signupEmailError);
                hideError(signupPasswordError);
                hideError(signupConfirmPasswordError);
            };

            const showSignupForm = () => {
                signupTab.classList.add('active');
                loginTab.classList.remove('active');
                signupForm.style.display = 'block';
                loginForm.style.display = 'none';
                formTitle.textContent = 'Create Your Account';
                formSubtitle.textContent = 'Join us to get personalized support.';
                // Clear errors when switching forms
                hideError(loginEmailError);
                hideError(loginPasswordError);
                hideError(signupFullNameError);
                hideError(signupEmailError);
                hideError(signupPasswordError);
                hideError(signupConfirmPasswordError);
            };

            loginTab.addEventListener('click', showLoginForm);
            signupTab.addEventListener('click', showSignupForm);

            // --- Dark Mode Toggle ---

            darkModeToggle.addEventListener('click', () => {
                document.body.classList.toggle('dark-mode');
                const isDarkMode = document.body.classList.contains('dark-mode');
                darkModeToggle.textContent = isDarkMode ? 'Light Mode' : 'Dark Mode';
                localStorage.setItem('darkMode', isDarkMode ? 'enabled' : 'disabled');
            });

            // Apply dark mode preference on load
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
                darkModeToggle.textContent = 'Light Mode';
            }

            // --- Login Form Validation & Submission ---

            loginForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                let isValid = true;

                hideError(loginEmailError);
                hideError(loginPasswordError);

                const email = loginEmailInput.value.trim();
                const password = loginPasswordInput.value.trim();

                if (!isValidEmail(email)) {
                    showError(loginEmailError, 'Please enter a valid email address.');
                    isValid = false;
                }
                if (password.length < 6) {
                    showError(loginPasswordError, 'Password must be at least 6 characters.');
                    isValid = false;
                }

                if (!isValid) return;

                setFormState(true, loginSubmitBtn, loginLoading);

                try {
                    const formData = new FormData();
                    formData.append('email', email);
                    formData.append('password', password);

                    const response = await fetch('login.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    if (data.success) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Login Successful',
                            text: 'Welcome back!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        window.location.href = '../';
                    } else {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: data.message || 'Login failed.'
                        });
                    }
                } catch (error) {
                    console.error('Login error:', error);
                    showError(loginPasswordError, 'An error occurred during login. Please try again.');
                } finally {
                    setFormState(false, loginSubmitBtn, loginLoading);
                }
            });

            // --- Sign Up Form Validation & Submission ---

            signupForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                let isValid = true;

                hideError(signupFullNameError);
                hideError(signupEmailError);
                hideError(signupPasswordError);
                hideError(signupConfirmPasswordError);

                const fullName = signupFullNameInput.value.trim();
                const email = signupEmailInput.value.trim();
                const password = signupPasswordInput.value.trim();
                const confirmPassword = signupConfirmPasswordInput.value.trim();
                const staffId = signupStaffIdInput.value.trim(); // Optional

                if (!fullName) {
                    showError(signupFullNameError, 'Full name is required.');
                    isValid = false;
                }
                if (!isValidEmail(email)) {
                    showError(signupEmailError, 'Please enter a valid email address.');
                    isValid = false;
                }
                if (password.length < 6) {
                    showError(signupPasswordError, 'Password must be at least 6 characters.');
                    isValid = false;
                }
                if (password !== confirmPassword) {
                    showError(signupConfirmPasswordError, 'Passwords do not match.');
                    isValid = false;
                }

                if (!isValid) return;

                setFormState(true, signupSubmitBtn, signupLoading);

                try {
                    const formData = new FormData();
                    formData.append('fullName', fullName);
                    formData.append('email', email);
                    formData.append('password', password);
                    formData.append('staffId', staffId);

                    const response = await fetch('signup.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    if (data.success) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Account Created',
                            text: 'Account created successfully! Please log in.',
                            timer: 1800,
                            showConfirmButton: false
                        });
                        showLoginForm();
                    } else {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Sign Up Failed',
                            text: data.message || 'Sign up failed.'
                        });
                    }
                } catch (error) {
                    console.error('Sign up error:', error);
                    showError(signupEmailError, 'An error occurred during sign up. Please try again.');
                } finally {
                    setFormState(false, signupSubmitBtn, signupLoading);
                }
            });

            // Real-time input validation feedback (optional, but good UX)
            loginEmailInput.addEventListener('input', () => hideError(loginEmailError));
            loginPasswordInput.addEventListener('input', () => hideError(loginPasswordError));
            signupFullNameInput.addEventListener('input', () => hideError(signupFullNameError));
            signupEmailInput.addEventListener('input', () => hideError(signupEmailError));
            signupPasswordInput.addEventListener('input', () => {
                hideError(signupPasswordError);
                if (signupConfirmPasswordInput.value.trim() !== '' && signupPasswordInput.value !== signupConfirmPasswordInput.value) {
                    showError(signupConfirmPasswordError, 'Passwords do not match.');
                } else {
                    hideError(signupConfirmPasswordError);
                }
            });
            signupConfirmPasswordInput.addEventListener('input', () => {
                if (signupPasswordInput.value !== signupConfirmPasswordInput.value) {
                    showError(signupConfirmPasswordError, 'Passwords do not match.');
                } else {
                    hideError(signupConfirmPasswordError);
                }
            });
        });
    </script>
</body>
</html>
