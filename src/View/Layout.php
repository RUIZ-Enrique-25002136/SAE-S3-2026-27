<?php

namespace App\View;

final class Layout extends Component
{
    /**
     * @param array{id?: int, email: string}|null $user
     * @param array<Component|string> $children
     */
    public function __construct(
        private readonly string $title,
        private readonly ?array $user,
        private readonly array $children,
        private readonly string $currentPath,
    ) {
    }

    public function render(): string
    {
        $title = $this->e($this->title);
        $nav = $this->renderNav();
        $content = $this->renderAll($this->children);

        return <<<HTML
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>{$title} - SAE S3</title>
                <link rel="stylesheet" href="/css/style.css">
                <link rel="icon" type="image/x-icon" href="/assets/logo.png">
            </head>
            <body>
            <header class="site-header">
                <div class="nav-container">
                    <a href="/" class="brand">SAE S3</a>
                    {$nav}
                </div>
            </header>
            <main class="main-content">
                {$content}
            </main>
            <footer class="site-footer">
                <p> Nos réseaux :</p>
                <a href="https://x.com"><img class="reseaux" src="/assets/x.svg" alt="Notre page X"></a>
                <a href="https://facebook.com"><img class="reseaux" src="/assets/facebook.svg" alt="Notre page Facebook"></a>
                <a href="https://instagram.com"><img class="reseaux" src="/assets/instagram.svg" alt="Notre page Instagram"></a><br>
                <a href="/legal-notice">Notice légale</a>
                <a href="/sitemap">Plan du site</a>
            </footer>
            </body>
            </html>

            HTML;
    }

    private function renderNav(): string
    {
        $current = parse_url($currentPath ?? '/', PHP_URL_PATH);

        if ($this->user === null) {
            $links = $this->link('/', 'Accueil', 'nav-item', $current)
                . $this->link('/login', 'Connexion', 'nav-item', $current)
                . $this->link('/register', 'Inscription', 'btn-register', $current);
        } else {
            $links = $this->link('/', 'Accueil', 'nav-item', $current)
                . '<span class="user-badge">' . $this->e($this->user['email']) . '</span>'
                . $this->link('/members', 'Membres', 'nav-item', $current)
                . $this->link('/account', 'Mon compte', 'nav-item', $current)
                . '<a href="/logout" class="nav-item nav-logout">Déconnexion</a>';
        }

        return '<nav class="nav-links">' . $links . '</nav>';
    }

    private function link(string $path, string $label, string $class, ?string $current): string
    {
        $active = $path === $current ? ' active' : '';

        return '<a href="' . $this->e($path) . '" class="' . $class . $active . '">'
            . $this->e($label) . '</a>';
    }
}