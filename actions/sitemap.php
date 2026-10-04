<?php

$routes = require dirname(__DIR__) . '/config/routes.php';
$pages = array_filter($routes, fn(array $route): bool => $route['sitemap']);

buildHeader('Plan du site');
render('sitemap', ['pages' => $pages]);
buildFooter();
