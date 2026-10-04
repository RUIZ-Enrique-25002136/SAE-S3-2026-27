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
        $routes = require dirname(__DIR__) . '/config/routes.php';
        $pages = array_filter($routes, fn(array $route): bool => $route['sitemap']);

        buildHeader('Plan du site');
        render('sitemap', ['pages' => $pages]);
        buildFooter();
    }
}