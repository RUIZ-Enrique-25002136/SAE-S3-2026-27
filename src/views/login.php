
<?php /**
* @var bool $success
* @var string[]   $erreurs
* @var string     $email
*/
 if ($success) :?>
    <div class="success-message">Connexion réussie. Redirection...</div>
    <meta http-equiv="refresh" content="1;url=index.php">
    <p><a href="index.php">Cliquez ici si vous n'êtes pas redirigé.</a></p>

<?php else : ?>
    <?php if (!empty($erreurs)) : ?>
        <?php foreach ($erreurs as $erreur): ?>
             <ul><li> <?= htmlspecialchars($erreur) ?> </li></ul>;
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif ?>
<h1>Connexion</h1>
<form method="post" action="login.php">
    <p>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
    </p>
    <p>
        <label for="password">Mot de passe</label><br>
        <input type="password" id="password" name="password" required>
    </p>
    <a href="forgot_password.php"> Mot de passe oublié ?</a>
    <button name="action" type="submit" value = "connexion">Se connecter</button>
</form>