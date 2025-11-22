<?php
/**
 * Authentication Helper Functions
 * 
 * Provides utilities for:
 * - Checking if user is authenticated
 * - Storing and retrieving return URLs
 * - Performing safe redirects
 * - Checking user type (admin vs regular user)
 */

/**
 * Check if user is currently authenticated (logged in)
 * 
 * @return bool True if user is logged in, false otherwise
 */
function is_user_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if admin is currently authenticated
 * 
 * @return bool True if admin is logged in, false otherwise
 */
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}

/**
 * Store the current page URL as the return destination after login
 * This should be called before redirecting to login page
 * 
 * @param string $url The URL to return to after login. If null, uses current page.
 */
function set_return_url($url = null) {
    if ($url === null) {
        // Store current request (full URL with query string)
        $url = $_SERVER['REQUEST_URI'];
    }
    $_SESSION['return_url'] = $url;
}

/**
 * Get the stored return URL and clear it from session
 * 
 * @return string|null The stored return URL or null if none exists
 */
function get_and_clear_return_url() {
    if (isset($_SESSION['return_url'])) {
        $url = $_SESSION['return_url'];
        unset($_SESSION['return_url']);
        return $url;
    }
    return null;
}

/**
 * Safely redirect to a URL, validating it's internal
 * 
 * @param string $url The URL to redirect to
 * @param bool $is_internal If true, only allows redirects to same host/domain
 */
function safe_redirect($url, $is_internal = true) {
    if ($is_internal) {
        // Validate URL is internal (same host)
        $parsed = parse_url($url);
        $current_host = parse_url("http://" . $_SERVER['HTTP_HOST']);
        
        // Allow relative URLs and URLs with same host
        if (isset($parsed['host']) && $parsed['host'] !== $current_host['host']) {
            // Redirect to home if external redirect attempted
            $url = '/Kacchi-Ganj/02_User Site/home.php';
        }
    }
    
    header("Location: " . $url);
    exit;
}

/**
 * Require user to be logged in
 * If not logged in, stores current page as return URL and redirects to login
 * 
 * @param string $login_path Path to login page (relative to document root)
 */
function require_user_login($login_path = '/Kacchi-Ganj/01_Admin Site/login.php') {
    if (!is_user_logged_in()) {
        set_return_url();
        safe_redirect($login_path);
    }
}

/**
 * Require admin to be logged in
 * If not logged in, redirects to admin login
 * 
 * @param string $login_path Path to admin login page (relative to document root)
 */
function require_admin_login($login_path = '/Kacchi-Ganj/01_Admin Site/login.php') {
    if (!is_admin_logged_in()) {
        safe_redirect($login_path);
    }
}

/**
 * Get current user ID if logged in
 * 
 * @return int|null User ID or null if not logged in
 */
function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current admin ID if logged in
 * 
 * @return int|null Admin ID or null if not logged in
 */
function get_admin_id() {
    return $_SESSION['admin_id'] ?? null;
}
?>
