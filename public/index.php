<?php
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
$routes = require $racine . '/config/routes.php';

$chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$chemin = rtrim($chemin, '/') ?: '/';

if (!isset($routes[$chemin])) {
    http_response_code(404);
    render('404', ['chemin' => $chemin]);
    exit;
}

require $racine . '/actions/' . $routes[$chemin] . '.php';