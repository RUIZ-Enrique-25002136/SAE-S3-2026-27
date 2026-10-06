<?php

namespace App\Core;

use App\View\Component;
use App\View\Layout;

class View
{
    public function __construct(
        private string $dossier,
        private array $partage = [],
    ) {}

    public function render(string $view, array $donnees = [], int $statut = 200): Response
    {
        $donnees += $this->partage;


        return new Response($this->capture($view . '.php', $donnees)->render(), $statut);
    }

    /** @return Component */
    private function capture(string $fichier, array $donnees): string
    {
        extract($donnees, EXTR_SKIP);
        return require $this->dossier . '/' . $fichier;
    }
}