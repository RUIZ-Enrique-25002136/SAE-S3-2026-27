<?php

namespace controllers;

use models\UserRepository;
use PDO;
use PDOException;

require_once 'includes/header.php';
require_once 'includes/footer.php';

class ForgotPasswordController
{
    private UserRepository $users;

    public function __construct(PDO $pdo)
    {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->users = new UserRepository($pdo);
    }

    public function forgot(): void
    {
        $config = parse_ini_file(__DIR__ . '/.env');
        $siteUrl = $config['SITE_URL'] ?? 'https://beghin.alwaysdata.net';
        $erreurs = [];
        $succes = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreurs[] = "L'email n'est pas valide";
            } else {
                try {
                    $token = bin2hex(random_bytes(32));
                    if ($this->users->setToken($email, $token)) {
                        $link = $siteUrl . '/reset_password.php?token=' . $token;
                        mail($email, 'Pour réinitialiser ton mot de passe', 'Clique ici : ' . $link);
                    }
                    $succes = true;
                } catch (PDOException|\Random\RandomException $e) {
                    $erreurs[] = 'Une erreur s\'est produite';
                }
            }
        }

        buildHeader();
        require 'views/forgot_password_view.php';
        buildFooter();
    }

    public function reset(): void
    {
        $erreurs = [];
        $succes = false;
        $token = $_POST['token'] ?? $_GET['token'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];
            $confirmation = $_POST['confirmation'];

            if (strlen($password) < 8) {
                $erreurs[] = 'Le mot de passe doit contenir au moins 8 caractére';
            }

            if ($password !== $confirmation) {
                $erreurs[] = 'Les mots de passe ne correspondent pas';
            }

            if (empty($erreurs)) {
                try {
                    if ($this->users->resetPassword($token, $password)) {
                        $succes = true;
                    } else {
                        $erreurs[] = 'Lien invalide';
                    }
                } catch (PDOException $e) {
                    $erreurs[] = 'Une erreur s\'est produite';
                }
            }
        }
        $tokenValide = !$succes && $this->users->tokenExists($token);

        buildHeader();
        require 'views/reset_password_view.php';
        buildFooter();
    }
}