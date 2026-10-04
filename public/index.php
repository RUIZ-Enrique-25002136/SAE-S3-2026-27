<?php
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
$routes = require $root . '/config/routes.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$path = rtrim($path, '/') ?: '/';

if (!isset($routes[$path])) {
    http_response_code(404);
    render('404', ['path' => $path]);
    exit;
}

require $root . '/actions/' . $routes[$path]['action'] . '.php';