<?php
/** @var array<string, array{title: string, action?: string, sitemap?: bool}> $pages */
?>
<div class="card sitemap-card">
    <h1>Plan du site</h1>
    <p class="sitemap-intro">Retrouvez l'ensemble des pages accessibles du site SAE S3.</p>

    <ul class="sitemap-list">
        <?php foreach ($pages as $url => $page): ?>
            <li>
                <a href="<?= htmlspecialchars($url) ?>">
                    <span class="sitemap-title"><?= htmlspecialchars($page['title']) ?></span>
                    <span class="sitemap-url"><?= htmlspecialchars($url) ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="card-actions">
        <a href="/" class="btn btn-secondary">Retour à l'accueil</a>
    </div>
</div>
