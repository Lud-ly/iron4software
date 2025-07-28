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
?>