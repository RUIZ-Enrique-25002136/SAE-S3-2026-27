<?php

namespace App\Core;

use App\View\Component;
use App\View\Layout;
use Closure;

class View
{
    public function __construct(
        private string $dossier,
        private array $partage = [],
    ) {}

    public function render(string $view, array $donnees = [], int $statut = 200): Response
    {
        $donnees += $this->partage;
        $resultat = $this->capture($view . '.php', $donnees);

        if ($resultat instanceof Component) {
            return new Response($resultat->render(), $statut);
        }

        $html = (string) $this->capture('layout.php', $donnees + ['content' => $resultat]);


        return new Response($html, $statut);
    }

    /** @return Component */
    private function capture(string $fichier, array $donnees): string
    {
        extract($donnees, EXTR_SKIP);
        ob_start();
        $resultat = require $this->dossier . '/' . $fichier;
        $html = (string) ob_get_clean();

        return $result instanceof Component ? $resultat : $html;
    }
}