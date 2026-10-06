<?php

namespace App\Core;

class View
{
    public function __construct(
        private string $dossier,
        private array $partage = [],
    ) {}

    public function render(string $view, array $donnees = [], int $statut = 200): Response
    {
        $donnees += $this->partage;

        $content = $this->capture($view . '.php', $donnees);
        $html = $this->capture('layout.php', $donnees + ['content' => $content]);

        return new Response($statut, $html);
    }

    private function capture(string $fichier, array $donnees): string
    {
        extract($donnees, EXTR_SKIP);
        ob_start();
        require $this->dossier . '/' . $fichier;

        return ob_get_clean();
    }
}