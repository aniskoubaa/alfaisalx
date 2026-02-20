<?php
/**
 * AlfaisalX Admin Authentication
 * 
 * Simple session-based authentication for admin panel.
 * Default credentials stored in database settings table.
 */

session_start();

require_once dirname(dirname(__DIR__)) . '/database/Database.php';

class AdminAuth {
    private $db;
    
    // Default admin credentials (should be changed after first login)
    private const DEFAULT_USERNAME = 'admin';
    private const DEFAULT_PASSWORD = 'alfaisalx2025';
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->ensureAdminExists();
    }
    
    /**
     * Ensure admin credentials exist in database
     */
    private function ensureAdminExists() {
        $username = $this->db->getSetting('admin_username');
        if (!$username) {
            $this->db->execute(
                "INSERT OR REPLACE INTO settings (key, value, description) VALUES (?, ?, ?)",
                ['admin_username', self::DEFAULT_USERNAME, 'Admin panel username']
            );
            $this->db->execute(
                "INSERT OR REPLACE INTO settings (key, value, description) VALUES (?, ?, ?)",
                ['admin_password', password_hash(self::DEFAULT_PASSWORD, PASSWORD_DEFAULT), 'Admin panel password (hashed)']
            );
        }
    }
    
    /**
     * Attempt to log in with credentials
     */
    public function login($username, $password) {
        $storedUsername = $this->db->getSetting('admin_username');
        $storedPassword = $this->db->getSetting('admin_password');
        
        if ($username === $storedUsername && password_verify($password, $storedPassword)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_login_time'] = time();
            return true;
        }
        return false;
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            return false;
        }
        
        // Session timeout after 2 hours
        if (isset($_SESSION['admin_login_time']) && (time() - $_SESSION['admin_login_time']) > 7200) {
            $this->logout();
            return false;
        }
        
        return true;
    }
    
    /**
     * Log out
     */
    public function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
    
    /**
     * Change password
     */
    public function changePassword($currentPassword, $newPassword) {
        $storedPassword = $this->db->getSetting('admin_password');
        
        if (!password_verify($currentPassword, $storedPassword)) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }
        
        if (strlen($newPassword) < 8) {
            return ['success' => false, 'message' => 'New password must be at least 8 characters'];
        }
        
        $this->db->execute(
            "UPDATE settings SET value = ? WHERE key = 'admin_password'",
            [password_hash($newPassword, PASSWORD_DEFAULT)]
        );
        
        return ['success' => true, 'message' => 'Password changed successfully'];
    }
    
    /**
     * Require authentication - redirect to login if not logged in
     */
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
    }
}

// Global auth instance
$auth = new AdminAuth();
