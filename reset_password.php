<?php
    require_once 'includes/footer.php';
    require_once 'controllers/ForgotPasswordController.php';

    $pdo = connexion();
    $controller = new ForgotPasswordController($pdo);
    $controller->reset();