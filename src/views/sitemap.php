<?php
/** @var array<string, array{title: string, action?: string, sitemap?: bool}> $pages */
?>
<div class="card sitemap-card">
    <div class="sitemap-header">
        <h1>Plan du site</h1>
        <p class="sitemap-subtitle">Retrouvez l'ensemble des pages accessibles et l'arborescence de l'application.</p>
    </div>

    <nav class="sitemap-nav" aria-label="Plan du site">
        <ul class="sitemap-grid">
            <?php foreach ($pages as $url => $page): ?>
                <li>
                    <a href="<?= htmlspecialchars($url) ?>" class="sitemap-item">
                        <div class="sitemap-item-content">
                            <span class="sitemap-item-title"><?= htmlspecialchars($page['title']) ?></span>
                            <span class="sitemap-item-url"><code><?= htmlspecialchars($url) ?></code></span>
                        </div>
                        <span class="sitemap-item-arrow" aria-hidden="true">&rarr;</span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="card-actions">
        <a href="/" class="btn btn-secondary">Retour à l'accueil</a>
    </div>
</div>
