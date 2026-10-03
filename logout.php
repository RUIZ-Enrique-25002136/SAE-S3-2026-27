<?php
require_once __DIR__ . '/src/views/partials/header.php';
require_once __DIR__ . '/src/views/partials/footer.php';
session_start();
$_SESSION = [];
session_destroy();
buildHeader('Déconnexion');
buildFooter();
