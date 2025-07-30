<?php
/**
 * Iron4Software - Fonctions Utilitaires
 * @authors Ludo, Damien, Emilio
 */

 function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

function formatCurrency($amount) {
    return number_format($amount, 2, ',', ' ') . ' €';
}

function getRoleLabel($role) {
    $roles = [
        'admin' => 'Administrateur',
        'manager' => 'Manager',
        'developer' => 'Développeur',
        'analyst' => 'Analyste'
    ];
    return $roles[$role] ?? $role;
}

function getDepartmentLabel($department) {
    $departments = [
        'IT' => 'Informatique',
        'Development' => 'Développement',
        'Security' => 'Sécurité',
        'Management' => 'Direction'
    ];
    return $departments[$department] ?? $department;
}

function generateReport(string $type): array
{
    // Accès à la base de données
    $db = Database::getInstance()->getConnection();

    // Nettoyage basique du paramètre
    //$type = trim($type);

    // Requête préparée pour éviter les injections SQL
    $stmt = $db->prepare("SELECT * FROM projects WHERE type = :type ORDER BY created_date DESC");
    $stmt->bindValue(':type', $type, PDO::PARAM_STR);
    $stmt->execute();

    // Récupérer tous les projets correspondant au type demandé
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $projects ?: [];
}

?>