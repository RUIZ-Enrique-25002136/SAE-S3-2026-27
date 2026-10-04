<?php
/** @var string[] $erreurs */
/** @var bool $succes */
?>
<?php foreach ($erreurs as $erreur) : ?>
    <ul><li><?= htmlspecialchars($erreur) ?></li></ul>
<?php endforeach; ?>

<?php if ($succes) : ?>
    <p>Si cet email existe, un lien vient d'être envoyé</p>
<?php else: ?>
    <form method="post" action="/forgot-password">
        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required>
        </p>
        <button type="submit">Envoyer le lien</button>
    </form>
<?php endif; ?>
