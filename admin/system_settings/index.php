<?php require_once "../components/session.php";?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings | MOF Smart Desk</title>
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
        
        .settings-section {
            margin-bottom: var(--space-xl);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: var(--space-md);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }
        
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: var(--space-lg);
        }
        
        .setting-card {
            background-color: var(--white);
            border-radius: var(--card-radius);
            padding: var(--space-md);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .setting-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }
        
        .setting-card label {
            display: block;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: var(--space-xs);
        }
        
        .setting-card select,
        .setting-card input[type="text"],
        .setting-card input[type="number"] {
            width: 100%;
            padding: var(--space-sm);
            border: 1px solid var(--medium-gray);
            border-radius: var(--btn-radius);
            font-size: 1rem;
        }
        
        .setting-card select:focus,
        .setting-card input[type="text"]:focus,
        .setting-card input[type="number"]:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 5px rgba(99, 102, 241, 0.3);
        }
        
        .setting-card.checkbox {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            padding: var(--space-md);
        }
        
        .setting-card.checkbox input {
            width: auto;
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
            
            .settings-grid {
                grid-template-columns: 1fr;
            }
            
            .setting-card {
                padding: var(--space-sm);
            }
        }
    </style>
</head>
<body>
    <?php require_once "../nav/index.php"?>
    
    <main class="main-content">
        <div class="header">
            <h1 class="page-title">System Settings</h1>
            <div class="action-buttons">
                <button class="btn btn-primary" id="saveSettingsBtn">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </div>
        </div>
        
        <!-- General Settings -->
        <div class="settings-section">
            <h2 class="section-title"><i class="fas fa-cog"></i> General Settings</h2>
            <div class="settings-grid">
                <div class="setting-card">
                    <label for="systemName">System Name</label>
                    <input type="text" id="systemName" name="systemName" value="MOF Smart Desk">
                </div>
                <div class="setting-card">
                    <label for="defaultLanguage">Default Language</label>
                    <select id="defaultLanguage" name="defaultLanguage">
                        <option value="en" selected>English</option>
                        <option value="fr">French</option>
                        <option value="ms">Malay</option>
                    </select>
                </div>
                <div class="setting-card">
                    <label for="timezone">Timezone</label>
                    <select id="timezone" name="timezone">
                        <option value="Africa/Accra" selected>Africa/Accra</option>
                        <option value="UTC">UTC</option>
                        <option value="Asia/Kuala_Lumpur">Asia/Kuala Lumpur</option>
                    </select>
                </div>
                <div class="setting-card">
                    <label for="defaultCurrency">Default Currency</label>
                    <select id="defaultCurrency" name="defaultCurrency">
                        <option value="GHS" selected>GHS</option>
                        <option value="USD">USD</option>
                        <option value="MYR">MYR</option>
                    </select>
                </div>
                <div class="setting-card checkbox">
                    <input type="checkbox" id="darkMode" name="darkMode">
                    <label for="darkMode">Enable Dark Mode</label>
                </div>
            </div>
        </div>
        
        <!-- Security Settings -->
        <div class="settings-section">
            <h2 class="section-title"><i class="fas fa-lock"></i> Security Settings</h2>
            <div class="settings-grid">
                <div class="setting-card">
                    <label for="passwordExpiration">Password Expiration Period (days)</label>
                    <input type="number" id="passwordExpiration" name="passwordExpiration" value="90" min="30" max="365">
                </div>
                <div class="setting-card checkbox">
                    <input type="checkbox" id="twoFactorAuth" name="twoFactorAuth">
                    <label for="twoFactorAuth">Two-Factor Authentication (2FA)</label>
                </div>
                <div class="setting-card">
                    <label for="sessionTimeout">Session Timeout Duration (minutes)</label>
                    <input type="number" id="sessionTimeout" name="sessionTimeout" value="15" min="5" max="60">
                </div>
            </div>
        </div>
        
        <!-- Email & Notification Settings -->
        <div class="settings-section">
            <h2 class="section-title"><i class="fas fa-envelope"></i> Email & Notification Settings</h2>
            <div class="settings-grid">
                <div class="setting-card">
                    <label for="systemEmail">System Email Address</label>
                    <input type="text" id="systemEmail" name="systemEmail" value="admin@mofsmartdesk.gov">
                </div>
                <div class="setting-card checkbox">
                    <input type="checkbox" id="emailAlerts" name="emailAlerts" checked>
                    <label for="emailAlerts">Enable Email Alerts</label>
                </div>
                <div class="setting-card checkbox">
                    <input type="checkbox" id="notifyUserSignup" name="notifyUserSignup" checked>
                    <label for="notifyUserSignup">Notify on User Signup</label>
                </div>
            </div>
        </div>
        
        <!-- User & Access Control -->
        <div class="settings-section">
            <h2 class="section-title"><i class="fas fa-users"></i> User & Access Control</h2>
            <div class="settings-grid">
                <div class="setting-card checkbox">
                    <input type="checkbox" id="newUserRegistration" name="newUserRegistration" checked>
                    <label for="newUserRegistration">Enable New User Registration</label>
                </div>
                <div class="setting-card">
                    <label for="defaultUserRole">Default User Role on Signup</label>
                    <select id="defaultUserRole" name="defaultUserRole">
                        <option value="guest" selected>Guest</option>
                        <option value="officer">Officer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- System Maintenance -->
        <div class="settings-section">
            <h2 class="section-title"><i class="fas fa-tools"></i> System Maintenance</h2>
            <div class="settings-grid">
                <div class="setting-card">
                    <label for="systemStatus">System Status</label>
                    <select id="systemStatus" name="systemStatus">
                        <option value="online" selected>Online</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="offline">Offline</option>
                    </select>
                </div>
                <div class="setting-card">
                    <label for="backupInterval">Auto Backup Interval</label>
                    <select id="backupInterval" name="backupInterval">
                        <option value="daily" selected>Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
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

            // Save settings button click handler
            document.getElementById('saveSettingsBtn').addEventListener('click', saveSettings);
            
            function saveSettings() {
                const formData = new FormData();
                const inputs = document.querySelectorAll('.setting-card input, .setting-card select');
                
                const settings = {};
                inputs.forEach(input => {
                    if (input.type === 'checkbox') {
                        settings[input.name] = input.checked;
                    } else {
                        settings[input.name] = input.value;
                    }
                });
                
                // Validate inputs
                if (!settings.systemName) {
                    Swal.fire({
                        title: 'Invalid Input',
                        text: 'System Name cannot be empty.',
                        icon: 'error'
                    });
                    return;
                }
                if (settings.passwordExpiration < 30 || settings.passwordExpiration > 365) {
                    Swal.fire({
                        title: 'Invalid Input',
                        text: 'Password Expiration Period must be between 30 and 365 days.',
                        icon: 'error'
                    });
                    return;
                }
                if (settings.sessionTimeout < 5 || settings.sessionTimeout > 60) {
                    Swal.fire({
                        title: 'Invalid Input',
                        text: 'Session Timeout Duration must be between 5 and 60 minutes.',
                        icon: 'error'
                    });
                    return;
                }
                
                // Simulate saving settings to the server
                console.log('Saving settings:', settings);
                
                Swal.fire({
                    title: 'Settings Saved!',
                    text: 'System settings have been updated (simulated).',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>
</body>
</html>