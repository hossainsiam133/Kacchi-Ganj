<?php
session_start();
include 'connection.php';
// include global config to get DEFAULT_ADMIN_ID
if (file_exists(__DIR__ . '/../config.php')) {
    include __DIR__ . '/../config.php';
}

// Simple API test page
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

if (!$user_id) {
    die('Please log in first');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>API Test - Messaging</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .box {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .success { color: #27ae60; font-weight: bold; }
        .error { color: #e74c3c; font-weight: bold; }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        button {
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        button:hover {
            background: #1e40af;
        }
        textarea {
            width: 100%;
            padding: 10px;
            font-family: monospace;
            border: 1px solid #ddd;
            border-radius: 5px;
            min-height: 100px;
        }
    </style>
</head>
<body>
    <h1>📡 API Test - Messaging System</h1>
    <p>Logged in as User ID: <strong><?php echo $user_id; ?></strong></p>
    <hr>

    <div class="box">
        <h2>Test 1: Send Test Message</h2>
    <p>This will send a test message to Admin (ID: <?php echo (defined('DEFAULT_ADMIN_ID') ? DEFAULT_ADMIN_ID : 1); ?>)</p>
        <button onclick="testSendMessage()">Send Test Message</button>
        <textarea id="test1Result" placeholder="Result will appear here..."></textarea>
    </div>

    <div class="box">
        <h2>Test 2: Get Conversations</h2>
        <p>This will fetch all your conversations</p>
        <button onclick="testGetConversations()">Get Conversations</button>
        <textarea id="test2Result" placeholder="Result will appear here..."></textarea>
    </div>

    <div class="box">
        <h2>Test 3: Get Unread Count</h2>
        <p>This will show unread message count</p>
        <button onclick="testGetUnreadCount()">Get Unread Count</button>
        <textarea id="test3Result" placeholder="Result will appear here..."></textarea>
    </div>

    <div class="box">
        <h2>Test 4: Get Specific Conversation</h2>
    <p>This will fetch messages with Admin (ID: <?php echo (defined('DEFAULT_ADMIN_ID') ? DEFAULT_ADMIN_ID : 1); ?>)</p>
        <button onclick="testGetConversation()">Get Conversation with Admin</button>
        <textarea id="test4Result" placeholder="Result will appear here..."></textarea>
    </div>

    <script>
        // expose PHP config to JS
        const DEFAULT_ADMIN_ID = <?php echo (defined('DEFAULT_ADMIN_ID') ? DEFAULT_ADMIN_ID : 1); ?>;
        async function testSendMessage() {
            try {
                const formData = new FormData();
                // default admin id from config
                formData.append('receiver_id', String(DEFAULT_ADMIN_ID));
                formData.append('message', 'Test message - ' + new Date().toLocaleString());

                const response = await fetch('api_messages.php?action=send', {
                    method: 'POST',
                    body: formData
                });

                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    document.getElementById('test1Result').value = 'Invalid JSON response:\n' + text;
                    alert('✗ Invalid JSON response (see result box)');
                    return;
                }
                document.getElementById('test1Result').value = JSON.stringify(data, null, 2);

                if (data.success) {
                    alert('✓ Message sent successfully!');
                }
            } catch (error) {
                document.getElementById('test1Result').value = 'Error: ' + error.message;
                alert('✗ Error: ' + error.message);
            }
        }

        async function testGetConversations() {
            try {
                const response = await fetch('api_messages.php?action=get_conversations');
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    document.getElementById('test2Result').value = 'Invalid JSON response:\n' + text;
                    return;
                }
                document.getElementById('test2Result').value = JSON.stringify(data, null, 2);
            } catch (error) {
                document.getElementById('test2Result').value = 'Error: ' + error.message;
            }
        }

        async function testGetUnreadCount() {
            try {
                const response = await fetch('api_messages.php?action=get_unread_count');
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    document.getElementById('test3Result').value = 'Invalid JSON response:\n' + text;
                    return;
                }
                document.getElementById('test3Result').value = JSON.stringify(data, null, 2);
            } catch (error) {
                document.getElementById('test3Result').value = 'Error: ' + error.message;
            }
        }

        async function testGetConversation() {
            try {
                const response = await fetch(`api_messages.php?action=get_conversation&other_user_id=${DEFAULT_ADMIN_ID}`);
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    document.getElementById('test4Result').value = 'Invalid JSON response:\n' + text;
                    return;
                }
                document.getElementById('test4Result').value = JSON.stringify(data, null, 2);
            } catch (error) {
                document.getElementById('test4Result').value = 'Error: ' + error.message;
            }
        }
    </script>
</body>
</html>
