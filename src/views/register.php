<?php
/**
 * @var bool $success
 * @var string[]   $errors
 * @var string     $email
 * @var string     $login
 */
use App\Core\Csrf;
if ($success) :?>
<p>Inscription réussie.</p><br>
<p>Veuillez vérifier votre email.</p>

<?php else : ?>
<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $error): ?>
    <ul><li> <?=htmlspecialchars($error)?> </li></ul>
    <?php endforeach; ?>
<?php endif; ?>
<?php endif; ?>
<h1>S'inscrire</h1>
<form method="post" action="/register">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
    <p>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
    </p>
    <p>
        <label for="login">Pseudo</label><br>
        <input type="text" id="login" name="login" value="<?= htmlspecialchars($login) ?>" minlength="3" maxlength="30" pattern="[A-Za-z0-9_\-]{3,30}" required>
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
