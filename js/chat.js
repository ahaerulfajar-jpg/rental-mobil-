/**
 * Chat functionality for n8n integration
 */

// Global chat manager instance to prevent multiple initializations
let globalChatManager = null;

class ChatManager {
    constructor(options = {}) {
        this.apiUrl = options.apiUrl || 'app/api/chat_api.php';
        this.isLoading = false;
        this.container = null;
        this.messagesContainer = null;
        this.inputElement = null;
        this.sendButton = null;
        this.isPopupMode = options.isPopupMode || false;
        
        // Load history fresh each time
        this.conversationHistory = this.loadHistory();
        
        this.init();
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        // Find or create chat container
        if (this.isPopupMode) {
            this.container = document.querySelector('.chat-popup');
            this.messagesContainer = this.container?.querySelector('.chat-popup-body');
        } else {
            this.container = document.querySelector('.chat-page');
            this.messagesContainer = this.container?.querySelector('.chat-page-body');
        }

        if (!this.messagesContainer) {
            console.error('Chat container not found');
            return;
        }

        // Find input and send button
        if (this.isPopupMode) {
            this.inputElement = this.container?.querySelector('.chat-input');
            this.sendButton = this.container?.querySelector('.chat-send-button');
        } else {
            this.inputElement = document.querySelector('.chat-page-input');
            this.sendButton = document.querySelector('.chat-page-send-button');
        }

        // Setup event listeners
        this.setupEventListeners();
        
        // Always reload history from storage and render
        this.conversationHistory = this.loadHistory();
        this.renderHistory();
    }

    setupEventListeners() {
        // Remove existing listeners by cloning elements (prevents duplicates)
        if (this.sendButton && !this.sendButton.dataset.listenerAttached) {
            const sendHandler = () => this.sendMessage();
            this.sendButton.addEventListener('click', sendHandler);
            this.sendButton.dataset.listenerAttached = 'true';
        }

        // Enter key to send
        if (this.inputElement && !this.inputElement.dataset.listenerAttached) {
            const keyHandler = (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            };
            this.inputElement.addEventListener('keypress', keyHandler);
            this.inputElement.dataset.listenerAttached = 'true';
        }

        // Close button (for popup)
        const closeButton = this.container?.querySelector('.chat-close');
        if (closeButton && !closeButton.dataset.listenerAttached) {
            closeButton.addEventListener('click', () => this.closePopup());
            closeButton.dataset.listenerAttached = 'true';
        }
    }

    async sendMessage() {
        const message = this.inputElement?.value.trim();
        
        if (!message || this.isLoading) {
            return;
        }

        // Clear input
        this.inputElement.value = '';

        // Add user message to UI
        this.addMessage(message, 'user');

        // Show loading indicator
        this.showLoading();

        // Disable input and button
        this.setLoadingState(true);

        try {
            // Send to API
            const response = await fetch(this.apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    message: message,
                    conversation_history: this.conversationHistory
                })
            });

            const data = await response.json();

            // Hide loading indicator
            this.hideLoading();

            if (data.success) {
                // Add bot response to UI
                this.addMessage(data.message, 'bot');
                
                // Save to history
                this.conversationHistory.push({
                    role: 'user',
                    message: message,
                    timestamp: new Date().toISOString()
                });
                this.conversationHistory.push({
                    role: 'bot',
                    message: data.message,
                    timestamp: data.timestamp || new Date().toISOString()
                });
                this.saveHistory();
            } else {
                // Show error
                this.showError(data.error || 'Failed to send message. Please try again.');
            }
        } catch (error) {
            console.error('Chat error:', error);
            this.hideLoading();
            this.showError('Network error. Please check your connection and try again.');
        } finally {
            // Re-enable input and button
            this.setLoadingState(false);
        }
    }

    addMessage(text, type, timestamp = null) {
        if (!this.messagesContainer) return;

        // Remove empty state if exists
        const emptyState = this.messagesContainer.querySelector('.chat-empty-state');
        if (emptyState) {
            emptyState.remove();
        }

        // Create message element
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${type}`;

        const contentDiv = document.createElement('div');
        contentDiv.className = 'chat-message-content';
        contentDiv.textContent = text;

        const timeDiv = document.createElement('div');
        timeDiv.className = 'chat-message-time';
        const messageTime = timestamp || new Date();
        timeDiv.textContent = this.formatTime(messageTime);

        messageDiv.appendChild(contentDiv);
        messageDiv.appendChild(timeDiv);
        this.messagesContainer.appendChild(messageDiv);

        // Scroll to bottom
        this.scrollToBottom();
    }

    showLoading() {
        if (!this.messagesContainer) return;

        // Remove existing loading indicator
        this.hideLoading();

        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'chat-message bot';
        loadingDiv.id = 'chat-loading-indicator';

        const loadingContent = document.createElement('div');
        loadingContent.className = 'chat-loading';
        loadingContent.innerHTML = '<span></span><span></span><span></span>';

        loadingDiv.appendChild(loadingContent);
        this.messagesContainer.appendChild(loadingDiv);

        this.scrollToBottom();
    }

    hideLoading() {
        const loadingIndicator = document.getElementById('chat-loading-indicator');
        if (loadingIndicator) {
            loadingIndicator.remove();
        }
    }

    showError(message) {
        if (!this.messagesContainer) return;

        const errorDiv = document.createElement('div');
        errorDiv.className = 'chat-error-message';
        errorDiv.textContent = message;

        this.messagesContainer.appendChild(errorDiv);
        this.scrollToBottom();

        // Auto remove after 5 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }

    setLoadingState(loading) {
        this.isLoading = loading;
        
        if (this.inputElement) {
            this.inputElement.disabled = loading;
        }
        
        if (this.sendButton) {
            this.sendButton.disabled = loading;
        }
    }

    scrollToBottom() {
        if (this.messagesContainer) {
            this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
        }
    }

    formatTime(date) {
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    }

    loadHistory() {
        try {
            // Try localStorage first (persists across sessions)
            let history = localStorage.getItem('chat_history');
            
            // If no localStorage, try sessionStorage (persists across page navigations in same session)
            if (!history) {
                history = sessionStorage.getItem('chat_history');
            }
            
            if (history) {
                const parsed = JSON.parse(history);
                // Keep only last 50 messages to avoid storage issues
                return parsed.slice(-50);
            }
        } catch (error) {
            console.error('Error loading chat history:', error);
        }
        return [];
    }

    saveHistory() {
        try {
            const historyJson = JSON.stringify(this.conversationHistory);
            
            // Save to both localStorage and sessionStorage for redundancy
            localStorage.setItem('chat_history', historyJson);
            sessionStorage.setItem('chat_history', historyJson);
        } catch (error) {
            console.error('Error saving chat history:', error);
            // If localStorage fails (quota exceeded), try sessionStorage only
            try {
                sessionStorage.setItem('chat_history', JSON.stringify(this.conversationHistory));
            } catch (e) {
                console.error('Error saving to sessionStorage:', e);
            }
        }
    }

    renderHistory() {
        if (!this.messagesContainer) {
            return;
        }

        // Clear existing messages first
        this.messagesContainer.innerHTML = '';

        if (this.conversationHistory.length === 0) {
            // Show empty state
            const emptyState = document.createElement('div');
            emptyState.className = 'chat-empty-state';
            emptyState.innerHTML = `
                <i class="fa-solid fa-comments"></i>
                <p>Mulai percakapan dengan kami!</p>
            `;
            this.messagesContainer.appendChild(emptyState);
            return;
        }

        // Render all messages from history
        this.conversationHistory.forEach(item => {
            // Use stored timestamp if available, otherwise use current time
            const messageTime = item.timestamp ? new Date(item.timestamp) : new Date();
            this.addMessage(item.message, item.role, messageTime);
        });

        this.scrollToBottom();
    }

    clearHistory() {
        this.conversationHistory = [];
        localStorage.removeItem('chat_history');
        sessionStorage.removeItem('chat_history');
        if (this.messagesContainer) {
            this.messagesContainer.innerHTML = '';
            this.renderHistory();
        }
    }

    closePopup() {
        const popup = document.querySelector('.chat-popup');
        if (popup) {
            popup.classList.remove('active');
        }
    }
}

// Initialize chat for popup mode
function initChatPopup() {
    const popup = document.querySelector('.chat-popup');
    if (!popup) return;
    
    // Always reload history from storage when popup opens
    // This ensures history persists across page navigations
    if (!popup.dataset.initialized) {
        // Create new instance
        globalChatManager = new ChatManager({ isPopupMode: true });
        popup.dataset.initialized = 'true';
    } else {
        // If already initialized (from previous page load), reload history and re-render
        if (globalChatManager) {
            // Reload history from storage
            globalChatManager.conversationHistory = globalChatManager.loadHistory();
            // Reconnect to DOM elements
            globalChatManager.messagesContainer = popup.querySelector('.chat-popup-body');
            globalChatManager.inputElement = popup.querySelector('.chat-input');
            globalChatManager.sendButton = popup.querySelector('.chat-send-button');
            // Re-render history
            if (globalChatManager.messagesContainer) {
                globalChatManager.renderHistory();
            }
        } else {
            // If manager was lost (page reload), create new one
            globalChatManager = new ChatManager({ isPopupMode: true });
        }
    }
}

// Initialize chat for page mode
function initChatPage() {
    const page = document.querySelector('.chat-page');
    if (page) {
        globalChatManager = new ChatManager({ isPopupMode: false });
    }
}

// Floating button functionality
function initChatButton() {
    const chatButton = document.querySelector('.chat-floating-button');
    const chatPopup = document.querySelector('.chat-popup');
    
    if (chatButton && chatPopup) {
        // Remove existing event listeners by cloning
        const newButton = chatButton.cloneNode(true);
        chatButton.parentNode.replaceChild(newButton, chatButton);
        
        newButton.addEventListener('click', () => {
            chatPopup.classList.toggle('active');
            if (chatPopup.classList.contains('active')) {
                // Initialize or refresh chat
                initChatPopup();
            }
        });
    }
}

// Save history before page unload
window.addEventListener('beforeunload', () => {
    if (globalChatManager && globalChatManager.conversationHistory) {
        globalChatManager.saveHistory();
    }
});

// Also save on visibility change (when user switches tabs)
document.addEventListener('visibilitychange', () => {
    if (globalChatManager && globalChatManager.conversationHistory) {
        globalChatManager.saveHistory();
    }
});

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initChatButton();
        initChatPage();
    });
} else {
    initChatButton();
    initChatPage();
}

