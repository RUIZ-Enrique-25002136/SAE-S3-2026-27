<?php

use App\controllers\AuthController;
use App\controllers\ForgotPasswordController;
use App\controllers\HomeController;
use App\models\UserRepository;

if (PHP_SAPI == 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)))
    return false;

$racine = dirname(__DIR__);

require $racine . '/autoload.php';
require $racine . '/includes/render.php';
require $racine . '/includes/connnexion_db.php';
require_once $racine . '/src/views/partials/header.php';
require_once $racine . '/src/views/partials/footer.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = connexion();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$users = new UserRepository($pdo);

$constructeurs = [
    HomeController::class           => fn() => new HomeController(),
    AuthController::class           => fn() => new AuthController($users),
    ForgotPasswordController::class => fn() => new ForgotPasswordController($users),
];

$routes = require $racine . '/config/routes.php';

$methode = $_SERVER['REQUEST_METHOD'];
$chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$chemin = rtrim($chemin, '/') ?: '/';

if (!isset($routes[$chemin])) {
    http_response_code(404);
    render('404', ['chemin' => $chemin]);
    exit;
}

if (!isset($routes[$chemin][$methode])) {
    http_response_code(405);
    render('404', ['chemin' => $chemin]);
    exit;
}

[$classe, $action] = $routes[$chemin][$methode];
$controller = $factories[$classe]();
$controller->$action();
