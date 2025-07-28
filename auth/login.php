<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';
require_once '../config/security.php';
require_once '../includes/functions.php';

$error = '';
$success = '';

if (Security::isLoggedIn()) {
    header('Location: /iron4software/secure/dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = sanitizeInput($_POST['password'] ?? '');
    
    if (!empty($username) && !empty($password)) {
        if (Security::login($username, $password)) {
            $success = "Connexion réussie !";
            header("refresh:1;url=/iron4software/secure/dashboard.php");
        } else {
            $error = "Identifiants incorrects";
        }
    } else {
        $error = "Veuillez remplir tous les champs";
    }
}

$pageTitle = "Connexion - Iron4Software";
include '../includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>🔐 Connexion Employés</h2>
            <p>Plateforme Iron4Software</p>
        </div>
        
        <div class="auth-body">
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label>👤 Nom d'utilisateur</label>
                    <input type="text" name="username" 
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" 
                           placeholder="Votre nom d'utilisateur" required>
                </div>
                
                <div class="form-group">
                    <label>🔒 Mot de passe</label>
                    <input type="password" name="password" 
                           placeholder="Votre mot de passe" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-full">Se connecter</button>
            </form>
        </div>
        
        <div class="auth-footer">
            <div class="test-accounts">
                <h4>Comptes de test :</h4>
                <div class="accounts-list">
                    <p><strong>admin</strong> / admin123 (Administrateur)</p>
                    <p><strong>jdupont</strong> / password (Développeur)</p>
                    <p><strong>mmartin</strong> / test123 (Manager)</p>
                    <p><strong>sgarcia</strong> / iron4 (Analyste sécurité)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
