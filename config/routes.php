<?php

use App\Controller\AuthController;
use App\Controller\ForgotPasswordController;
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
    ['GET',  '/forgot-password', [ForgotPasswordController::class, 'forgot'], 'Mot de passe oubliée', true],
    ['POST', '/forgot-password', [ForgotPasswordController::class, 'forgot']],
    ['GET',  '/reset-password', [ForgotPasswordController::class, 'reset']],
    ['POST', '/reset-password', [ForgotPasswordController::class, 'reset']],
    ['GET',  '/sitemap', [HomeController::class, 'sitemap'], 'Plan du site', true],
];