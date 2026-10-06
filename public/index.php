<?php

require "../../autoload.php";


if (PHP_SAPI == 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)))
    return false;

$root = dirname(__DIR__);
require $root . '/autoload.php';
Env::charger($root . '/.env');
session_start();
$pdo = Database::connexion();
$users = new UserRepository($pdo);
$view = new View($root . '/src/views', [/* user, currentPath */]);
$factories = [];
$routes = require $root . '/config/routes.php';
$router = new Router($routes, $factories, $view);
$router->dispatch(Request::createFromGlobals())->send();
$pdo = Database::connexion();
$users = new UserRepository($pdo);

$factories = [
    HomeController::class     => new HomeController(),
    AuthController::class     => new AuthController($users, $view),
    PasswordController::class => new PasswordController($users,$view),
];

$table = [];
foreach (require $root . '/config/routes.php' as $route) {
    [$verb, $url, $handler] = $route;
    $table[$url][$verb] = $handler;
}


$request = Request::createFromGlobals();
$method = $request->getMethod();
$path = $request->getPath();

if (!isset($table[$path])) {
    $response = new Response($this->render('404', ['path' => $path]), 404);
    $response->send();
    exit;
}

if (!isset($table[$path][$method])) {
    $response = new Response($this->render('405', ['path' => $path]), 405);
    $response->send();
    exit;
}

