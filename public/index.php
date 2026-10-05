<?php

use App\Controller\AuthController;
use App\Controller\PasswordController;
use App\Controller\HomeController;
use App\Models\UserRepository;

if (PHP_SAPI == 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)))
    return false;

$root = dirname(__DIR__);

require $root . '/autoload.php';
require $root . '/includes/env.php';
require $root . '/includes/render.php';
require $root . '/includes/database.php';
require $root . '/includes/csrf.php';
require_once $root . '/src/views/partials/header.php';
require_once $root . '/src/views/partials/footer.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = getConnection();
$users = new UserRepository($pdo);

$factories = [
    HomeController::class     => fn() => new HomeController(),
    AuthController::class     => fn() => new AuthController($users),
    PasswordController::class => fn() => new PasswordController($users),
];

$table = [];
foreach (require $root . '/config/routes.php' as $route) {
    [$verb, $url, $handler] = $route;
    $table[$url][$verb] = $handler;
}

use App\Core\Request;
use App\Core\Response;

$request = Request::createFromGlobals();
$method = $request->getMethod();
$path = $request->getPath();

if (!isset($table[$path])) {
    $response = new Response(render('404', ['path' => $path]), 404);
    $response->send();
    exit;
}

if (!isset($table[$path][$method])) {
    $response = new Response(render('405', ['path' => $path]), 405);
    $response->send();
    exit;
}

[$class, $action] = $table[$path][$method];
$controller = $factories[$class]();

$response = $controller->$action($request);
$response->send();