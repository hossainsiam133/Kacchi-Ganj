# Kacchi-Ganj Authentication & Authorization System

## Overview

This document describes the authentication and authorization system implemented for the Kacchi-Ganj restaurant application.

## Single Entry Point

### Main Landing Page
- **File**: `index.php` (root directory)
- **Purpose**: Serves as the single entry point for the application
- **Content**: Includes home page content (menu, products, restaurant details)
- **Access**: Public - no authentication required

## Public vs Protected Content

### Public Content (No Login Required)
Users can view without authentication:
- Landing page (`index.php`)
- Menu listings
- Restaurant information
- Product/dish details

### Protected Actions (Login Required)
Users must be authenticated to:
- Add items to cart
- View cart
- Proceed to checkout
- Place orders
- View order history
- Download receipts
- Send/view messages

## Authentication Files

### Helper Functions: `02_User Site/auth_helper.php`

Provides utility functions for authentication:

```php
is_user_logged_in()           // Check if user is logged in
is_admin_logged_in()          // Check if admin is logged in
require_user_login($path)     // Force user login, redirect if needed
require_admin_login($path)    // Force admin login, redirect if needed
set_return_url($url)          // Store current page as return destination
get_and_clear_return_url()    // Retrieve and clear stored return URL
safe_redirect($url, $internal) // Safely redirect (prevents open redirects)
get_user_id()                 // Get current user ID
get_admin_id()                // Get current admin ID
```

## Protected Pages

The following pages now require authentication:

1. **`02_User Site/cart.php`** - Shopping cart
2. **`02_User Site/checkout.php`** - Checkout process
3. **`02_User Site/order_history.php`** - User's past orders
4. **`02_User Site/generate_receipt.php`** - Receipt download (also verifies order ownership)
5. **`02_User Site/messages.php`** - Messaging system

## Return URL Flow

### How It Works

1. **User attempts protected action while logged out**
   - Example: User tries to add item to cart from menu
   - Current URL is stored in `$_SESSION['return_url']`
   - User is redirected to login page

2. **User logs in or registers**
   - Login form validates credentials
   - If successful, checks for stored return URL
   - User is redirected back to original page/action

3. **After login redirect**
   - Any pending actions (like add-to-cart) are automatically processed
   - User returns to the page they came from
   - Seamless experience

### URL Parameter

- **Parameter**: `return_url` (GET parameter)
- **Format**: URL-encoded path
- **Example**: `login.php?return_url=%2FKacchi-Ganj%2F02_User%20Site%2Fmenu.php`
- **Safety**: Validated to prevent open redirect attacks (only allows same-host redirects)

## Add-to-Cart Protection

### Home.php and Menu.php
Both pages check for authentication before processing add-to-cart:

```php
if(empty($user_id)){
    // Store pending add-to-cart action
    $_SESSION['pending_action'] = [
        'action' => 'add_to_cart',
        'data' => [...product details...]
    ];
    // Redirect to login with return URL
    header('Location: ../01_Admin Site/login.php?return_url=' . urlencode(...));
    exit;
}
```

After login, the pending action is automatically executed.

## Login/Registration Pages

### Login Page: `01_Admin Site/login.php`
- Accepts `return_url` parameter
- Processes pending actions after successful login
- Redirects back to original page with stored return URL
- Includes link to registration with return_url preserved

### Registration Page: `01_Admin Site/register.php`
- Accepts `return_url` parameter
- After successful registration, redirects to login page with return_url
- Includes link to login with return_url preserved

## Security Considerations

### Open Redirect Prevention
- Return URLs are validated to only allow internal redirects
- Check ensures URL doesn't contain `..` (path traversal)
- URLs are compared against current host

### Order Access Control
- `generate_receipt.php` verifies user can only download their own orders
- Checks that order's `user_id` matches logged-in user's `user_id`

### Session Management
- Passwords are sanitized and escaped
- Email inputs are validated
- All database queries use prepared statements (where applicable)

## Implementation Example

### For Developers: Adding Protection to a New Page

1. Include the auth helper:
```php
<?php
include 'connection.php';
include 'auth_helper.php';
session_start();
require_user_login();
$user_id = get_user_id();
?>
```

2. Optionally store return URL before redirecting:
```php
if(!is_user_logged_in()) {
    set_return_url();
    safe_redirect('/Kacchi-Ganj/01_Admin Site/login.php');
}
```

## Session Variables

### User Session Variables
- `$_SESSION['user_id']` - Logged-in user ID
- `$_SESSION['user_name']` - User's name
- `$_SESSION['user_email']` - User's email
- `$_SESSION['return_url']` - (Temporary) URL to redirect to after login
- `$_SESSION['pending_action']` - (Temporary) Action to process after login

### Admin Session Variables
- `$_SESSION['admin_id']` - Logged-in admin ID
- `$_SESSION['admin_name']` - Admin's name
- `$_SESSION['admin_email']` - Admin's email

## Testing the Authentication System

### Test 1: Public Access
- Open `http://localhost/Kacchi-Ganj/index.php`
- Should display menu and products without login
- ✅ Expected: Page loads normally

### Test 2: Protected Page Access (Logged Out)
- Open `http://localhost/Kacchi-Ganj/02_User Site/cart.php`
- ✅ Expected: Redirects to login page

### Test 3: Add-to-Cart Without Login
- From menu, try to add item to cart while logged out
- ✅ Expected: Redirects to login page
- After login, item should be in cart
- ✅ Expected: Return to menu with item added

### Test 4: Order History Without Login
- Open `http://localhost/Kacchi-Ganj/02_User Site/order_history.php`
- ✅ Expected: Redirects to login page

### Test 5: Return URL Flow
- Add item to cart (triggers login redirect)
- Login successfully
- ✅ Expected: Redirected back to menu with item in cart

## Future Enhancements

- [ ] Implement CSRF token protection
- [ ] Add password hashing (bcrypt)
- [ ] Implement session timeout
- [ ] Add two-factor authentication
- [ ] Implement role-based access control (RBAC) for admin pages
- [ ] Add API endpoint protection with JWT tokens

---

**Last Updated**: November 22, 2025
**System Version**: 1.0
