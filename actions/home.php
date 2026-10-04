<?php

$utilisateur = $_SESSION['utilisateur'] ?? '';

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    header('Location: /logout');
    exit;
}

$titre = 'Accueil';
buildHeader($titre);

render('home');
buildfooter();
