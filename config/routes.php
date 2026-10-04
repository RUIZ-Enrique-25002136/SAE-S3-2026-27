<?php

use App\Controller\AuthController;
use App\Controller\ForgotPasswordController;
use App\Controller\HomeController;

return [
    ['GET',  '/', [HomeController::class, 'index'], 'home', true],
    ['GET',  '/legal-notice', [HomeController::class, 'legalNotice'], 'legal-notice', true],
    ['GET',  '/login', [AuthController::class, 'loginForm'], 'login', true],
    ['POST', '/login', [AuthController::class, 'login']],
    ['GET',  '/register', [AuthController::class, 'registerForm'], 'register', true],
    ['POST', '/register', [AuthController::class, 'register']],
    ['GET',  '/logout', [AuthController::class, 'logout']],
    ['POST',  '/logout', [AuthController::class, 'logout']],
    ['GET',  '/verify', [AuthController::class, 'verify']],
    ['GET',  '/forgot-password', [ForgotPasswordController::class, 'forgot']],
    ['POST', '/forgot-password', [ForgotPasswordController::class, 'forgot']],
    ['GET',  '/reset-password', [ForgotPasswordController::class, 'reset']],
    ['POST', '/reset-password', [ForgotPasswordController::class, 'reset']],
    ['GET',  '/sitemap', [HomeController::class, 'sitemap'], 'sitemap', true],
];