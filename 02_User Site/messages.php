<?php
    include 'connection.php';
    // Load global config (DEFAULT_ADMIN_ID)
    if (file_exists(__DIR__ . '/../config.php')) {
        include __DIR__ . '/../config.php';
    }
    session_start();
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

    if(!$user_id){
       header('location: ../01_Admin Site/login.php');
    }
    
    // Get current user info
    $user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id='$user_id'");
    $current_user = mysqli_fetch_assoc($user_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <title>Live Messages - Kacchi Ganj</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Roboto, Arial, sans-serif;
            background: #f5f7fa;
            color: #333;
        }

        .messaging-container {
            display: flex;
            height: calc(100vh - 80px);
            max-width: 1400px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .conversations-sidebar {
            width: 300px;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            background: #fafbfc;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
        }

        .sidebar-header h2 {
            margin: 0;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .conversations-list {
            flex: 1;
            overflow-y: auto;
        }

        .conversation-item {
            padding: 12px 16px;
            border-bottom: 1px solid #e0e0e0;
            cursor: pointer;
            transition: background 0.2s;
            position: relative;
        }

        .conversation-item:hover {
            background: #e8f0ff;
        }

        .conversation-item.active {
            background: #e8f0ff;
            border-left: 4px solid #2563eb;
        }

        .conversation-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .conversation-item-name {
            font-weight: 600;
            color: #0f172a;
        }

        .unread-badge {
            background: #ef4444;
            color: white;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .conversation-item-preview {
            font-size: 0.85rem;
            color: #6b7280;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: white;
        }

        .chat-header {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            background: linear-gradient(135deg, #f8fafc 0%, #f0f2f5 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header-info h3 {
            margin: 0 0 4px 0;
            color: #0f172a;
        }

        .chat-header-info p {
            margin: 0;
            font-size: 0.85rem;
            color: #6b7280;
        }

        .messages-display {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .message-row {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-row.sent {
            justify-content: flex-end;
        }

        .message-bubble {
            max-width: 60%;
            padding: 12px 16px;
            border-radius: 12px;
            word-wrap: break-word;
            line-height: 1.4;
            font-size: 0.95rem;
        }

        .message-row.received .message-bubble {
            background: #e8f0ff;
            color: #0f172a;
            border-bottom-left-radius: 4px;
        }

        .message-row.sent .message-bubble {
            background: #2563eb;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .message-time {
            font-size: 0.75rem;
            color: #9ca3af;
            padding: 0 4px;
            align-self: flex-end;
            margin-bottom: 4px;
        }

        .chat-input-area {
            padding: 20px;
            border-top: 1px solid #e0e0e0;
            background: #f9fafb;
        }

        .input-form {
            display: flex;
            gap: 10px;
        }

        .chat-input-field {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            resize: none;
            max-height: 80px;
        }

        .chat-input-field:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .send-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .send-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        .empty-chat {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #9ca3af;
            font-size: 1.1rem;
            flex-direction: column;
            gap: 10px;
        }

        .empty-chat i {
            font-size: 3rem;
            opacity: 0.3;
        }

        /* New Conversation Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.3s;
        }

        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 15px;
        }

        .modal-header h2 {
            margin: 0;
            color: #2563eb;
        }

        .close-modal {
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
            transition: color 0.2s;
        }

        .close-modal:hover {
            color: #000;
        }

        .modal-form-group {
            margin-bottom: 15px;
        }

        .modal-form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 600;
        }

        .modal-form-group select,
        .modal-form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
            box-sizing: border-box;
        }

        .modal-form-group select:focus,
        .modal-form-group textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .modal-form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }

        .modal-btn-cancel {
            background: #e5e7eb;
            color: #333;
        }

        .modal-btn-cancel:hover {
            background: #d1d5db;
        }

        .modal-btn-start {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
        }

        .modal-btn-start:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        .new-conv-btn {
            margin: 10px;
            padding: 12px 20px;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
            width: calc(100% - 20px);
            justify-content: center;
        }

        .new-conv-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        @media (max-width: 768px) {
            .messaging-container {
                flex-direction: column;
                height: auto;
            }

            .conversations-sidebar {
                width: 100%;
                height: 200px;
                max-height: 30vh;
                overflow-y: auto;
            }

            .chat-area {
                height: 60vh;
            }

            .message-bubble {
                max-width: 85%;
            }
        }

        /* Scrollbar styling */
        .conversations-list::-webkit-scrollbar,
        .messages-display::-webkit-scrollbar {
            width: 6px;
        }

        .conversations-list::-webkit-scrollbar-track,
        .messages-display::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .conversations-list::-webkit-scrollbar-thumb,
        .messages-display::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <header class="header">
        <?php include 'nav.php'; ?>
    </header>

    <div class="messaging-container">
        <!-- Conversations Sidebar -->
        <div class="conversations-sidebar">
            <div class="sidebar-header">
                <h2><i class="ri-chat-2-line"></i> Messages</h2>
            </div>
            <button class="new-conv-btn" onclick="openNewConversationModal()">
                <i class="ri-add-line"></i> New Message
            </button>
            <div class="conversations-list" id="conversationsList">
                <div style="padding: 20px; text-align: center; color: #9ca3af;">Loading conversations...</div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <div class="chat-header">
                <div class="chat-header-info" id="chatHeaderInfo">
                    <h3>Select a conversation</h3>
                    <p>Start messaging</p>
                </div>
            </div>

            <div class="messages-display" id="messagesDisplay">
                <div class="empty-chat">
                    <i class="ri-chat-smile-2-line"></i>
                    <p>Select a conversation to start messaging</p>
                </div>
            </div>

            <div class="chat-input-area" id="chatInputArea" style="display: none;">
                <form class="input-form" id="messageForm">
                    <textarea class="chat-input-field" id="messageInput" placeholder="Type your message..." rows="1"></textarea>
                    <button type="submit" class="send-btn">
                        <i class="ri-send-plane-2-fill"></i> Send
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- New Conversation Modal -->
    <div id="newConversationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Start New Conversation</h2>
                <span class="close-modal" onclick="closeNewConversationModal()">&times;</span>
            </div>
            <form id="newConversationForm">
                <div class="modal-form-group">
                    <label for="recipientSelect">Select Admin/Support:</label>
                    <select id="recipientSelect" required>
                        <option value="">-- Loading admins --</option>
                    </select>
                </div>
                <div class="modal-form-group">
                    <label for="initialMessage">Your Message:</label>
                    <textarea id="initialMessage" placeholder="Type your message here..." required></textarea>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="modal-btn modal-btn-cancel" onclick="closeNewConversationModal()">Cancel</button>
                    <button type="submit" class="modal-btn modal-btn-start">
                        <i class="ri-send-plane-2-fill"></i> Start Chat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    let currentConversationId = null;
    let currentUserId = <?php echo $user_id; ?>;
    // expose PHP config to JS
    const DEFAULT_ADMIN_ID = <?php echo (defined('DEFAULT_ADMIN_ID') ? DEFAULT_ADMIN_ID : 1); ?>;
    // snapshots to avoid unnecessary re-renders and reduce flicker
    let prevConversationsJSON = '';
    let prevMessagesJSON = {}; // keyed by conversation id

        // Modal functions
        function openNewConversationModal() {
            document.getElementById('newConversationModal').style.display = 'block';
            loadAdminOptions();
        }

        function closeNewConversationModal() {
            document.getElementById('newConversationModal').style.display = 'none';
            document.getElementById('newConversationForm').reset();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('newConversationModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Handle new conversation form submission
        document.getElementById('newConversationForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const recipientId = document.getElementById('recipientSelect').value;
            const message = document.getElementById('initialMessage').value.trim();
            
            if (!recipientId || !message) {
                alert('Please fill all fields');
                return;
            }
            
            // Send initial message
            const formData = new FormData();
            formData.append('receiver_id', recipientId);
            formData.append('message', message);
            
            try {
                const response = await fetch('api_messages.php?action=send', {
                    method: 'POST',
                    body: formData
                });
                
                // Check if response is ok
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Invalid JSON response (start new conversation):', text);
                    throw new Error('Invalid JSON response: ' + e.message);
                }

                if (data.success) {
                    closeNewConversationModal();
                    loadConversations();
                    // Select the new conversation
                    setTimeout(() => {
                        selectConversation(recipientId, 'Support Admin');
                    }, 500);
                } else {
                    alert('Failed to start conversation: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error starting conversation:', error);
                alert('Error starting conversation: ' + error.message);
            }
        });

        // Load admin options
        async function loadAdminOptions() {
            try {
                const select = document.getElementById('recipientSelect');
                select.innerHTML = '<option value="">-- Select recipient --</option>';

                // Fetch admin users from server
                const resp = await fetch('../01_Admin Site/api_admin_messages.php?action=get_admins');
                const text = await resp.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Invalid JSON response when loading admins:', text);
                    // fallback to a default from config
                    select.innerHTML += `<option value="${DEFAULT_ADMIN_ID}">Support Admin</option>`;
                    return;
                }

                if (data.success && Array.isArray(data.admins) && data.admins.length > 0) {
                    data.admins.forEach(a => {
                        const name = a.name || (a.email ? a.email.split('@')[0] : 'Admin');
                        select.innerHTML += `<option value="${a.id}">${name}</option>`;
                    });
                } else {
                    // fallback to a default from config
                    select.innerHTML += `<option value="${DEFAULT_ADMIN_ID}">Support Admin</option>`;
                }
                
            } catch (error) {
                console.error('Error loading admins:', error);
                alert('Error loading admins');
            }
        }

        // Load conversations
        async function loadConversations() {
            try {
                const response = await fetch('api_messages.php?action=get_conversations');
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Invalid JSON response (load conversations):', text);
                    return;
                }

                if (data.success) {
                    // avoid re-rendering if conversations unchanged
                    const convJSON = JSON.stringify(data.conversations || []);
                    if (convJSON === prevConversationsJSON) return;
                    prevConversationsJSON = convJSON;

                    const list = document.getElementById('conversationsList');
                    list.innerHTML = '';

                    if (data.conversations.length === 0) {
                        list.innerHTML = '<div style="padding: 20px; text-align: center; color: #9ca3af;">No conversations yet</div>';
                        return;
                    }

                    data.conversations.forEach(conv => {
                        const item = document.createElement('div');
                        item.className = 'conversation-item';
                        item.onclick = () => selectConversation(conv.other_user_id, conv.user_name);

                        let unreadBadge = '';
                        if (conv.unread_count > 0) {
                            unreadBadge = `<span class="unread-badge">${conv.unread_count}</span>`;
                        }

                        item.innerHTML = `
                            <div class="conversation-item-header">
                                <span class="conversation-item-name">${conv.user_name}</span>
                                ${unreadBadge}
                            </div>
                            <div class="conversation-item-preview">${conv.last_message || 'No messages yet'}</div>
                        `;
                        list.appendChild(item);
                    });
                }
            } catch (error) {
                console.error('Error loading conversations:', error);
            }
        }

        // Select a conversation
        async function selectConversation(otherUserId, userName) {
            currentConversationId = otherUserId;
            
            // Update header
            document.getElementById('chatHeaderInfo').innerHTML = `
                <h3>${userName}</h3>
                <p>Chat with ${userName}</p>
            `;
            
            // Show input area
            document.getElementById('chatInputArea').style.display = 'block';
            
            // Load messages
            loadMessages();
            
            // Mark conversation item as active
            document.querySelectorAll('.conversation-item').forEach(item => {
                item.classList.remove('active');
            });
            event.target.closest('.conversation-item').classList.add('active');
        }

        // Load messages
        async function loadMessages() {
            try {
                // if user is typing, skip refreshing to avoid disrupting them
                const messageInput = document.getElementById('messageInput');
                if (messageInput && messageInput.value.trim() !== '') return;

                const response = await fetch(`api_messages.php?action=get_conversation&other_user_id=${currentConversationId}`);
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Invalid JSON response (load messages):', text);
                    return;
                }

                if (data.success) {
                    // avoid re-rendering if messages unchanged
                    const msgsJSON = JSON.stringify(data.messages || []);
                    if (prevMessagesJSON[currentConversationId] === msgsJSON) return;
                    prevMessagesJSON[currentConversationId] = msgsJSON;

                    const display = document.getElementById('messagesDisplay');
                    display.innerHTML = '';

                    data.messages.forEach(msg => {
                        const isSent = msg.sender_id == currentUserId;
                        const row = document.createElement('div');
                        row.className = `message-row ${isSent ? 'sent' : 'received'}`;

                        row.innerHTML = `
                            <div class="message-time">${new Date(msg.timestamp).toLocaleTimeString()}</div>
                            <div class="message-bubble">${msg.message}</div>
                        `;
                        display.appendChild(row);
                    });

                    // Scroll to bottom
                    display.scrollTop = display.scrollHeight;
                }
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        }

        // Send message
        document.getElementById('messageForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (!message) return;
            
            const formData = new FormData();
            formData.append('receiver_id', currentConversationId);
            formData.append('message', message);
            
            try {
                const response = await fetch('api_messages.php?action=send', {
                    method: 'POST',
                    body: formData
                });
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Invalid JSON response (send message):', text);
                    return;
                }

                if (data.success) {
                    input.value = '';
                    loadMessages();
                }
            } catch (error) {
                console.error('Error sending message:', error);
            }
        });

        // Auto-refresh messages every 2 seconds
        // Poll every 3 seconds; avoid too-frequent refresh which can cause flicker
        setInterval(() => {
            if (currentConversationId) {
                loadMessages();
            }
            loadConversations();
        }, 3000);

        // Initial load
        loadConversations();
    </script>
</body>
</html>
