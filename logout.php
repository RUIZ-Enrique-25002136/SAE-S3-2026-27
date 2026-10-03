<?php
require_once 'includes/header.php';
require_once 'includes/footer.php';
session_start();
$_SESSION = [];
session_destroy();
buildHeader('Déconnexion');
echo <<< HTML
<h1>Vous vous êtes bien déconnecté.<h1> 
HTML;
buildFooter();
