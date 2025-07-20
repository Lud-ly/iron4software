<?php
require_once '../config/database.php';
require_once '../config/security.php';
require_once '../includes/functions.php';

Security::requireLogin();

$pageTitle = "Rapports - Iron4Software";
$reportData = [];

// VULNÉRABILITÉ - SQL Injection dans les rapports
if ($_GET && isset($_GET['type'])) {
    $type = $_GET['type'];
    $reportData = generateReport($type);
}

include '../includes/header.php';
?>

<div class="reports-container">
    <div class="reports-header">
        <h2>📊 Générateur de Rapports Iron4Software</h2>
        <p>Rapports de projets et statistiques</p>
    </div>
    
    <div class="report-form">
        <div class="form-panel">
            <div class="panel-header">
                <h3>🎯 Type de Rapport</h3>
            </div>
            <div class="panel-body">
                <form method="GET">
                    <div class="form-group">
                        <label>Type de projet :</label>
                        <input type="text" name="type" 
                               value="<?php echo htmlspecialchars($_GET['type'] ?? ''); ?>" 
                               placeholder="web, mobile, consulting, security">
                    </div>
                    <button type="submit" class="btn btn-primary">Générer</button>
                </form>
                
                <div class="help">
                    <p><strong>Types disponibles :</strong> web, mobile, consulting, security</p>
                    <p><strong>Test injection :</strong> <code>' OR '1'='1</code></p>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (!empty($reportData)): ?>
    <div class="report-results">
        <h3>📋 Résultats</h3>
        <p><strong>Type :</strong> <?php echo htmlspecialchars($_GET['type']); ?></p>
        <p><strong>Projets trouvés :</strong> <?php echo count($reportData); ?></p>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Client</th>
                    <th>Type</th>
                    <th>Budget</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reportData as $project): ?>
                <tr>
                    <td><?php echo htmlspecialchars($project['name']); ?></td>
                    <td><?php echo htmlspecialchars($project['client']); ?></td>
                    <td><?php echo htmlspecialchars($project['type']); ?></td>
                    <td><?php echo number_format($project['budget'], 0, ',', ' '); ?> €</td>
                    <td><?php echo htmlspecialchars($project['status']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
