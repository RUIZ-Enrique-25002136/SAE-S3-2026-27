<?php
function buildHeader(string $titre = 'Accueil') : void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $utilisateur = $_SESSION['utilisateur'] ?? null;
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $currentPath = rtrim($currentPath, '/') ?: '/';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titre) ?> - SAE S3</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>
<body>
<header class="site-header">
    <div class="nav-container">
        <a href="/" class="brand">SAE S3</a>
        <nav class="nav-links">
            <a href="/" class="nav-item <?= $currentPath === '/' ? 'active' : '' ?>">Accueil</a>
            <?php if ($utilisateur === null): ?>
                <a href="/login" class="nav-item <?= $currentPath === '/login' ? 'active' : '' ?>">Connexion</a>
                <a href="/register" class="btn-register <?= $currentPath === '/register' ? 'active' : '' ?>">Inscription</a>
            <?php else: ?>
                <span class="user-badge"><?= htmlspecialchars($utilisateur['email'] ?? 'Connecté') ?></span>
                <a href="/logout" class="nav-item nav-logout">Déconnexion</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="main-content">
<?php
}
?>
