<?php
require_once '../config/database.php';
require_once '../config/security.php';

Security::requireLogin();

$pageTitle = "Tableau de bord - Iron4Software";
$db = Database::getInstance()->getConnection();

$stats = [
    'projects' => $db->query("SELECT COUNT(*) as count FROM projects")->fetch_assoc()['count'],
    'clients' => $db->query("SELECT COUNT(*) as count FROM clients")->fetch_assoc()['count'],
    'employees' => $db->query("SELECT COUNT(*) as count FROM employees WHERE status='active'")->fetch_assoc()['count']
];

include '../includes/header.php';
?>

<div class="dashboard">
    <div class="dashboard-header">
        <h2>📊 Tableau de Bord Iron4Software</h2>
        <p>Bienvenue <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> 
        (<?php echo htmlspecialchars($_SESSION['department'] ?? 'N/A'); ?>)</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📁</div>
            <div class="stat-content">
                <h3><?php echo $stats['projects']; ?></h3>
                <p>Projets</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3><?php echo $stats['clients']; ?></h3>
                <p>Clients</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👤</div>
            <div class="stat-content">
                <h3><?php echo $stats['employees']; ?></h3>
                <p>Employés</p>
            </div>
        </div>
    </div>
    
    <div class="dashboard-content">
        <div class="panel">
            <div class="panel-header">
                <h3>🚀 Projets Récents</h3>
            </div>
            <div class="panel-body">
                <?php
                $projects = $db->query("SELECT * FROM projects ORDER BY created_date DESC LIMIT 5");
                if ($projects && $projects->num_rows > 0):
                ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Projet</th>
                            <th>Client</th>
                            <th>Type</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($project = $projects->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($project['name']); ?></td>
                            <td><?php echo htmlspecialchars($project['client']); ?></td>
                            <td><?php echo htmlspecialchars($project['type']); ?></td>
                            <td><span class="status-<?php echo $project['status']; ?>">
                                <?php echo htmlspecialchars($project['status']); ?>
                            </span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>Aucun projet récent</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="info-panel">
        <h4>👤 Informations Session</h4>
        <p><strong>Utilisateur :</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <p><strong>Rôle :</strong> <?php echo htmlspecialchars($_SESSION['role']); ?></p>
        <p><strong>IP :</strong> <?php echo $_SERVER['REMOTE_ADDR']; ?></p>
        <p><strong>User-Agent :</strong> <?php echo htmlspecialchars(substr($_SERVER['HTTP_USER_AGENT'], 0, 50)); ?>...</p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
