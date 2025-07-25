document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const periodTabs = document.querySelectorAll('.period-tab');
    const historyLists = document.querySelectorAll('.history-list');
    
    periodTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            periodTabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Get the period from data attribute
            const period = this.dataset.period;
            
            // Hide all history lists
            historyLists.forEach(list => {
                list.classList.remove('active');
            });
            
            // Show the selected history list
            document.getElementById(`${period}History`).classList.add('active');
        });
    });
    
    // Action buttons functionality
    const actionButtons = document.querySelectorAll('.action-btn');
    
    actionButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            actionButtons.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            // Handle different button actions
            const btnId = this.id;
            switch(btnId) {
                case 'newChatBtn':
                    // Logic for new chat
                    console.log('New chat clicked');
                    break;
                case 'faqsBtn':
                    // Logic for FAQs
                    console.log('FAQs clicked');
                    break;
                case 'resourcesBtn':
                    // Logic for resources
                    console.log('Resources clicked');
                    break;
            }
        });
    });
    
    // Clear history button
    const clearHistoryBtn = document.getElementById('clearHistoryButton');
    if (clearHistoryBtn) {
        clearHistoryBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear all chat history?')) {
                // Clear all history lists
                historyLists.forEach(list => {
                    list.innerHTML = '<div class="empty-state">No history available</div>';
                });
            }
        });
    }
    
    // History item click handler
    const historyItems = document.querySelectorAll('.history-item');
    historyItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            historyItems.forEach(i => i.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');
            
            // Here you would load the chat history for this item
            const chatTitle = this.querySelector('.history-title').textContent;
            console.log(`Loading chat: ${chatTitle}`);
        });
    });
});