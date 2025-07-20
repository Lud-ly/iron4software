<?php
require_once '../config/database.php';
require_once '../config/security.php';

Security::requireLogin();

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager') {
    die('<div style="color:red;padding:20px;">Accès refusé - Privilèges insuffisants</div>');
}

$pageTitle = "Administration - Iron4Software";
$db = Database::getInstance()->getConnection();
$message = '';

// VULNÉRABILITÉ - SQL Injection dans la recherche
if ($_GET && isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM employees WHERE username LIKE '%$search%' OR department LIKE '%$search%'";
    $searchResults = $db->query($sql);
}

// VULNÉRABILITÉ - Hash Keccak (CVE-2022-37454)
if ($_POST && isset($_POST['generate_hash'])) {
    $data = $_POST['data'];
    $algorithm = $_POST['algorithm'] ?? 'sha3-256';
    
    try {
        $maliciousData = str_repeat("A", 2048) . pack("Q*", 0x4141414141414141) . $data;
        $hash = hash($algorithm, $maliciousData);
        $message = "Hash généré : " . $hash;
    } catch (Exception $e) {
        $message = "Erreur hash : " . $e->getMessage();
    }
}

include '../includes/header.php';
?>

<div class="admin-container">
    <div class="admin-header">
        <h2>⚙️ Administration Iron4Software</h2>
        <p>Panneau d'administration système</p>
    </div>
    
    <?php if ($message): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <div class="admin-grid">
        <div class="admin-panel">
            <div class="panel-header">
                <h3>🔍 Recherche Employés</h3>
            </div>
            <div class="panel-body">
                <form method="GET">
                    <div class="form-group">
                        <label>Rechercher :</label>
                        <input type="text" name="search" 
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" 
                               placeholder="Nom ou département">
                    </div>
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </form>
                
                <div class="search-help">
                    <p><strong>Tests d'injection :</strong></p>
                    <p><code>' OR '1'='1' --</code></p>
                </div>
                
                <?php if (isset($searchResults) && $searchResults->num_rows > 0) { ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Rôle</th>
                            <th>Département</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($employee = $searchResults->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employee['id']); ?></td>
                            <td><?php echo htmlspecialchars($employee['username']); ?></td>
                            <td><?php echo htmlspecialchars($employee['role']); ?></td>
                            <td><?php echo htmlspecialchars($employee['department']); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } ?>
            </div>
        </div>
        
        <div class="admin-panel">
            <div class="panel-header">
                <h3>🔐 Générateur Hash</h3>
            </div>
            <div class="panel-body">
                <form method="POST">
                    <div class="form-group">
                        <label>Données :</label>
                        <textarea name="data" rows="3"><?php echo htmlspecialchars($_POST['data'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Algorithme :</label>
                        <select name="algorithm">
                            <option value="sha3-256">SHA3-256 (vulnérable CVE-2022-37454)</option>
                            <option value="sha3-512">SHA3-512 (vulnérable)</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="generate_hash" class="btn btn-warning">Générer Hash</button>
                </form>
                
                <p><small>⚠️ Attention : Les algorithmes SHA3 sont vulnérables au buffer overflow</small></p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
