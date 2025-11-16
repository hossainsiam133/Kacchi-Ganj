<?php
// Start buffering immediately so any output from includes (warnings, whitespace) is captured.
ob_start();
ini_set('display_errors', 0);
include 'connection.php';
session_start();

// Prevent accidental HTML/notice output breaking JSON responses.
// Buffer output and attach any unexpected output into the JSON under __debug_html for debugging.
ob_start();
ini_set('display_errors', 0);

function send_json($arr) {
    // Capture any buffered output (warnings, notices, HTML)
    $buf = ob_get_clean();
    if ($buf !== false && strlen(trim($buf)) > 0) {
        $arr['__debug_html'] = $buf;
    }
    header('Content-Type: application/json');
    echo json_encode($arr);
    exit;
}

// Register shutdown function to catch fatal errors that would otherwise produce empty responses
register_shutdown_function(function() {
    $err = error_get_last();
    if ($err !== NULL) {
        // try to return a JSON error containing the PHP error and any buffered output
        $buf = '';
        if (ob_get_level() > 0) {
            $buf = ob_get_clean();
        }
        header('Content-Type: application/json');
        $out = ['success' => false, 'message' => 'Fatal error in API', 'error' => $err];
        if ($buf !== false && strlen(trim($buf)) > 0) $out['__debug_html'] = $buf;
        echo json_encode($out);
        exit;
    }
});

$action = isset($_GET['action']) ? $_GET['action'] : '';
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

if (!$user_id) {
    send_json(['success' => false, 'message' => 'Unauthorized']);
}

// Send a message
if ($action === 'send' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_id = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : 0;
    $message = isset($_POST['message']) ? mysqli_real_escape_string($conn, $_POST['message']) : '';
    
    if (!$receiver_id || !$message) {
        send_json(['success' => false, 'message' => 'Invalid data']);
    }
    // Ensure receiver exists in users table (messages FK references users.id)
    $check_receiver = mysqli_query($conn, "SELECT id FROM `users` WHERE id='$receiver_id' LIMIT 1");
    if (!$check_receiver || mysqli_num_rows($check_receiver) === 0) {
        send_json(['success' => false, 'message' => 'Recipient not found']);
    }
    
    $query = "INSERT INTO `messages` (`sender_id`, `receiver_id`, `message`, `timestamp`, `is_read`) 
              VALUES ('$user_id', '$receiver_id', '$message', NOW(), 0)";
    
    if (mysqli_query($conn, $query)) {
        send_json(['success' => true, 'message' => 'Message sent']);
    } else {
        send_json(['success' => false, 'message' => 'Failed to send message: ' . mysqli_error($conn)]);
    }
}

// Get messages for a conversation
if ($action === 'get_conversation') {
    $other_user_id = isset($_GET['other_user_id']) ? intval($_GET['other_user_id']) : 0;
    
    if (!$other_user_id) {
        send_json(['success' => false, 'message' => 'Invalid user']);
    }
    
    $query = "SELECT * FROM `messages` 
              WHERE (sender_id='$user_id' AND receiver_id='$other_user_id') 
                 OR (sender_id='$other_user_id' AND receiver_id='$user_id')
              ORDER BY timestamp ASC";
    
    $result = mysqli_query($conn, $query);
    $messages = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
    
    // Mark messages as read
    $update_query = "UPDATE `messages` SET `is_read`=1 
                    WHERE receiver_id='$user_id' AND sender_id='$other_user_id'";
    mysqli_query($conn, $update_query);
    
    send_json(['success' => true, 'messages' => $messages]);
}

// Get conversation list
if ($action === 'get_conversations') {
    $query = "SELECT DISTINCT 
              CASE 
                WHEN sender_id='$user_id' THEN receiver_id 
                ELSE sender_id 
              END as other_user_id,
              MAX(timestamp) as last_message_time,
              (SELECT message FROM messages m2 
               WHERE (m2.sender_id=messages.sender_id AND m2.receiver_id=messages.receiver_id 
                      OR m2.sender_id=messages.receiver_id AND m2.receiver_id=messages.sender_id)
               ORDER BY m2.timestamp DESC LIMIT 1) as last_message,
              (SELECT COUNT(*) FROM messages m3 
               WHERE m3.receiver_id='$user_id' AND m3.is_read=0 
               AND (m3.sender_id=other_user_id OR m3.receiver_id=other_user_id)) as unread_count
              FROM `messages`
              WHERE sender_id='$user_id' OR receiver_id='$user_id'
              GROUP BY other_user_id
              ORDER BY last_message_time DESC";
    
    $result = mysqli_query($conn, $query);
    $conversations = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $other_id = $row['other_user_id'];
        $user_query = mysqli_query($conn, "SELECT name, email FROM `users` WHERE id='$other_id'");
        $user_info = mysqli_fetch_assoc($user_query);
        
        $row['user_name'] = $user_info ? $user_info['name'] : 'Unknown';
        $row['user_email'] = $user_info ? $user_info['email'] : '';
        $conversations[] = $row;
    }
    
    send_json(['success' => true, 'conversations' => $conversations]);
}

// Get unread count
if ($action === 'get_unread_count') {
    $query = "SELECT COUNT(*) as count FROM `messages` WHERE receiver_id='$user_id' AND is_read=0";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    send_json(['success' => true, 'unread_count' => $row['count']]);
}

send_json(['success' => false, 'message' => 'Invalid action']);
?>
