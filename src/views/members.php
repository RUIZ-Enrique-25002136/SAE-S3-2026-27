<?php
/**
 * @var \App\Models\User[] $members
 * @var int                $page
 * @var int                $pages
 * @var int                $total
 */
?>
<div class="card members-card">
    <h1>Membres</h1>
    <p class="members-count"><?= $total ?> membre<?= $total > 1 ? 's' : '' ?> inscrit<?= $total > 1 ? 's' : '' ?>.</p>

    <?php if ($members === []): ?>
        <p class="members-empty">Aucun membre pour le moment.</p>
    <?php else: ?>
        <table class="members-table">
            <thead>
                <tr>
                    <th scope="col">Pseudo</th>
                    <th scope="col">Inscrit le</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td class="member-login"><?= htmlspecialchars($member->login ?? 'Membre n°' . $member->id) ?></td>
                        <td class="member-date"><?= $member->createdAt !== null ? htmlspecialchars(date('d/m/Y', (int) strtotime($member->createdAt))) : '' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if ($pages > 1): ?>
        <nav aria-label="Pagination">
            <?php if ($page > 1): ?>
                <a href="/members?page=<?= $page - 1 ?>" class="btn btn-secondary btn-sm">« Précédent</a>
            <?php endif; ?>
            <span class="pagination-info">Page <?= $page ?> sur <?= $pages ?></span>
            <?php if ($page < $pages): ?>
                <a href="/members?page=<?= $page + 1 ?>" class="btn btn-secondary btn-sm">Suivant »</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</div>
