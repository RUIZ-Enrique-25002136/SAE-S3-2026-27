<?php
/** @var bool $success */
/** @var string[] $errors */
/** @var bool $isTokenValid */
/** @var string $token */
use App\Core\Csrf;
?>

<?php if ($success): ?>
    Mot de passe réinitialisé
    <a href="/login">Vous Connecter</a>
<?php else: ?>
    <?php foreach ($errors as $error): ?>
        <ul><li><?= htmlspecialchars($error) ?></li></ul>
    <?php endforeach; ?>

    <?php if ($isTokenValid): ?>
        <form method="post" action="/reset-password">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
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
