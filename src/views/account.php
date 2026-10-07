<?php
/**
 * @var \App\Models\User $account
 * @var string[]         $errors
 * @var bool             $success
 */
use App\Core\Csrf;
?>
<div class="card account-card">
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

    <div class="account-info">
        <div class="account-info-row">
            <span class="account-info-label">Email</span>
            <span class="account-info-value"><?= htmlspecialchars($account->email) ?></span>
        </div>
        <div class="account-info-row">
            <span class="account-info-label">Pseudo</span>
            <span class="account-info-value"><?= htmlspecialchars($account->login ?? 'aucun') ?></span>
        </div>
        <?php if ($account->createdAt !== null): ?>
            <div class="account-info-row">
                <span class="account-info-label">Inscrit le</span>
                <span class="account-info-value"><?= htmlspecialchars(date('d/m/Y', (int) strtotime($account->createdAt))) ?></span>
            </div>
        <?php endif; ?>
    </div>

    <section class="account-section">
        <h2>Modifier mon Pseudo</h2>
        <form method="post" action="/account">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
            <p>
                <label for="login">Nouveau Pseudo</label>
                <input type="text" id="login" name="login" value="<?= htmlspecialchars($account->login ?? '') ?>" minlength="3" maxlength="30" pattern="[A-Za-z0-9_\-]{3,30}" required>
            </p>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </section>

    <section class="account-section account-danger">
        <h2>Supprimer mon compte</h2>
        <p class="account-danger-text">Cette action est définitive : toutes vos données seront effacées.</p>
        <form method="post" action="/account/delete">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
            <p>
                <label for="password">Mot de passe pour confirmer</label>
                <input type="password" id="password" name="password" required>
            </p>
            <button type="submit" class="btn btn-danger">Supprimer mon compte</button>
        </form>
    </section>
</div>
