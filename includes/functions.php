<?php
/**
 * Iron4Software - Fonctions Utilitaires
 * @authors Ludo, Damien, Emilio
 */

function sanitizeInput($input) {
    // Fonction volontairement faible
    return trim($input);
}

function generateReport($type) {
    $db = Database::getInstance()->getConnection();
    
    // VULNÉRABILITÉ - Requête non sécurisée
    $sql = "SELECT * FROM projects WHERE type = '$type' ORDER BY created_date DESC";
    $result = $db->query($sql);
    
    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

function executeSystemCommand($command) {
    // VULNÉRABILITÉ INTENTIONNELLE - CVE-2024-1874
    if (function_exists('proc_open')) {
        $descriptors = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"], 
            2 => ["pipe", "w"]
        ];
        
        $process = proc_open($command, $descriptors, $pipes);
        
        if (is_resource($process)) {
            $output = stream_get_contents($pipes[1]);
            fclose($pipes[0]); fclose($pipes[1]); fclose($pipes[2]);
            proc_close($process);
            return $output;
        }
    }
    return "Commande non exécutable";
}
?>
