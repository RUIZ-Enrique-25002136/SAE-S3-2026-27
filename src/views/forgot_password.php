<?php
/** @var string[] $errors */
/** @var bool $success */
use App\Core\Csrf;
?>
<?php foreach ($errors as $error) : ?>
    <ul><li><?= htmlspecialchars($error) ?></li></ul>
<?php endforeach; ?>

<?php if ($success) : ?>
    <p>Si cet email existe, un lien vient d'être envoyé</p>
<?php else: ?>
    <form method="post" action="/forgot-password">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required>
        </p>
        <button type="submit">Envoyer le lien</button>
    </form>
<?php endif; ?>
