<?php

$user = $_SESSION['user'] ?? '';

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    header('Location: /logout');
    exit;
}

$title = 'Accueil';
buildHeader($title);

render('home');
buildfooter();
