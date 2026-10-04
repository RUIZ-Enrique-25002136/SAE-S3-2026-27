<?php

namespace App\Controller;
require_once  __DIR__ . '/../views/partials/header.php';
require_once  __DIR__ . '/../views/partials/footer.php';

class HomeController {
    public function index() {
        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
            header('Location: /logout');
            exit;
        }

        buildHeader('Accueil');
        render('home');
        buildfooter();

    }

    public function legalNotice() {
        buildHeader('Mention légales');
        render('legal-notice');
        buildFooter();
    }

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