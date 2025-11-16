<?php
// Debugging helper for messaging system
session_start();
include 'connection.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Messaging System - Debug Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 20px auto;
            background: #f5f5f5;
            padding: 20px;
        }
        .box {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .success {
            color: #27ae60;
            font-weight: bold;
        }
        .error {
            color: #e74c3c;
            font-weight: bold;
        }
        .warning {
            color: #f39c12;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table tr {
            border-bottom: 1px solid #ddd;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
        }
        table th {
            background: #2563eb;
            color: white;
        }
        pre {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>🔧 Messaging System - Debug Info</h1>
    <hr>

    <!-- Session Info -->
    <div class="box">
        <h2>📋 Session Information</h2>
        <?php
        if(isset($_SESSION['user_id'])) {
            echo "<p class='success'>✓ User logged in (ID: " . $_SESSION['user_id'] . ")</p>";
        } else {
            echo "<p class='error'>✗ User NOT logged in</p>";
        }
        
        if(isset($_SESSION['admin_id'])) {
            echo "<p class='success'>✓ Admin logged in (ID: " . $_SESSION['admin_id'] . ")</p>";
        } else {
            echo "<p class='warning'>⚠ Admin not logged in (this is normal for user side)</p>";
        }
        ?>
    </div>

    <!-- Database Connection -->
    <div class="box">
        <h2>🗄️ Database Connection</h2>
        <?php
        if($conn) {
            echo "<p class='success'>✓ Database connection successful</p>";
        } else {
            echo "<p class='error'>✗ Database connection failed: " . mysqli_connect_error() . "</p>";
        }
        ?>
    </div>

    <!-- Tables Check -->
    <div class="box">
        <h2>📊 Database Tables</h2>
        <?php
        // Check messages table
        $check_messages = "SHOW TABLES LIKE 'messages'";
        $result = mysqli_query($conn, $check_messages);
        
        if(mysqli_num_rows($result) > 0) {
            echo "<p class='success'>✓ Messages table exists</p>";
            
            // Get table structure
            $desc = mysqli_query($conn, "DESCRIBE messages");
            echo "<h4>Table Structure:</h4>";
            echo "<table>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
            while($row = mysqli_fetch_assoc($desc)) {
                echo "<tr>";
                echo "<td>" . $row['Field'] . "</td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['Null'] . "</td>";
                echo "<td>" . $row['Key'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Count messages
            $count = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM messages");
            $count_row = mysqli_fetch_assoc($count);
            echo "<p>Total messages in database: <strong>" . $count_row['cnt'] . "</strong></p>";
            
        } else {
            echo "<p class='error'>✗ Messages table NOT found</p>";
            echo "<p>Click the button below to create it:</p>";
        }
        
        // Check users table
        echo "<hr>";
        $check_users = "SHOW TABLES LIKE 'users'";
        $users_result = mysqli_query($conn, $check_users);
        
        if(mysqli_num_rows($users_result) > 0) {
            echo "<p class='success'>✓ Users table exists</p>";
            
            // Count users
            $user_count = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users");
            $user_row = mysqli_fetch_assoc($user_count);
            echo "<p>Total users: <strong>" . $user_row['cnt'] . "</strong></p>";
            
        } else {
            echo "<p class='error'>✗ Users table NOT found</p>";
        }
        ?>
    </div>

    <!-- API Test -->
    <div class="box">
        <h2>🧪 API Test</h2>
        <?php
        if(isset($_SESSION['user_id'])) {
            echo "<p>Testing API endpoints for logged-in user (ID: " . $_SESSION['user_id'] . ")</p>";
            echo "<p>API Status: <span class='success'>✓ Ready to test</span></p>";
        } else {
            echo "<p class='error'>✗ Cannot test API - user not logged in</p>";
        }
        ?>
    </div>

    <!-- Setup Button -->
    <div class="box">
        <h2>⚙️ Setup & Configuration</h2>
        <p>If messages table doesn't exist, click below to create it:</p>
        <button onclick="window.location.href='setup_messaging.php'" style="
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        ">Run Setup Script</button>
    </div>

    <!-- Instructions -->
    <div class="box">
        <h2>📖 Next Steps</h2>
        <ol>
            <li>If messages table is missing, click "Run Setup Script" above</li>
            <li>Make sure you're logged in as a user</li>
            <li>Go to <code>/02_User Site/messages.php</code></li>
            <li>Click "+ New Message" button</li>
            <li>Select admin and type your message</li>
            <li>Click "Start Chat"</li>
        </ol>
    </div>

    <hr>
    <p style="text-align: center; color: #999;">Debug Info Generated: <?php echo date('Y-m-d H:i:s'); ?></p>
</body>
</html>
