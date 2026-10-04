<?php
/**
 * Affiche le début de la page : <head>, menu de navigation et ouverture de <main>.
 *
 * @param string $title Titre de la page, affiché dans l'onglet
 * @return void
 */
function buildHeader(string $title = 'Accueil') : void
{
    $user = currentUser();
    $currentPath = currentPath();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - SAE S3</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>
<body>
<header class="site-header">
    <div class="nav-container">
        <a href="/" class="brand">SAE S3</a>
        <nav class="nav-links">
            <a href="/" class="nav-item <?= $currentPath === '/' ? 'active' : '' ?>">Accueil</a>
            <?php if ($user === null): ?>
                <a href="/login" class="nav-item <?= $currentPath === '/login' ? 'active' : '' ?>">Connexion</a>
                <a href="/register" class="btn-register <?= $currentPath === '/register' ? 'active' : '' ?>">Inscription</a>
            <?php else: ?>
                <span class="user-badge"><?= htmlspecialchars($user['email']) ?></span>
                <a href="/logout" class="nav-item nav-logout">Déconnexion</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="main-content">
<?php
}
?>
