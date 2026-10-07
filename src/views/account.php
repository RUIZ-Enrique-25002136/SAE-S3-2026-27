<?php
/**
 * @var \App\Models\User $account
 * @var string[]         $errors
 * @var bool             $success
 */
use App\Core\Csrf;
?>
<div class="card">
    <h1>Mon compte</h1>

    <?php if ($success): ?>
        <div class="alert alert-success">Votre Pseudo a été modifié.</div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <p>Email : <?= htmlspecialchars($account->email) ?></p>
    <p>Pseudo : <?= htmlspecialchars($account->login ?? 'aucun') ?></p>
    <?php if ($account->createdAt !== null): ?>
        <p>Inscrit le : <?= htmlspecialchars(date('d/m/Y', (int) strtotime($account->createdAt))) ?></p>
    <?php endif; ?>

    <h2>Modifier mon Pseudo</h2>
    <form method="post" action="/account">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
        <p>
            <label for="login">Nouveau Pseudo</label><br>
            <input type="text" id="login" name="login" value="<?= htmlspecialchars($account->login ?? '') ?>" minlength="3" maxlength="30" pattern="[A-Za-z0-9_\-]{3,30}" required>
        </p>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>

    <h2>Supprimer mon compte</h2>
    <p>Cette action est définitive : toutes vos données seront effacées.</p>
    <form method="post" action="/account/delete">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
        <p>
            <label for="password">Mot de passe pour confirmer</label><br>
            <input type="password" id="password" name="password" required>
        </p>
        <button type="submit" class="btn btn-secondary">Supprimer mon compte</button>
    </form>
</div>
