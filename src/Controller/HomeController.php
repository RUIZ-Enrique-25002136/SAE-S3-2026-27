<?php

namespace App\Controller;
require_once  __DIR__ . '/../views/partials/header.php';
require_once  __DIR__ . '/../views/partials/footer.php';

/**
 * Pages publiques : accueil, mentions légales et plan du site.
 */
class HomeController {
    /**
     * Affiche la page d'accueil (GET /).
     *
     * @return void
     */
    public function index() {
        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
            header('Location: /logout');
            exit;
        }

        buildHeader('Accueil');
        render('home');
        buildfooter();

    }

    /**
     * Affiche les mentions légales (GET /legal-notice).
     *
     * @return void
     */
    public function legalNotice() {
        buildHeader('Mentions légales');
        render('legal_notice');
        buildFooter();
    }

    /**
     * Affiche le plan du site, généré à partir des routes GET marquées comme visibles.
     *
     * @return void
     */
    public function sitemap() {
        $pages = [];
        foreach (require dirname(__DIR__, 2) . '/config/routes.php' as $route) {
            if ($route[0] === 'GET' && ($route[4] ?? false)) {
                $pages[$route[1]] = ['title' => $route[3]];
            }
        }

        buildHeader('Plan du site');
        render('sitemap', ['pages' => $pages]);
        buildFooter();
    }
}