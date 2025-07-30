<?php
require_once '../config/database.php';
require_once '../config/security.php';

Security::requireLogin();

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager') {
    die('<div style="color:red;padding:20px;">Accès refusé - Privilèges insuffisants</div>');
}

$pageTitle = "Administration - Iron4Software";
$db = Database::getInstance()->getConnection();

$searchResults = null;
if ($_GET && isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM employees WHERE username LIKE '%$search%' OR department LIKE '%$search%'";
    $searchResults = $db->query($sql);
}

include '../includes/header.php';
?>

<div class="admin-container">
    <div class="admin-header">
        <h2>🔍 Recherche Employés - Iron4Software</h2>
        <p>Module de recherche d'employés</p>
    </div>
    
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
                    <p><strong>Exemples de recherche :</strong></p>
                    <ul>
                        <li><code>admin</code> - Recherche normale</li>
                        <li><code>dev</code> - Département développement</li>
                    </ul>
                </div>
                
                <?php if ($searchResults && $searchResults->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Rôle</th>
                            <th>Département</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($employee = $searchResults->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employee['id']); ?></td>
                            <td><?php echo htmlspecialchars($employee['username']); ?></td>
                            <td><?php echo htmlspecialchars($employee['role']); ?></td>
                            <td><?php echo htmlspecialchars($employee['department']); ?></td>
                            <td><?php echo htmlspecialchars($employee['status']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php elseif (isset($_GET['search'])): ?>
                <p>Aucun résultat trouvé pour "<?php echo htmlspecialchars($_GET['search']); ?>"</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>