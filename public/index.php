<?php

use App\Controller\AuthController;
use App\Controller\PasswordController;
use App\Controller\HomeController;
use App\Models\UserRepository;
use App\Core\Database;
use App\Core\Env;
use App\Core\View;
use App\Core\Router;
use App\Core\Request;
use App\Core\Response;


if (PHP_SAPI == 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

$root = dirname(__DIR__);
require $root . '/autoload.php';
Env::charger($root . '/.env');
session_start();

$request = Request::createFromGlobals();
$users = new UserRepository(Database::connexion());
$view = new View($root . '/src/views', [
    'user'        => $_SESSION['user'] ?? null,
    'currentPath' => $request->getPath(),
]);


$factories = [
    HomeController::class     => fn() => new HomeController($view),
    AuthController::class     => fn() => new AuthController($users, $view),
    PasswordController::class => fn() => new PasswordController($users, $view),
];
$router = new Router(require $root . '/config/routes.php', $factories, $view);

$table = [];
foreach (require $root . '/config/routes.php' as $route) {
    [$verb, $url, $handler] = $route;
    $table[$url][$verb] = $handler;
}


$request = Request::createFromGlobals();
$router->dispatch($request)->send();



