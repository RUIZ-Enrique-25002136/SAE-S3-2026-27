<?php

use App\Controller\AuthController;
use App\Controller\ForgotPasswordController;
use App\Controller\HomeController;

return [
    ['GET',  '/', [HomeController::class, 'index']],
    ['GET',  '/legal-notice', [HomeController::class, 'legalNotice']],
    ['GET',  '/login', [AuthController::class, 'loginForm']],
    ['POST', '/login', [AuthController::class, 'login']],
    ['GET',  '/register', [AuthController::class, 'registerForm']],
    ['POST', '/register', [AuthController::class, 'register']],
    ['GET',  '/logout', [AuthController::class, 'logout']],
    ['GET',  '/verify', [AuthController::class, 'verify']],
    ['GET',  '/forgot-password', [ForgotPasswordController::class, 'forgot']],
    ['POST', '/forgot-password', [ForgotPasswordController::class, 'forgot']],
    ['GET',  '/reset-password', [ForgotPasswordController::class, 'reset']],
    ['POST', '/reset-password', [ForgotPasswordController::class, 'reset']],
    ['GET',  '/sitemap', [HomeController::class, 'sitemap']],
];