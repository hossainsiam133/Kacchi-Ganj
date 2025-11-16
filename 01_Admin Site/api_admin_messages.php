<?php
// Start buffering immediately so any output from includes (warnings, whitespace) is captured.
ob_start();
ini_set('display_errors', 0);
include 'connection.php';
session_start();

function send_json_admin($arr) {
    $buf = ob_get_clean();
    if ($buf !== false && strlen(trim($buf)) > 0) {
        $arr['__debug_html'] = $buf;
    }
    header('Content-Type: application/json');
    echo json_encode($arr);
    exit;
}

// Register shutdown function to catch fatal errors and return JSON instead of empty response
register_shutdown_function(function() {
    $err = error_get_last();
    if ($err !== NULL) {
        $buf = '';
        if (ob_get_level() > 0) {
            $buf = ob_get_clean();
        }
        header('Content-Type: application/json');
        $out = ['success' => false, 'message' => 'Fatal error in Admin API', 'error' => $err];
        if ($buf !== false && strlen(trim($buf)) > 0) $out['__debug_html'] = $buf;
        echo json_encode($out);
        exit;
    }
});

$action = isset($_GET['action']) ? $_GET['action'] : '';
$admin_id = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : 0;

if (!$admin_id) {
    send_json_admin(['success' => false, 'message' => 'Unauthorized']);
}

// Send a message from admin
if ($action === 'send' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $message = isset($_POST['message']) ? mysqli_real_escape_string($conn, $_POST['message']) : '';
    
    if (!$user_id || !$message) {
        send_json_admin(['success' => false, 'message' => 'Invalid data']);
    }
    // Ensure recipient user exists in users table before inserting (messages FK -> users.id)
    $check_user = mysqli_query($conn, "SELECT id FROM `users` WHERE id='$user_id' LIMIT 1");
    if (!$check_user || mysqli_num_rows($check_user) === 0) {
        send_json_admin(['success' => false, 'message' => 'Recipient user not found']);
    }
    
    $query = "INSERT INTO `messages` (`sender_id`, `receiver_id`, `message`, `timestamp`, `is_read`) 
              VALUES ('$admin_id', '$user_id', '$message', NOW(), 0)";
    
    if (mysqli_query($conn, $query)) {
        send_json_admin(['success' => true, 'message' => 'Message sent']);
    } else {
        send_json_admin(['success' => false, 'message' => 'Failed to send message: ' . mysqli_error($conn)]);
    }
}

// Return list of admin users (from users table where user_type='admin')
if ($action === 'get_admins') {
    $admins = [];
    $res = mysqli_query($conn, "SELECT id, name, email FROM `users` WHERE user_type='admin'");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $admins[] = $row;
        }
    }
    send_json_admin(['success' => true, 'admins' => $admins]);
}

// Get messages for a conversation
if ($action === 'get_conversation') {
    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    
    if (!$user_id) {
        send_json_admin(['success' => false, 'message' => 'Invalid user']);
    }
    
    $query = "SELECT * FROM `messages` 
              WHERE (sender_id='$user_id' AND receiver_id='$admin_id') 
                 OR (sender_id='$admin_id' AND receiver_id='$user_id')
              ORDER BY timestamp ASC";
    
    $result = mysqli_query($conn, $query);
    $messages = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
    
    // Mark messages as read
    $update_query = "UPDATE `messages` SET `is_read`=1 
                    WHERE receiver_id='$admin_id' AND sender_id='$user_id'";
    mysqli_query($conn, $update_query);
    
    send_json_admin(['success' => true, 'messages' => $messages]);
}

// Get all conversations for admin
if ($action === 'get_all_conversations') {
    $query = "SELECT DISTINCT 
              CASE 
                WHEN sender_id='$admin_id' THEN receiver_id 
                ELSE sender_id 
              END as user_id,
              MAX(timestamp) as last_message_time,
              (SELECT message FROM messages m2 
               WHERE (m2.sender_id=messages.sender_id AND m2.receiver_id=messages.receiver_id 
                      OR m2.sender_id=messages.receiver_id AND m2.receiver_id=messages.sender_id)
               ORDER BY m2.timestamp DESC LIMIT 1) as last_message
              FROM `messages`
              WHERE sender_id='$admin_id' OR receiver_id='$admin_id'
              GROUP BY user_id
              ORDER BY last_message_time DESC";
    
    $result = mysqli_query($conn, $query);
    $conversations = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $user_query = mysqli_query($conn, "SELECT name, email FROM `users` WHERE id='$user_id'");
        $user_info = mysqli_fetch_assoc($user_query);
        
        $row['user_name'] = $user_info ? $user_info['name'] : 'Unknown';
        $row['user_email'] = $user_info ? $user_info['email'] : '';
        $conversations[] = $row;
    }
    
    send_json_admin(['success' => true, 'conversations' => $conversations]);
}

send_json_admin(['success' => false, 'message' => 'Invalid action']);
?>
