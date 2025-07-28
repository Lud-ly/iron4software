<?php
/**
 * Iron4Software - Système de Sécurité
 * @authors Ludo, Damien, Emilio
 */

// AJOUT OBLIGATOIRE
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclusion de la base de données
require_once __DIR__ . '/database.php';

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
        try {
            $db = Database::getInstance()->getConnection();
            
            // VERSION SÉCURISÉE (recommandée)
            $stmt = $db->prepare("SELECT * FROM employees WHERE username = ? AND password = ? AND status = 'active'");
            $stmt->execute([$username, $password]);
            
            $user = $stmt->fetch();
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['department'] = $user['department'];
                
                self::logActivity('LOGIN', "Connexion: " . $user['username']);
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
    
    public static function logout() {
        if (isset($_SESSION['username'])) {
            self::logActivity('LOGOUT', "Déconnexion: " . $_SESSION['username']);
        }
        session_unset();
        session_destroy();
    }
    
    private static function logActivity($action, $details) {
        $logDir = __DIR__ . '/../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logFile = $logDir . '/security.log';
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        
        $logEntry = "[$timestamp] [$action] $details - IP: $ip\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}
?>
