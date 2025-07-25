 // 3. Default Chat Assistant
 document.getElementById('defaultAssistantOption').addEventListener('click', async () => {
    settingsDropdown.classList.remove('open');
    settingsOpen = false;
    await Swal.fire({
        title: 'Default Chat Assistant',
        html: `
            <div style="text-align:left;">
                <label><input type="radio" name="assistantType" value="text" checked> Text</label><br>
                <label style="color:#aaa;"><input type="radio" name="assistantType" value="text_image" disabled> Text & Image (coming soon)</label><br>
                <label style="color:#aaa;"><input type="radio" name="assistantType" value="voice" disabled> Voice (coming soon)</label>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'OK',
        focusConfirm: false
    });
});

// 4. Font Size
const fontSizes = [
    { label: 'Small', value: '14px' },
    { label: 'Medium', value: '16px' },
    { label: 'Large', value: '18px' },
    { label: 'Huge', value: '22px' }
];
let currentFontSize = localStorage.getItem('mof_font_size') || '16px';
document.documentElement.style.setProperty('--mof-font-size', currentFontSize);

document.getElementById('fontSizeOption').addEventListener('click', async () => {
    settingsDropdown.classList.remove('open');
    settingsOpen = false;
    let selectedIdx = fontSizes.findIndex(f => f.value === currentFontSize);
    if (selectedIdx === -1) selectedIdx = 1;
    await Swal.fire({
        title: 'Font Size',
        html: `
            <div style="margin:20px 0 10px 0;">
                <input type="range" min="0" max="3" value="${selectedIdx}" id="fontSizeSlider" style="width: 100%;">
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.95em;color:#888;">
                <span>Small</span><span>Medium</span><span>Large</span><span>Huge</span>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'OK',
        didOpen: () => {
            const slider = Swal.getPopup().querySelector('#fontSizeSlider');
            slider.addEventListener('input', (e) => {
                const idx = parseInt(e.target.value);
                currentFontSize = fontSizes[idx].value;
                document.documentElement.style.setProperty('--mof-font-size', currentFontSize);
            });
        },
        preConfirm: () => {
            localStorage.setItem('mof_font_size', currentFontSize);
        }
    });
});

// Apply font size on load
document.documentElement.style.setProperty('--mof-font-size', currentFontSize);

// --- Avatar Dropdown ---
const userAvatar = document.getElementById('userAvatar');
const avatarDropdown = document.getElementById('avatarDropdown');
let avatarOpen = false;
userAvatar.addEventListener('click', (e) => {
    e.stopPropagation();
    avatarOpen = !avatarOpen;
    if (avatarOpen) {
        avatarDropdown.classList.add('open');
    } else {
        avatarDropdown.classList.remove('open');
    }
});
document.addEventListener('click', (e) => {
    if (avatarOpen && !avatarDropdown.contains(e.target) && e.target !== userAvatar) {
        avatarDropdown.classList.remove('open');
        avatarOpen = false;
    }
});
// Privacy Policy Modal
const privacyModal = document.getElementById('privacyModal');
const privacyModalClose = document.getElementById('privacyModalClose');
if (privacyModal && privacyModalClose) {
    document.getElementById('privacyPolicyOption').addEventListener('click', () => {
        avatarDropdown.classList.remove('open');
        avatarOpen = false;
        privacyModal.classList.add('open');
    });
    privacyModalClose.addEventListener('click', () => {
        privacyModal.classList.remove('open');
    });
    privacyModal.addEventListener('click', (e) => {
        if (e.target === privacyModal) {
            privacyModal.classList.remove('open');
        }
    });
};
// Logout Option
document.getElementById('logoutOption').addEventListener('click', async () => {
    avatarDropdown.classList.remove('open');
    avatarOpen = false;
    const result = await Swal.fire({
        title: 'Log out?',
        text: 'Are you sure you want to log out?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, log out',
        cancelButtonText: 'Cancel'
    });
    if (result.isConfirmed) {
        const response = await fetch('./api/logout.php', { method: 'POST' });
        const data = await response.json();
        if (data.success) {
            window.location.href = './auth/';
        } else {
            Swal.fire('Error', data.message || 'Failed to log out', 'error');
        }
    }
});

// --- Dark Mode Toggle ---
const darkModeButton = document.querySelector('.upgrade-button');
if (darkModeButton) {
    darkModeButton.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        darkModeButton.textContent = isDark ? 'Light Mode' : 'Dark Mode';
        localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
        // Prevent multiple refreshes if button is clicked rapidly
        if (!window.__darkModeRefreshTimeout) {
            window.__darkModeRefreshTimeout = setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    });
    // On load, apply dark mode if set
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
        darkModeButton.textContent = 'Light Mode';
    }

    
}


