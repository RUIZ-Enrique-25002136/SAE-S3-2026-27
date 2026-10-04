<?php
/** @var bool $success */
?>
<div class="card">
    <?php if ($success): ?>
        <h1>Email vérifié</h1>
        <p>Votre adresse est confirmée, vous pouvez maintenant vous connecter.</p>
        <a href="/login" class="btn btn-primary">Se connecter</a>
    <?php else: ?>
        <h1>Lien invalide</h1>
        <p>Ce lien de confirmation est invalide ou a déjà été utilisé.</p>
        <a href="/" class="btn btn-secondary">Retour à l'accueil</a>
    <?php endif; ?>
</div>
