

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
    <?=$content?>
</main>

    <footer class="site-footer">
    <p> Nos réseaux :</p>
    <a href = "https://x.com"><img class = "reseaux" src="/assets/x.svg" alt="Notre page X" ></a>
    <a href = "https://facebook.com"><img class = "reseaux" src="/assets/facebook.svg" alt="Notre page Facebook" ></a>
    <a href = "https://instagram.com"><img class = "reseaux" src="/assets/instagram.svg" alt="Notre page Instagram" ></a><br>
    <a href = "/legal-notice">Notice légale</a>
    <a href = "/sitemap">Plan du site</a>
    </footer>
</body>
</html>