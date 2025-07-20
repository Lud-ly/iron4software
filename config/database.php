<?php
/**
 * Iron4Software - Configuration Base de Données
 * @authors Ludo, Damien, Emilio
 * @location DataScientest, France
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'iron4_admin');
define('DB_PASS', 'Iron4Soft2024!');
define('DB_NAME', 'iron4software');

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($this->connection->connect_error) {
                throw new Exception("Connexion échouée: " . $this->connection->connect_error);
            }
            
            // Configuration MySQL vulnérable
            $this->connection->query("SET sql_mode = ''");
            
        } catch (Exception $e) {
            error_log("Database Error: " . $e->getMessage());
            die("Erreur de connexion à la base de données");
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
