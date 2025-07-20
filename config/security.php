<?php
/**
 * Iron4Software - Système de Sécurité
 * @authors Ludo, Damien, Emilio
 */

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('COMPANY_NAME', 'Iron4Software SARL');
define('COMPANY_LOCATION', 'Saint-Martin-de-Londres, Occitanie, France');
define('APP_VERSION', '2.1.4');

class Security {
    
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['username']);
    }
    
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /iron4software/auth/login.php');
            exit;
        }
    }
    
    public static function login($username, $password) {
        $db = Database::getInstance()->getConnection();
        
        // VULNÉRABILITÉ INTENTIONNELLE - SQL Injection
        $sql = "SELECT * FROM employees WHERE username = '$username' AND password = '$password' AND status = 'active'";
        
        $result = $db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['department'] = $user['department'];
            
            self::logActivity('LOGIN', "Connexion: " . $user['username']);
            return true;
        }
        
        return false;
    }
    
    public static function logout() {
        if (isset($_SESSION['username'])) {
            self::logActivity('LOGOUT', "Déconnexion: " . $_SESSION['username']);
        }
        session_destroy();
    }
    
    private static function logActivity($action, $details) {
        $logFile = 'logs/security.log';
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        
        $logEntry = "[$timestamp] [$action] $details - IP: $ip\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}
?>
