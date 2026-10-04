<?php

use App\Controller\AuthController;
use App\Controller\PasswordController;
use App\Controller\HomeController;

return [
    ['GET',  '/', [HomeController::class, 'index'], 'Accueil', true],
    ['GET',  '/legal-notice', [HomeController::class, 'legalNotice'], 'Mentions légales', true],
    ['GET',  '/login', [AuthController::class, 'loginForm'], 'Connexion', true],
    ['POST', '/login', [AuthController::class, 'login']],
    ['GET',  '/register', [AuthController::class, 'registerForm'], 'Inscription', true],
    ['POST', '/register', [AuthController::class, 'register']],
    ['GET',  '/logout', [AuthController::class, 'logout']],
    ['POST',  '/logout', [AuthController::class, 'logout']],
    ['GET',  '/verify', [AuthController::class, 'verify']],
    ['GET',  '/forgot-password', [PasswordController::class, 'forgot'], 'Mot de passe oublié', true],
    ['POST', '/forgot-password', [PasswordController::class, 'forgot']],
    ['GET',  '/reset-password', [PasswordController::class, 'reset']],
    ['POST', '/reset-password', [PasswordController::class, 'reset']],
    ['GET',  '/sitemap', [HomeController::class, 'sitemap'], 'Plan du site', true],
];