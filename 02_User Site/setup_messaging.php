<?php
// Database setup script for messaging system
include 'connection.php';

// include global config for admin id
if (file_exists(__DIR__ . '/../config.php')) {
    include __DIR__ . '/../config.php';
}

echo "<h2>Messaging System Database Setup</h2>";
echo "<hr>";

// Check if messages table exists
$check_table = "SHOW TABLES LIKE 'messages'";
$result = mysqli_query($conn, $check_table);

if(mysqli_num_rows($result) > 0) {
    echo "<div style='color: green; font-weight: bold;'>✓ Messages table already exists</div>";
} else {
    echo "<div style='color: orange; font-weight: bold;'>✗ Messages table not found. Creating...</div><br>";
    
    $create_table = "CREATE TABLE IF NOT EXISTS `messages` (
        `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `sender_id` int(11) NOT NULL,
        `receiver_id` int(11) NOT NULL,
        `message` text NOT NULL,
        `timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
        `is_read` tinyint(1) DEFAULT 0,
        FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`receiver_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
        INDEX `idx_conversation` (`sender_id`, `receiver_id`),
        INDEX `idx_receiver` (`receiver_id`, `is_read`),
        INDEX `idx_timestamp` (`timestamp`)
    )";
    
    if(mysqli_query($conn, $create_table)) {
        echo "<div style='color: green; font-weight: bold;'>✓ Messages table created successfully!</div>";
    } else {
        echo "<div style='color: red; font-weight: bold;'>✗ Error creating messages table:</div>";
        echo "<pre>" . mysqli_error($conn) . "</pre>";
        exit;
    }
}

echo "<hr>";
echo "<h3>Checking Users Table</h3>";

// Check if users table exists
$check_users = "SHOW TABLES LIKE 'users'";
$users_result = mysqli_query($conn, $check_users);

if(mysqli_num_rows($users_result) > 0) {
    echo "<div style='color: green; font-weight: bold;'>✓ Users table exists</div>";
    
    // Get sample users
    $users_query = mysqli_query($conn, "SELECT id, name, email FROM `users` LIMIT 5");
    echo "<h4>Sample Users (first 5):</h4>";
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";
    while($user = mysqli_fetch_assoc($users_query)) {
        echo "<tr><td>" . $user['id'] . "</td><td>" . $user['name'] . "</td><td>" . $user['email'] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<div style='color: red; font-weight: bold;'>✗ Users table not found!</div>";
}

echo "<hr>";
echo "<h3>Configuration</h3>";
echo "<p><strong>Admin User ID (for messaging):</strong> <code>" . (defined('DEFAULT_ADMIN_ID') ? DEFAULT_ADMIN_ID : '1') . "</code></p>";
echo "<p>To change the admin user ID, edit messages.php and admin_messages.php</p>";

echo "<hr>";
echo "<div style='background: #f0f0f0; padding: 15px; border-radius: 5px;'>";
echo "<h3>✓ Setup Complete!</h3>";
echo "<p>You can now:</p>";
echo "<ul>";
echo "<li>Go to <code>/02_User Site/messages.php</code> to start messaging</li>";
echo "<li>Go to <code>/01_Admin Site/admin_messages.php</code> to manage conversations</li>";
echo "</ul>";
echo "</div>";

mysqli_close($conn);
?>
