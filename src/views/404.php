<?php
buildHeader('Page non trouvée');
?>
<div class="card">
    <h1>404 - Page non trouvée</h1>
    <p>La page <code><?= htmlspecialchars($path ?? '') ?></code> n'existe pas.</p>
    <a href="/" class="btn btn-primary">Retour à l'accueil</a>
</div>
<?php
buildFooter();