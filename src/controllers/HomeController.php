<?php

namespace App\controllers;
require_once  __DIR__ . '/../views/partials/header.php';
require_once  __DIR__ . '/../views/partials/footer.php';

use PDO;
use PDOException;
use App\models\UserRepository;

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
        render('legalNotice');
        buildFooter();
    }
}