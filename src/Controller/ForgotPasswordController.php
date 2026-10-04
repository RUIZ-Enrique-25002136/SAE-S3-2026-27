<?php

namespace App\Controller;
require_once  __DIR__ . '/../views/partials/header.php';
require_once  __DIR__ . '/../views/partials/footer.php';

use PDOException;
use App\models\UserRepository;



class ForgotPasswordController
{
    // intitiallise la variable pdo pour quelle puisse catch les erreur et initiallise l'objet user
    public function __construct(PDO $pdo) {}

    public function forgot(): void
    {
        //load les variables d'environnements
        $config = parse_ini_file(__DIR__ . '/../../.env');
        $siteUrl = $config['SITE_URL'] ?? 'https://beghin.alwaysdata.net';
        $erreurs = [];
        $succes = false;

        // si la page est rejoint depuis un formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // récupére l'email et enléve les espaces inutiles
            $email = trim($_POST['email']);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreurs[] = "L'email n'est pas valide";
            } else {
                try {
                    //crée un token a partir de 32 bit aléatoire puis le converti en hexadecimal pour être utilisable
                    $token = bin2hex(random_bytes(32));
                    //si le token est bien rentrée dans la table envoie un lien vers la pages de reset_password avec le token dans l'URL
                    if ($this->users->setToken($email, $token)) {
                        $link = $siteUrl . '/reset-password?token=' . $token;
                        mail($email, 'Pour réinitialiser ton mot de passe', 'Clique ici : ' . $link);
                    }
                    $succes = true;
                } catch (PDOException|\Random\RandomException $e) {
                    $erreurs[] = 'Une erreur s\'est produite';
                }
            }
        }

        buildHeader();
        //affiche la vue
        render('forgotPassword', ['erreurs' => $erreurs, 'succes' => $succes]);
        buildFooter();
    }

    public function reset(): void
    {
        $erreurs = [];
        $succes = false;
        $token = $_POST['token'] ?? $_GET['token'] ?? '';

        // si la page est rejoint depuis un formulaire
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

        // n'est vrais que si le formulaire n'a pas encore été validé et que le token est valide
        $tokenValide = !$succes && $this->users->tokenExists($token);

        buildHeader();
        // affiche la vue
        render('resetPassword', ['erreurs' => $erreurs, 'succes' => $succes, 'tokenValide' => $tokenValide, 'token' => $token]);
        buildFooter();
    }
}