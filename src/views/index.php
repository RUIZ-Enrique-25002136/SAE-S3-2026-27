<?php
/** @var array $utilisateur */
?>
<div class="card welcome-card">
    <h1>Bienvenue</h1>

    <?php if ($utilisateur !== ''): ?>
        <p class="welcome-text">Bonjour <strong><?= htmlspecialchars($utilisateur['email'] ?? $utilisateur['login'] ?? '') ?></strong>, vous êtes connecté.</p>
        <form method= 'post' action = '/SAE-S3-2026-27/logout.php' class="card-actions">
            <button type="submit" class="btn btn-secondary">Déconnexion</button>
        </form>
    <?php else: ?>
        <p class="welcome-text">Vous n'êtes pas connecté.</p>
        <p>Veuillez vous identifier ou créer un compte pour accéder à votre espace</p>
        <div class="card-actions">
            <a href="login.php" class="btn btn-primary">Connexion</a>
            <a href="register.php" class="btn btn-secondary">Créer un compte</a>
        </div>
    <?php endif; ?>
</div>

<?php if($utilisateur == ''): ?>
    <p>Vous n'êtes pas connecté. <a href="login.php">Connectez-vous</a> ou <a href="register.php">créez un compte</a>.</p>
<?php endif; ?>