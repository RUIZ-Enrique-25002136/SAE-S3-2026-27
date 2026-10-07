<?php
/**
 * @var \App\Models\User[] $members
 * @var int                $page
 * @var int                $pages
 * @var int                $total
 */
?>
<div class="card members-card">
    <div class="members-header">
        <h1>Membres de la communauté</h1>
        <div class="members-count-badge">
            <strong><?= $total ?></strong> membre<?= $total > 1 ? 's' : '' ?> inscrit<?= $total > 1 ? 's' : '' ?>
        </div>
    </div>

    <?php if ($members === []): ?>
        <div class="members-empty">
            <div class="empty-icon" aria-hidden="true">👥</div>
            <h2>Aucun membre pour le moment</h2>
            <p>Les membres inscrits et vérifiés apparaîtront sur cette page.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="members-table">
                <thead>
                    <tr>
                        <th scope="col">Pseudo</th>
                        <th scope="col">Inscrit le</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                        <?php
                            $pseudo = $member->login ?? 'Membre n°' . $member->id;
                            $initial = mb_strtoupper(mb_substr($pseudo, 0, 1, 'UTF-8'), 'UTF-8');
                        ?>
                        <tr>
                            <td>
                                <div class="member-user-cell">
                                    <div class="member-avatar" aria-hidden="true"><?= htmlspecialchars($initial) ?></div>
                                    <span class="member-login"><?= htmlspecialchars($pseudo) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="member-date">
                                    <?= $member->createdAt !== null ? htmlspecialchars(date('d/m/Y', (int) strtotime($member->createdAt))) : '—' ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if ($pages > 1): ?>
        <nav class="pagination" aria-label="Pagination">
            <?php if ($page > 1): ?>
                <a href="/members?page=<?= $page - 1 ?>" class="btn btn-secondary btn-pagination">« Précédent</a>
            <?php else: ?>
                <span class="btn btn-secondary btn-pagination btn-disabled" aria-disabled="true">« Précédent</span>
            <?php endif; ?>

            <span class="pagination-info">Page <strong><?= $page ?></strong> sur <strong><?= $pages ?></strong></span>

            <?php if ($page < $pages): ?>
                <a href="/members?page=<?= $page + 1 ?>" class="btn btn-secondary btn-pagination">Suivant »</a>
            <?php else: ?>
                <span class="btn btn-secondary btn-pagination btn-disabled" aria-disabled="true">Suivant »</span>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</div>
