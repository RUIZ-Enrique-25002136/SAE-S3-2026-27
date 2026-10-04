<?php
buildHeader('Méthode non autorisée');
?>
<div class="card">
    <h1>405 - Méthode non autorisée</h1>
    <p>La page <code><?= htmlspecialchars($path ?? '') ?></code> existe, mais pas pour ce type de requête.</p>
    <a href="/" class="btn btn-primary">Retour à l'accueil</a>
</div>
<?php
buildFooter();
