<header class="header">
        <div class="header-left">
        <img src="https://upload.wikimedia.org/wikipedia/commons/5/59/Coat_of_arms_of_Ghana.svg" alt="Ghana Coat of Arms" style="height:40px;width:auto;vertical-align:middle;" />
        <span class="header-url">
            <?php $is_logged_in = "false";?>
            <?php if ($is_logged_in): ?>
                <?php echo htmlspecialchars($user_email ?? ''); ?>
            <?php else: ?>
                You are logged in as a Guest ..
                <button id="loginBtn" style="margin-left:8px;padding:4px 12px;border-radius:12px;border:none;background:var(--accent-green);color:#fff;cursor:pointer;font-size:0.95em;">Login</button>
            <?php endif; ?>

            </span>
        </div>
        <div class="header-right">


<a href="#" class="upgrade-button" id="darkModeToggle">Dark Mode</a>

            <?php if (isset(
$page) && ($page === 'faqs' || $page === 'resources')): ?>
                <button class="action-btn backbtn" onclick="window.location.href='../index.php'">Back to SmartDesk</button>
            <?php else: ?>
                <button class="icon-button" id="settingsButton" aria-label="Settings">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 8 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 5 15.4a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 5 8.6a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 8 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09c0 .66.39 1.26 1 1.51a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.66 0 1.26.39 1.51 1H21a2 2 0 0 1 0 4h-.09c-.25 0-.48.09-.68.26z"></path></svg>
                </button>
                <div class="settings-dropdown" id="settingsDropdown">
                    <ul>
                        <li><button class="settings-option" id="changePasswordOption">Change Password</button></li>
                        <li><button class="settings-option" id="clearHistoryOption">Clear Chat History</button></li>
                        <li><button class="settings-option" id="defaultAssistantOption">Default Chat Assistant</button></li>
                        <li><button class="settings-option" id="fontSizeOption">Font Size</button></li>
                    </ul>
                </div>
                <div class="user-avatar" id="userAvatar">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <circle cx="20" cy="20" r="20" fill="#D3D3D3"/>
                      <ellipse cx="20" cy="16" rx="7" ry="7" fill="#B0B0B0"/>
                      <ellipse cx="20" cy="29" rx="12" ry="7" fill="#B0B0B0"/>
                    </svg>
                    <div class="avatar-dropdown" id="avatarDropdown">
                        <button class="avatar-option" id="privacyPolicyOption">Privacy Policy</button>
                        <button class="avatar-option" id="logoutOption">Logout</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </header>
