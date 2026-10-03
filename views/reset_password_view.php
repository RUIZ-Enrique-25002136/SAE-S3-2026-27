<?php
/** @var bool $succes */
/** @var string[] $erreurs */
/** @var bool $tokenValide */
?>

<?php if ($succes): ?>
    Mot de passe réinitialisé
    <a href="../login.php">Vous Conneter</a>
<?php else: ?>
    <?php foreach ($erreurs as $erreur): ?>
        <ul><li><?= htmlspecialchars($erreur) ?></li></ul>
    <?php endforeach; ?>

    <?php if ($tokenValide): ?>
        <form method="post" action="../reset_password.php">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <p>
                <label for="password">Nouveau mot de passe</label><br>
                <input type="password" id="password" name="password" required>
            </p>
            <p>
                <label for="confirmation">Confirmation</label><br>
                <input type="password" id="confirmation" name="confirmation" required>
            </p>
            <button type="submit">Réinitialiser</button>
        </form>
    <?php else: ?>
        <p>Lien invalide.</p>
    <?php endif; ?>
<?php endif; ?>
