
<?php /**
* @var bool $success
* @var string[]   $errors
* @var string     $email
*/
 if ($success) :?>
    <div class="success-message">Connexion réussie. Redirection...</div>
    <meta http-equiv="refresh" content="1;url=/">
    <p><a href="/">Cliquez ici si vous n'êtes pas redirigé.</a></p>

<?php else : ?>
    <?php if (!empty($errors)) : ?>
        <?php foreach ($errors as $error): ?>
             <ul><li> <?= htmlspecialchars($error) ?> </li></ul>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif ?>
<h1>Connexion</h1>
<form method="post" action="/login">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>">
    <p>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
    </p>
    <p>
        <label for="password">Mot de passe</label><br>
        <input type="password" id="password" name="password" required>
    </p>
    <a href="/forgot-password"> Mot de passe oublié ?</a>
    <button name="action" type="submit" value = "connexion">Se connecter</button>
</form>