<?php
require_once  'includes/header.php';
require_once 'includes/footer.php';
session_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$utilisateur = $_SESSION['utilisateur'] ?? '';

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: index.php');
    $utilisateur = '';
    exit;
}

$titre = 'Accueil';
buildHeader($titre);
?>

<div class="card welcome-card">
    <h1>Bienvenue</h1>

    <?php if ($utilisateur !== ''): ?>
        <p class="welcome-text">Bonjour <strong><?= htmlspecialchars($utilisateur['email'] ?? $utilisateur['login'] ?? '') ?></strong>, vous êtes connecté.</p>
        <div class="card-actions">
            <a href="index.php?action=logout" class="btn btn-secondary">Déconnexion</a>
        </div>
    <?php else: ?>
        <p class="welcome-text">Vous n'êtes pas connecté.</p>
        <p>Veuillez vous identifier ou créer un compte pour accéder à votre espace</p>
        <div class="card-actions">
            <a href="login.php" class="btn btn-primary">Connexion</a>
            <a href="register.php" class="btn btn-secondary">Créer un compte</a>
        </div>
    <?php endif; ?>
</div>

<?php if($utilisateur == '') ?>
    <p>Vous n'êtes pas connecté. <a href="login.php">Connectez-vous</a> ou <a href="register.php">créez un compte</a>.</p>

<?php
buildFooter();?>
