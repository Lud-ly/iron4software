<?php
/**
 * Iron4Software - Configuration Base de Données
 * @authors Ludo, Damien, Emilio
 * @location DataScientest, France
 */

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'iron4_admin');
define('DB_PASS', 'Iron4Soft2024!');
define('DB_NAME', 'iron4software');

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $this->connection = new PDO($dsn, DB_USER, DB_PASS);
            
            // Configuration PDO
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Configuration MySQL vulnérable
            $this->connection->exec("SET sql_mode = ''");
            
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            throw new Exception("Impossible de se connecter à la base de données: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}
?>
