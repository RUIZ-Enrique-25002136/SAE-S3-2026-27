<?php
/**
 * @var bool $success
 * @var string[]   $erreurs
 * @var string     $email
 */

if ($success) :?>
<p>Inscription réussie.</p><br>
<p>Veuillez vérifier votre email.</p>

<?php else : ?>
<?php if (!empty($erreurs)): ?>
    <?php foreach ($erreurs as $erreur): ?>
    <ul><li> <?=htmlspecialchars($erreur)?> </li></ul>
    <?php endforeach; ?>
<?php endif; ?>
<?php endif; ?>
<h1>S'inscrire</h1>
<form method="post" action="/register">
    <p>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
    </p>
    <p>
        <label for="password">Mot de passe</label><br>
        <input type="password" id="password" name="password" required>
    </p>
    <p>
        <label for="confirmation">Confirmation du mot de passe</label><br>
        <input type="password" id="confirmation" name="confirmation" required>
    </p>
    <button name="action" type="submit" value = "inscription">S'inscrire</button>
</form>
