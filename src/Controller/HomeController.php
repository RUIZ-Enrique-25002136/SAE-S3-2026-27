<?php

namespace App\Controller;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;

class HomeController
{

    public function __construct(private View $view)
    {
        $this->view = new View(dirname(__DIR__, 2) . '/templates');
    }

    public function index(Request $request): Response
    {
        return $this->view->render('home', ['title' => 'Accueil']);
    }

    public function legalNotice(Request $request): Response
    {
        return $this->view->render('legal_notice', ['title' => 'Mentions légales']);
    }

    public function sitemap(Request $request): Response
    {
        $pages = [];
        foreach (require dirname(__DIR__, 2) . '/config/routes.php' as $route) {
            if ($route[0] === 'GET' && ($route[4] ?? false)) {
                $pages[$route[1]] = ['title' => $route[3]];
            }
        }

        return $this->view->render('sitemap', [
            'pages' => $pages,
            'title' => 'Plan du site',
        ]);
    }
}