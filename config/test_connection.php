<?php
require_once 'config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT username, role FROM employees WHERE status = 'active'");
    $users = $stmt->fetchAll();
    
    echo "<h3>Utilisateurs disponibles :</h3>";
    foreach ($users as $user) {
        echo "- " . $user['username'] . " (" . $user['role'] . ")<br>";
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
