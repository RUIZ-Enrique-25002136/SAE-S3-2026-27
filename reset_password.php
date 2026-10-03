<?php
    require_once 'includes/connnexion_db.php';
    require_once 'controllers/ForgotPasswordController.php';

    $pdo = connexion();
    $controller = new ForgotPasswordController($pdo);
    $controller->reset();