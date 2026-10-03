<?php
function buildHeader(string $titre = 'Accueil') : void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $utilisateur = $_SESSION['utilisateur'] ?? null;
    $currentPage = basename($_SERVER['PHP_SELF'] ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titre) ?> - SAE S3</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>
<body>
<header class="site-header">
    <div class="nav-container">
        <a href="index.php" class="brand">SAE S3</a>
        <nav class="nav-links">
            <a href="index.php" class="nav-item <?= ($currentPage === 'index.php' || $currentPage === '') ? 'active' : '' ?>">Accueil</a>
            <?php if ($utilisateur === null): ?>
                <a href="../login.php" class="nav-item <?= $currentPage === 'login.php' ? 'active' : '' ?>">Connexion</a>
                <a href="register.php" class="btn-register">Inscription</a>
            <?php else: ?>
                <span class="user-badge"><?= htmlspecialchars($utilisateur['email'] ?? 'Connecté') ?></span>
                <a href="index.php?action=logout" class="nav-item nav-logout">Déconnexion</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="main-content">
<?php
}
?>
