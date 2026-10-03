<?php
require_once __DIR__ . '/src/views/partials/header.php';
require_once __DIR__ . '/src/views/partials/footer.php';
require_once __DIR__ . '/includes/render.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$utilisateur = $_SESSION['utilisateur'] ?? '';

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    header('Location: logout.php');
}

$titre = 'Accueil';
buildHeader($titre);

render('index');
buildfooter();
