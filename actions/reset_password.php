<?php
    use App\controllers\PasswordController;

    $pdo = getConnection();
    $controller = new PasswordController($pdo);
    $controller->reset();