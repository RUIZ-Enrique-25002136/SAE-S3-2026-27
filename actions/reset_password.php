<?php
    use App\controllers\ForgotPasswordController;

    $pdo = connexion();
    $controller = new ForgotPasswordController($pdo);
    $controller->reset();