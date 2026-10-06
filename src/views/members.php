<?php
/**
 * @var \App\Models\User[] $members
 * @var int                $page
 * @var int                $pages
 * @var int                $total
 */
?>
<div class="card">
    <h1>Membres</h1>
    <p><?= $total ?> membre<?= $total > 1 ? 's' : '' ?> inscrit<?= $total > 1 ? 's' : '' ?>.</p>

    <?php if ($members === []): ?>
        <p>Aucun membre pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th scope="col">Login</th>
                    <th scope="col">Inscrit le</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?= htmlspecialchars($member->login ?? 'Membre n°' . $member->id) ?></td>
                        <td><?= $member->createdAt !== null ? htmlspecialchars(date('d/m/Y', (int) strtotime($member->createdAt))) : '' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if ($pages > 1): ?>
        <nav aria-label="Pagination">
            <?php if ($page > 1): ?>
                <a href="/members?page=<?= $page - 1 ?>" class="btn btn-secondary">« Précédent</a>
            <?php endif; ?>
            <span>Page <?= $page ?> sur <?= $pages ?></span>
            <?php if ($page < $pages): ?>
                <a href="/members?page=<?= $page + 1 ?>" class="btn btn-secondary">Suivant »</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</div>
