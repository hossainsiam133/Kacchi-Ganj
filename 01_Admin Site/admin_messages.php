<?php
    // include DB connection (file is in same folder)
    include 'connection.php';
    session_start();
    $admin_id = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : 0;

    if (!$admin_id) {
       header('location: login.php');
       exit;
    }

    // Get admin info safely: check if `admin` table exists first to avoid fatal errors
    $admin = ['id' => $admin_id, 'name' => 'Admin', 'email' => ''];
    $check_admin_table = "SHOW TABLES LIKE 'admin'";
    $tbl_res = @mysqli_query($conn, $check_admin_table);
    if ($tbl_res && mysqli_num_rows($tbl_res) > 0) {
        $admin_query = @mysqli_query($conn, "SELECT * FROM `admin` WHERE id='$admin_id' LIMIT 1");
        if ($admin_query && mysqli_num_rows($admin_query) > 0) {
            $admin = mysqli_fetch_assoc($admin_query);
        }
    } else {
        // fallback: try `users` table for name/email (many projects keep admin in users)
        $user_q = @mysqli_query($conn, "SELECT name, email FROM `users` WHERE id='$admin_id' LIMIT 1");
        if ($user_q && mysqli_num_rows($user_q) > 0) {
            $u = mysqli_fetch_assoc($user_q);
            $admin['name'] = $u['name'];
            $admin['email'] = $u['email'];
        } else {
            // final fallback: use session values if available
            if (isset($_SESSION['admin_name'])) $admin['name'] = $_SESSION['admin_name'];
            if (isset($_SESSION['admin_email'])) $admin['email'] = $_SESSION['admin_email'];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
    <title>Admin Messages - Kacchi Ganj</title>
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

        .admin-messaging-container {
            display: flex;
            height: 100vh;
        }

        .admin-sidebar-msgs {
            width: 320px;
            background: white;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .admin-sidebar-header {
            padding: 20px;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            border-bottom: 1px solid #1e40af;
        }

        .admin-sidebar-header h2 {
            margin: 0;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .admin-conversations-list {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .admin-conversation-item {
            padding: 12px;
            margin: 5px 0;
            background: #f9fafb;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .admin-conversation-item:hover {
            background: #e8f0ff;
            border-color: #2563eb;
        }

        .admin-conversation-item.active {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            border-color: #1e40af;
        }

        .admin-conversation-item-name {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .admin-conversation-item-preview {
            font-size: 0.85rem;
            opacity: 0.7;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: white;
        }

        .admin-chat-header {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            background: linear-gradient(135deg, #f8fafc 0%, #f0f2f5 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-chat-header-info h3 {
            margin: 0 0 4px 0;
            color: #0f172a;
        }

        .admin-chat-header-info p {
            margin: 0;
            font-size: 0.85rem;
            color: #6b7280;
        }

        .admin-messages-display {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #fafbfc;
        }

        .admin-message-row {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
        }

        .admin-message-row.sent {
            justify-content: flex-end;
        }

        .admin-message-bubble {
            max-width: 60%;
            padding: 12px 16px;
            border-radius: 12px;
            word-wrap: break-word;
            line-height: 1.4;
            font-size: 0.95rem;
        }

        .admin-message-row.received .admin-message-bubble {
            background: white;
            color: #0f172a;
            border: 1px solid #e0e0e0;
        }

        .admin-message-row.sent .admin-message-bubble {
            background: #2563eb;
            color: white;
        }

        .admin-message-time {
            font-size: 0.75rem;
            color: #9ca3af;
            padding: 0 4px;
            align-self: flex-end;
        }

        .admin-chat-input-area {
            padding: 20px;
            border-top: 1px solid #e0e0e0;
            background: white;
        }

        .admin-input-form {
            display: flex;
            gap: 10px;
        }

        .admin-chat-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            resize: none;
            max-height: 80px;
        }

        .admin-chat-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .admin-send-btn {
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

        .admin-send-btn:hover {
            transform: translateY(-2px);
        }

        .admin-empty-chat {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #9ca3af;
            font-size: 1.1rem;
            flex-direction: column;
            gap: 10px;
        }

        .admin-empty-chat i {
            font-size: 3rem;
            opacity: 0.3;
        }

        /* Scrollbar styling */
        .admin-conversations-list::-webkit-scrollbar,
        .admin-messages-display::-webkit-scrollbar {
            width: 6px;
        }

        .admin-conversations-list::-webkit-scrollbar-thumb,
        .admin-messages-display::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="admin-messaging-container">
        <!-- Conversations Sidebar -->
        <div class="admin-sidebar-msgs">
            <div class="admin-sidebar-header">
                <h2><i class="ri-chat-2-line"></i> User Messages</h2>
            </div>
            <div class="admin-conversations-list" id="adminConversationsList">
                <div style="padding: 20px; text-align: center; color: #9ca3af;">Loading conversations...</div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="admin-chat-area">
            <div class="admin-chat-header">
                <div class="admin-chat-header-info" id="adminChatHeaderInfo">
                    <h3>Select a user to start messaging</h3>
                    <p>All conversations</p>
                </div>
            </div>

            <div class="admin-messages-display" id="adminMessagesDisplay">
                <div class="admin-empty-chat">
                    <i class="ri-chat-smile-2-line"></i>
                    <p>Select a user to view conversation</p>
                </div>
            </div>

            <div class="admin-chat-input-area" id="adminChatInputArea" style="display: none;">
                <form class="admin-input-form" id="adminMessageForm">
                    <textarea class="admin-chat-input" id="adminMessageInput" placeholder="Type your response..." rows="1"></textarea>
                    <button type="submit" class="admin-send-btn">
                        <i class="ri-send-plane-2-fill"></i> Send
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    let currentAdminConversationUserId = null;
    let adminId = <?php echo $admin_id; ?>;
    // snapshots to avoid unnecessary re-renders and reduce flicker
    let prevAdminConversationsJSON = '';
    let prevAdminMessagesJSON = {}; // keyed by user id

        // Load all user conversations for admin
        async function loadAdminConversations() {
            try {
                // Get all messages grouped by users
                const response = await fetch('api_admin_messages.php?action=get_all_conversations', {
                    method: 'GET',
                });
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Invalid JSON response (load admin conversations):', text);
                    return;
                }

                if (data.success) {
                    // avoid re-rendering if conversations unchanged
                    const convJSON = JSON.stringify(data.conversations || []);
                    if (convJSON === prevAdminConversationsJSON) return;
                    prevAdminConversationsJSON = convJSON;

                    const list = document.getElementById('adminConversationsList');
                    list.innerHTML = '';
                    
                    if (data.conversations.length === 0) {
                        list.innerHTML = '<div style="padding: 20px; text-align: center; color: #9ca3af;">No messages yet</div>';
                        return;
                    }
                    
                    data.conversations.forEach(conv => {
                        const item = document.createElement('div');
                        item.className = 'admin-conversation-item';
                        item.onclick = () => selectAdminConversation(conv.user_id, conv.user_name);
                        
                        item.innerHTML = `
                            <div class="admin-conversation-item-name">${conv.user_name}</div>
                            <div class="admin-conversation-item-preview">${conv.user_email}</div>
                            <div class="admin-conversation-item-preview" style="font-size: 0.8rem; margin-top: 4px;">${conv.last_message || 'No messages yet'}</div>
                        `;
                        list.appendChild(item);
                    });
                }
            } catch (error) {
                console.error('Error loading conversations:', error);
            }
        }

        // Select a conversation
        async function selectAdminConversation(userId, userName) {
            currentAdminConversationUserId = userId;
            
            // Update header
            document.getElementById('adminChatHeaderInfo').innerHTML = `
                <h3>${userName}</h3>
                <p>Conversation with ${userName}</p>
            `;
            
            // Show input area
            document.getElementById('adminChatInputArea').style.display = 'block';
            
            // Load messages
            loadAdminMessages();
            
            // Mark as active
            document.querySelectorAll('.admin-conversation-item').forEach(item => {
                item.classList.remove('active');
            });
            event.target.closest('.admin-conversation-item').classList.add('active');
        }

        // Load messages for admin
        async function loadAdminMessages() {
            try {
                const response = await fetch(`api_admin_messages.php?action=get_conversation&user_id=${currentAdminConversationUserId}`);
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Invalid JSON response (load admin messages):', text);
                    return;
                }

                if (data.success) {
                    // skip refresh while admin typing
                    const adminInput = document.getElementById('adminMessageInput');
                    if (adminInput && adminInput.value.trim() !== '') return;

                    const msgsJSON = JSON.stringify(data.messages || []);
                    if (prevAdminMessagesJSON[currentAdminConversationUserId] === msgsJSON) return;
                    prevAdminMessagesJSON[currentAdminConversationUserId] = msgsJSON;

                    const display = document.getElementById('adminMessagesDisplay');
                    display.innerHTML = '';
                    
                    data.messages.forEach(msg => {
                        const isFromAdmin = msg.sender_id == adminId;
                        const row = document.createElement('div');
                        row.className = `admin-message-row ${isFromAdmin ? 'sent' : 'received'}`;
                        
                        row.innerHTML = `
                            <div class="admin-message-time">${new Date(msg.timestamp).toLocaleTimeString()}</div>
                            <div class="admin-message-bubble">${msg.message}</div>
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

        // Send message from admin
        document.getElementById('adminMessageForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const input = document.getElementById('adminMessageInput');
            const message = input.value.trim();
            
            if (!message) return;
            
            const formData = new FormData();
            formData.append('user_id', currentAdminConversationUserId);
            formData.append('message', message);
            
            try {
                const response = await fetch('api_admin_messages.php?action=send', {
                    method: 'POST',
                    body: formData
                });
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Invalid JSON response (admin send message):', text);
                    return;
                }

                if (data.success) {
                    input.value = '';
                    loadAdminMessages();
                }
            } catch (error) {
                console.error('Error sending message:', error);
            }
        });

        // Auto-refresh
        // Auto-refresh (every 3s) with smart diffs to reduce UI flicker
        setInterval(() => {
            if (currentAdminConversationUserId) {
                loadAdminMessages();
            }
            loadAdminConversations();
        }, 3000);

        // Initial load
        loadAdminConversations();
    </script>
</body>
</html>
