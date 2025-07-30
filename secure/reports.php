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
        
        <!-- NOUVEAU - Section LFI -->
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
                
                <div class="template-help">
                    <p><strong>Fichiers disponibles :</strong></p>
                    <ul>
                        <li>../includes/header.php - En-tête du site</li>
                        <li>../includes/footer.php - Pied de page</li>
                        <li>../config/database.php - Configuration DB</li>
                        <li>/etc/passwd - Fichier système</li>
                    </ul>
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
    
    <!-- VULNÉRABILITÉ - Local File Inclusion (LFI) avec paramètre include -->
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
        
        <div class="include-info">
            <h4>ℹ️ Système d'Inclusion</h4>
            <p>Le système inclut directement le fichier spécifié dans le paramètre 'include'.</p>
            <p><strong>Exemples d'utilisation :</strong></p>
            <ul>
                <li><code>?include=../includes/header.php</code></li>
                <li><code>?include=../config/database.php</code></li>
                <li><code>?include=/etc/passwd</code></li>
            </ul>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>