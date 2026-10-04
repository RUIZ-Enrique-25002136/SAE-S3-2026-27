<?php
/** @var array<string, array{action: string, title: string, sitemap: bool}> $pages */
?>
<div class="card mentions-card">
    <h1>Plan du site</h1>

    <nav class="mentions-section" aria-label="Plan du site">
        <ul>
            <?php foreach ($pages as $url => $page): ?>
                <li><a href="<?= htmlspecialchars($url) ?>"><?= htmlspecialchars($page['title']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="card-actions">
        <a href="/" class="btn btn-secondary">Retour à l'accueil</a>
    </div>
</div>
