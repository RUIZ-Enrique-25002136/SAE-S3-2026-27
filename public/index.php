<?php

use App\Controller\AuthController;
use App\Controller\PasswordController;
use App\Controller\HomeController;
use App\Models\UserRepository;

if (PHP_SAPI == 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)))
    return false;

$root = dirname(__DIR__);

require $root . '/autoload.php';
require $root . '/includes/render.php';
require $root . '/includes/database.php';
require_once $root . '/src/views/partials/header.php';
require_once $root . '/src/views/partials/footer.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = getConnection();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$users = new UserRepository($pdo);

$factories = [
    HomeController::class           => fn() => new HomeController(),
    AuthController::class           => fn() => new AuthController($users),
    PasswordController::class => fn() => new PasswordController($users),
];

$table = [];
foreach (require $root . '/config/routes.php' as $route) {
    [$verb, $url, $handler] = $route;
    $table[$url][$verb] = $handler;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$path = rtrim($path, '/') ?: '/';

if (!isset($table[$path])) {
    http_response_code(404);
    render('404', ['path' => $path]);
    exit;
}

if (!isset($table[$path][$method])) {
    http_response_code(405);
    render('404', ['path' => $path]);
    exit;
}

[$class, $action] = $table[$path][$method];
$controller = $factories[$class]();
$controller->$action();
