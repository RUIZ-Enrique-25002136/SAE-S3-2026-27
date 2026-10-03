<?php
    use App\controllers\ForgotPasswordController;
    require_once 'includes/connnexion_db.php';

    $pdo = connexion();
    $controller = new ForgotPasswordController($pdo);
    $controller->reset();