<?php
/**
 * @var \App\Models\User $account
 * @var string[]         $errors
 * @var bool             $success
 */
use App\Core\Csrf;

$displayName = $account->login ?? $account->email;
$initial = mb_strtoupper(mb_substr($displayName, 0, 1, 'UTF-8'), 'UTF-8');
?>
<div class="card account-card">
    <div class="account-header">
        <div class="account-avatar" aria-hidden="true"><?= htmlspecialchars($initial) ?></div>
        <div class="account-header-text">
            <h1>Mon compte</h1>
            <p class="account-header-email"><?= htmlspecialchars($account->email) ?></p>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success">Votre pseudo a été modifié avec succès.</div>
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

    <!-- Section Informations personnelles -->
    <section class="account-section">
        <h2 class="account-section-title">Informations personnelles</h2>
        <div class="account-details-grid">
            <div class="account-detail-item">
                <span class="account-detail-label">Adresse e-mail</span>
                <span class="account-detail-value"><?= htmlspecialchars($account->email) ?></span>
            </div>
            <div class="account-detail-item">
                <span class="account-detail-label">Pseudo actuel</span>
                <span class="account-detail-value">
                    <?= $account->login !== null ? htmlspecialchars($account->login) : '<span class="text-muted">Aucun pseudo</span>' ?>
                </span>
            </div>
            <?php if ($account->createdAt !== null): ?>
                <div class="account-detail-item">
                    <span class="account-detail-label">Date d'inscription</span>
                    <span class="account-detail-value"><?= htmlspecialchars(date('d/m/Y', (int) strtotime($account->createdAt))) ?></span>
                </div>
            <?php endif; ?>
            <div class="account-detail-item">
                <span class="account-detail-label">Statut du compte</span>
                <span class="account-detail-value">
                    <span class="badge-status-verified">✓ Vérifié</span>
                </span>
            </div>
        </div>
    </section>

    <!-- Section Modifier mon pseudo -->
    <section class="account-section">
        <h2 class="account-section-title">Modifier mon pseudo</h2>
        <p class="account-section-desc">Ce pseudo sera affiché publiquement aux autres membres sur le site.</p>

        <form method="post" action="/account" class="account-form">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
            <div class="form-group">
                <label for="login">Nouveau pseudo</label>
                <input type="text" id="login" name="login" value="<?= htmlspecialchars($account->login ?? '') ?>" minlength="3" maxlength="30" pattern="[A-Za-z0-9_\-]{3,30}" placeholder="3 à 30 caractères (lettres, chiffres, - ou _)" required>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer mon pseudo</button>
        </form>
    </section>

    <!-- Section Suppression du compte (Zone de danger) -->
    <section class="account-section account-danger-section">
        <div class="danger-header">
            <h2 class="danger-title">Supprimer mon compte</h2>
            <p class="danger-desc">Cette action est définitive : toutes vos données personnelles seront immédiatement effacées.</p>
        </div>

        <form method="post" action="/account/delete" class="account-form account-danger-form">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
            <div class="form-group">
                <label for="password">Mot de passe pour confirmer</label>
                <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
            </div>
            <button type="submit" class="btn btn-danger">Supprimer mon compte</button>
        </form>
    </section>
</div>
