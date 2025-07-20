<?php
require_once '../config/database.php';
require_once '../config/security.php';
require_once '../includes/functions.php';

Security::requireLogin();

$pageTitle = "Outils Système - Iron4Software";
$output = '';

// VULNÉRABILITÉ - Command Injection (CVE-2024-1874)
if ($_POST && isset($_POST['command'])) {
    $command = $_POST['command'];
    $output = executeSystemCommand($command);
}

// VULNÉRABILITÉ - File Upload + GD (CVE-2022-31630)
if ($_FILES && isset($_FILES['upload'])) {
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $filename = $_FILES['upload']['name'];
    $destination = $uploadDir . $filename;
    
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $destination)) {
        $output = "Fichier uploadé : " . $filename;
        
        // Test GD si fichier .gdf
        if (extension_loaded('gd') && strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'gdf') {
            try {
                $font = imageloadfont($destination);
                $output .= "\nPolice GD chargée avec ID : " . $font;
            } catch (Exception $e) {
                $output .= "\nErreur GD : " . $e->getMessage();
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="tools-container">
    <div class="tools-header">
        <h2>🔧 Outils Système Iron4Software</h2>
        <p>Outils d'administration et diagnostic</p>
    </div>
    
    <div class="tools-grid">
        <div class="tool-panel">
            <div class="tool-header">
                <h3>⚡ Exécution de Commandes</h3>
            </div>
            <div class="tool-body">
                <form method="POST">
                    <div class="form-group">
                        <label>Commande système :</label>
                        <input type="text" name="command" 
                               value="<?php echo htmlspecialchars($_POST['command'] ?? 'whoami'); ?>" 
                               placeholder="whoami">
                    </div>
                    <button type="submit" class="btn btn-primary">Exécuter</button>
                </form>
                
                <div class="examples">
                    <p><strong>Exemples :</strong></p>
                    <ul>
                        <li><code>whoami</code> - Utilisateur courant</li>
                        <li><code>ls -la</code> - Liste des fichiers</li>
                        <li><code>ps aux</code> - Processus</li>
                        <li><code>netstat -tulpn</code> - Connexions</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="tool-panel">
            <div class="tool-header">
                <h3>📁 Upload de Fichiers</h3>
            </div>
            <div class="tool-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Fichier à uploader :</label>
                        <input type="file" name="upload">
                    </div>
                    <button type="submit" class="btn btn-success">Upload</button>
                </form>
                
                <div class="info">
                    <p><strong>Types supportés :</strong></p>
                    <p>Images, documents, polices GDF (test vulnérabilité GD)</p>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (!empty($output)): ?>
    <div class="output-panel">
        <div class="output-header">
            <h3>📋 Résultat</h3>
        </div>
        <div class="output-body">
            <pre class="command-output"><?php echo htmlspecialchars($output); ?></pre>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="system-info">
        <h3>🖥️ Informations Système</h3>
        <div class="info-grid">
            <p><strong>PHP :</strong> <?php echo PHP_VERSION; ?></p>
            <p><strong>Extensions :</strong> <?php echo count(get_loaded_extensions()); ?></p>
            <p><strong>GD :</strong> <?php echo extension_loaded('gd') ? '✅ Activé' : '❌ Désactivé'; ?></p>
            <p><strong>proc_open :</strong> <?php echo function_exists('proc_open') ? '✅ Disponible' : '❌ Désactivé'; ?></p>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
