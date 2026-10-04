<?php
/** @var array{id: int, email: string}|null $utilisateur */
?>
<div class="card welcome-card">
    <h1>Bienvenue</h1>

    <?php if ($utilisateur !== null): ?>
        <p class="welcome-text">Bonjour <strong><?= htmlspecialchars($utilisateur['email']) ?></strong>, vous êtes connecté.</p>
        <form method= 'post' action = '/logout' class="card-actions">
            <button type="submit" class="btn btn-secondary">Déconnexion</button>
        </form>
    <?php else: ?>
        <p class="welcome-text">Vous n'êtes pas connecté.</p>
        <p>Veuillez vous identifier ou créer un compte pour accéder à votre espace</p>
        <div class="card-actions">
            <a href="/login" class="btn btn-primary">Connexion</a>
            <a href="/register" class="btn btn-secondary">Créer un compte</a>
        </div>
    <?php endif; ?>
</div>