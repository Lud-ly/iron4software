<?php
require_once 'config/database.php';
require_once 'config/security.php';

$pageTitle = "Iron4Software SARL - Accueil";
include 'includes/header.php';
?>

<div class="homepage">
    <div class="hero-section">
        <div class="hero-content">
            <h1>🛡️ Iron4Software SARL</h1>
            <h2>Solutions Informatiques</h2>
            <p class="company-location">Saint-Martin-de-Londres, Occitanie, France</p>
            <p class="hero-description">
                Société spécialisée dans le développement de solutions informatiques sur mesure,
                audits de sécurité et conseils technologiques.
            </p>
        </div>
        
        <div class="hero-stats">
            <div class="stat-box">
                <h3>15+</h3>
                <p>Projets actifs</p>
            </div>
            <div class="stat-box">
                <h3>50+</h3>
                <p>Clients</p>
            </div>
            <div class="stat-box">
                <h3>2024</h3>
                <p>Année création</p>
            </div>
        </div>
        
        <div class="access-card">
            <?php if (!Security::isLoggedIn()): ?>
                <h3>🔐 Accès Employés</h3>
                <p>Connectez-vous pour accéder aux outils internes</p>
                <a href="auth/login.php" class="btn btn-primary">Se connecter</a>
            <?php else: ?>
                <h3>✅ Connecté</h3>
                <p>Bienvenue <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <a href="secure/dashboard.php" class="btn btn-success">Tableau de bord</a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="services-section">
        <h3>🚀 Nos Services</h3>
        <div class="services-grid">
            <div class="service-item">
                <h4>💻 Développement Web</h4>
                <p>Applications PHP/MySQL, sites e-commerce, CRM sur mesure</p>
            </div>
            <div class="service-item">
                <h4>🔒 Sécurité Informatique</h4>
                <p>Audits de sécurité, tests de pénétration, formations</p>
            </div>
            <div class="service-item">
                <h4>☁️ Solutions Cloud</h4>
                <p>Migration cloud, infrastructure, hébergement sécurisé</p>
            </div>
            <div class="service-item">
                <h4>📊 Business Intelligence</h4>
                <p>Tableaux de bord, analyse de données, reporting</p>
            </div>
        </div>
    </div>
    
    <div class="company-info">
        <h3>📍 Iron4Software SARL</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>Siège social :</strong> Saint-Martin-de-Londres, 34380
            </div>
            <div class="info-item">
                <strong>Région :</strong> Occitanie, France
            </div>
            <div class="info-item">
                <strong>SIRET :</strong> 912 345 678 00012
            </div>
            <div class="info-item">
                <strong>Secteur :</strong> Services informatiques
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
