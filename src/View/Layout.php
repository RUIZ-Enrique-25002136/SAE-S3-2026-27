<?php

namespace App\View;

final class Layout extends Component
{
    public function __construct(
        private string $title,
        private ?array $user,
        private array $children,
    )
    {}
    public function render(): string
    {
        $title = htmlspecialchars($this->title);

        return '<!DOCTYPE html>'
            . '<html lang="fr">'
            . '<head>'
            . '<meta charset="utf-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1>'
            . '<title>' . $title . '</title>'
            . '</head>'
            . '<body>'
            . $this->renderNav()
            . '<main>'
            . '<h1>' . $title . '</h1>'
            . $this->renderAll($this->children)
            . '</main>'
            . '</body>'
            . '</html>';

    }

    private function renderNav(): string
    {
        $current = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        $links = $this->user === null
            ? ['/' => 'Accueil', '/connexion' => 'Connexion', '/inscription' => 'Inscription']
            : ['/' => 'Accueil', '/membres' => 'Membres', '/compte' => 'Mon compte', '/deconnexion' => 'Déconnexion'];

        $items = '';
        foreach ($links as $path => $label) {
            $class = $path === $current ? ' class="active"' : '';
            $items .= '<li><a href="' . $this->e($path) . '"' . $class . '>' . $this->e($label) . '</a></li>';
        }

        return '<nav><ul>' . $items . '</ul></nav>';
    }
}