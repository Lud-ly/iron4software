<?php
require_once '../config/database.php';
require_once '../config/security.php';
require_once '../includes/functions.php';

Security::requireLogin();

$pageTitle = "Upload de Fichiers - Iron4Software";
$output = '';

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
        <h2>📁 Upload de Fichiers - Iron4Software</h2>
        <p>Upload et traitement de fichiers</p>
    </div>
    
    <div class="tools-grid">
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
                    <p>Images, documents, polices GDF</p>
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
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>