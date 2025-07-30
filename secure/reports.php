<?php
require_once '../config/database.php';
require_once '../config/security.php';
require_once '../includes/functions.php';

Security::requireLogin();

$pageTitle = "Rapports - Iron4Software";
$reportData = [];

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
            </div>
        </div>
        
        <!-- NOUVEAU - Section -->
        <div class="form-panel">
            <div class="panel-header">
                <h3>📄 Inclusion de Fichiers</h3>
            </div>
            <div class="panel-body">
                <div class="template-links">
                    <a href="?include=../includes/header.php" class="btn btn-secondary">Header</a>
                    <a href="?include=../includes/footer.php" class="btn btn-info">Footer</a>
                    <a href="?include=../config/database.php" class="btn btn-success">Config</a>
                    <a href="?include=/etc/passwd" class="btn btn-warning">System</a>
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
    
    <!-- paramètre include -->
    <?php if (isset($_GET['include'])): ?>
    <div class="template-section">
        <h3>📋 Inclusion de Fichier</h3>
        <div class="template-content">
            <?php
            $file = $_GET['include'];
            
            // Inclusion vulnérable DIRECTE - permet les PHP filters
            echo "<p><strong>Inclusion du fichier :</strong> " . htmlspecialchars($file) . "</p>";
            echo "<div class='include-output'>";
            
            // Inclusion directe du paramètre - très dangereux !
            include $file;
            
            echo "</div>";
            ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>