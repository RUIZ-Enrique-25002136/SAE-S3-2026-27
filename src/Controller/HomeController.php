<?php

namespace App\Controller;

use App\Core\Request;
use App\Core\Response;

/**
 * Pages publiques : accueil, mentions légales et plan du site.
 */
class HomeController {
    /**
     * Affiche la page d'accueil (GET /).
     */
    public function index(Request $request): Response {
        return new Response(render('home', [], 'Accueil'));
    }

    /**
     * Affiche les mentions légales (GET /legal-notice).
     */
    public function legalNotice(Request $request): Response {
        return new Response(render('legal_notice', [], 'Mentions légales'));
    }

    /**
     * Affiche le plan du site, généré à partir des routes GET marquées comme visibles.
     */
    public function sitemap(Request $request): Response {
        $pages = [];
        foreach (require dirname(__DIR__, 2) . '/config/routes.php' as $route) {
            if ($route[0] === 'GET' && ($route[4] ?? false)) {
                $pages[$route[1]] = ['title' => $route[3]];
            }
        }

        return new Response(render('sitemap', ['pages' => $pages], 'Plan du site'));
    }
}
      