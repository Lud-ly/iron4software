<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Iron4Software'; ?></title>
    <link rel="stylesheet" href="/iron4software/assets/css/style.css">
    <meta name="company" content="Iron4Software SARL">
    <meta name="location" content="Saint-Martin-de-Londres, France">
    <meta name="authors" content="Ludo, Damien, Emilio">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo-section">
                <div class="company-info">
                    <h1>Iron4Software</h1>
                    <p>Saint-Martin-de-Londres, France</p>
                </div>
            </div>
            
            <?php if (Security::isLoggedIn()): ?>
            <nav class="main-nav">
                <ul class="nav-menu">
                    <li><a href="/iron4software/secure/dashboard.php">🏠 Dashboard</a></li>
                    <li><a href="/iron4software/secure/tools.php">🔧 Outils</a></li>
                    <li><a href="/iron4software/secure/admin.php">⚙️ Admin</a></li>
                    <li><a href="/iron4software/secure/reports.php">📊 Rapports</a></li>
                </ul>
            </nav>
            
            <div class="user-info">
                <span>👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="/iron4software/auth/logout.php" class="btn btn-logout">Déconnexion</a>
            </div>
            <?php endif; ?>
        </div>
    </header>
    
    <main class="main-content">
