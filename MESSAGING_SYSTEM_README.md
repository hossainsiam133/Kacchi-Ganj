# Live Messaging System - Setup & Usage Guide

## Overview
This is a real-time messaging system that allows users to send messages to admins and admins to manage all conversations. The system uses AJAX for live updates without page refresh.

## 🗄️ Database Setup

First, run this SQL query to create the messages table:

```sql
CREATE TABLE IF NOT EXISTS `messages` (
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
);
```

**Important:** Make sure your `users` table exists with at least these columns:
- `id` (PRIMARY KEY)
- `name` (VARCHAR)
- `email` (VARCHAR)
- `password` (VARCHAR)

## 📁 Files Created

### User Side
- **`/02_User Site/messages.php`** - User messaging interface
- **`/02_User Site/api_messages.php`** - API for user messaging operations

### Admin Side
- **`/01_Admin Site/admin_messages.php`** - Admin messaging panel
- **`/01_Admin Site/api_admin_messages.php`** - API for admin messaging operations

### Database
- **`database_schema_messages.sql`** - SQL schema file

---

## 👥 User Side Usage

### Step 1: Start a New Conversation
1. Go to `/02_User Site/messages.php`
2. Click the **"+ New Message"** button
3. Select the admin from the dropdown (currently hardcoded to "Support Admin")
4. Type your initial message
5. Click **"Start Chat"**

### Step 2: Send Messages
- Your message appears on the right side (blue bubble)
- Type new messages in the input box
- Click **Send** or press Enter
- Messages update automatically every 2 seconds

### Step 3: Receive Messages
- Admin replies appear on the left side (light blue bubble)
- Unread message count shows as a red badge
- Auto-loads new messages in real-time

---

## 🛠️ Admin Side Usage

### Access Admin Messaging
- Go to `/01_Admin Site/admin_messages.php`
- Must be logged in as an admin

### View Conversations
- Left sidebar shows all users who messaged
- Each entry shows:
  - User name
  - User email
  - Last message preview
  - Latest timestamp

### Reply to Users
1. Click on any user in the sidebar
2. Chat history appears in the center
3. Type your reply in the input box
4. Click **Send**
5. Message updates for both user and admin

---

## 🔧 Configuration

### Setting the Admin User ID

The system currently uses `user_id = 1` as the admin. To change this:

**In `/02_User Site/messages.php`:**
```javascript
// Line ~350 (in loadAdminOptions function)
select.innerHTML += '<option value="1">Support Admin</option>';
// Change "1" to your admin's user ID
```

**In `/02_User Site/api_messages.php`:**
All messages to the admin go to the receiver specified by users.

**In `/01_Admin Site/admin_messages.php`:**
Admin session uses `$_SESSION['admin_id']` (set during login)

### Getting Admin ID from Sessions

The admin side uses `$_SESSION['admin_id']` which should be set during admin login. Make sure your admin login script sets this:

```php
$_SESSION['admin_id'] = $admin_id; // Set this in your login page
```

---

## 📡 API Endpoints

### User API (`api_messages.php`)

#### Send Message
```
POST api_messages.php?action=send
Parameters:
  - receiver_id: Admin user ID (int)
  - message: Message text (string)
Response: { success: true/false, message: string }
```

#### Get Conversation
```
GET api_messages.php?action=get_conversation&other_user_id={userId}
Response: { success: true, messages: [...] }
```

#### Get All Conversations
```
GET api_messages.php?action=get_conversations
Response: { success: true, conversations: [...] }
```

#### Get Unread Count
```
GET api_messages.php?action=get_unread_count
Response: { success: true, unread_count: number }
```

### Admin API (`api_admin_messages.php`)

#### Send Message (Admin)
```
POST api_admin_messages.php?action=send
Parameters:
  - user_id: Target user ID (int)
  - message: Message text (string)
Response: { success: true/false, message: string }
```

#### Get Conversation
```
GET api_admin_messages.php?action=get_conversation&user_id={userId}
Response: { success: true, messages: [...] }
```

#### Get All Conversations (Admin)
```
GET api_admin_messages.php?action=get_all_conversations
Response: { success: true, conversations: [...] }
```

---

## 🎨 UI Features

### User Interface
- **Modern Chat Design** - Blue gradient header, clean white cards
- **Real-time Updates** - Auto-refresh every 2 seconds
- **Responsive** - Works on mobile, tablet, and desktop
- **Message Timestamps** - Each message shows send time
- **Unread Badges** - Red badge shows unread message count
- **Color-coded Messages** - Blue bubbles = sent, light blue = received

### Admin Interface
- **Conversation List** - All users with message preview
- **Quick Selection** - Click any user to view conversation
- **Chat History** - Full message thread displayed
- **Real-time Reply** - Send instant responses
- **User Info** - Name and email displayed for each conversation

---

## 🚀 How It Works

1. **User sends message** → `api_messages.php` saves to database
2. **Admin receives** → Message appears in admin panel (auto-refreshed)
3. **Admin replies** → `api_admin_messages.php` saves reply
4. **User receives** → Message updates via AJAX (2-second refresh)
5. **Conversation history** → Always available in both interfaces

---

## ⚙️ Customization

### Change Refresh Rate
In both `messages.php` and `admin_messages.php`, find:
```javascript
setInterval(() => {
    if (currentConversationId) {
        loadMessages();
    }
    loadConversations();
}, 2000); // Change 2000 to milliseconds you want
```

### Customize Colors
In the CSS sections, modify:
```css
--accent: #2563eb;  /* Main blue */
--accent-600: #1e40af;  /* Darker blue */
```

### Change Admin Display Name
In `messages.php`, modify:
```javascript
select.innerHTML += '<option value="1">Support Admin</option>';
// Change "Support Admin" to your name
```

---

## 🔒 Security Notes

1. **SQL Injection Protection** - All inputs use `mysqli_real_escape_string()`
2. **Session Validation** - User/Admin must be logged in
3. **Message Read Status** - Tracks `is_read` flag
4. **AJAX Headers** - JSON response type prevents caching
5. **HTML Escaping** - All user inputs are escaped on display

**Recommendations:**
- Use prepared statements instead of string escaping
- Implement rate limiting for message sending
- Add message encryption for sensitive data
- Validate admin access at the start of admin pages

---

## 🐛 Troubleshooting

### Issue: "No conversations loading"
- **Check:** Is the messages table created?
- **Check:** Are there any messages in the database?
- **Check:** Is user logged in? Check `$_SESSION['user_id']`

### Issue: "Can't start new conversation"
- **Check:** Is receiver_id (admin ID) correct?
- **Check:** Does the admin user exist in the users table?
- **Check:** Check browser console for JavaScript errors

### Issue: "Admin not receiving messages"
- **Check:** Is admin logged in? Check `$_SESSION['admin_id']`
- **Check:** Is the `admin_messages.php` page being accessed correctly?
- **Check:** Check database - messages should have receiver_id = admin_id

### Issue: "Messages not updating in real-time"
- **Check:** Is JavaScript enabled in browser?
- **Check:** Check browser Network tab to see if API calls are working
- **Check:** Are there any PHP errors? Check server error logs

---

## 📞 Support

For issues or questions:
1. Check the browser console (F12) for JavaScript errors
2. Check server error logs for PHP errors
3. Verify database connection in `connection.php`
4. Test API endpoints directly in URL bar

---

## Version
**v1.0** - Initial Release

---

Generated: November 17, 2025
