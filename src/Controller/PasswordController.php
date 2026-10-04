<?php

namespace App\Controller;

use PDOException;
use App\Models\UserRepository;

/**
 * Mot de passe oublié : demande d'un lien par mail, puis choix d'un nouveau mot de passe.
 */
class PasswordController
{
    /**
     * @param UserRepository $users Accès aux comptes utilisateurs
     */
    public function __construct(private UserRepository $users) {}

    /**
     * Affiche et traite le formulaire « mot de passe oublié » (GET et POST /forgot-password).
     * Le même message est affiché que l'email existe ou non, pour ne pas révéler les comptes.
     *
     * @return void
     */
    public function forgot(): void
    {
        $siteUrl = env('SITE_URL', 'https://beghin.alwaysdata.net');
        $errors = [];
        $success = false;

        // si la page est rejoint depuis un formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!checkCsrf()) { $errors[] = 'Session expirée, veuillez réessayer.'; }
            // récupére l'email et enléve les espaces inutiles
            $email = trim($_POST['email']);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email n'est pas valide";
            } elseif (empty($errors)) {
                try {
                    //crée un token a partir de 32 bit aléatoire puis le converti en hexadecimal pour être utilisable
                    $token = bin2hex(random_bytes(32));
                    //si le token est bien rentrée dans la table envoie un lien vers la pages de reset_password avec le token dans l'URL
                    if ($this->users->setToken($email, $token)) {
                        $link = $siteUrl . '/reset-password?token=' . $token;
                        mail($email, 'Pour réinitialiser ton mot de passe', 'Clique ici : ' . $link);
                    }
                    $success = true;
                } catch (PDOException|\Random\RandomException $e) {
                    $errors[] = 'Une erreur s\'est produite';
                }
            }
        }

        buildHeader('Mot de passe oublié');
        //affiche la vue
        render('forgot_password', ['errors' => $errors, 'success' => $success]);
        buildFooter();
    }

    /**
     * Affiche et traite le formulaire de nouveau mot de passe (GET et POST /reset-password).
     *
     * @return void
     */
    public function reset(): void
    {
        $errors = [];
        $success = false;
        $token = $_POST['token'] ?? $_GET['token'] ?? '';

        // si la page est rejoint depuis un formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!checkCsrf()) { $errors[] = 'Session expirée, veuillez réessayer.'; }
            $password = $_POST['password'];
            $confirmation = $_POST['confirmation'];

            if (strlen($password) < 8) {
                $errors[] = 'Le mot de passe doit contenir au moins 8 caractére';
            }

            if ($password !== $confirmation) {
                $errors[] = 'Les mots de passe ne correspondent pas';
            }

            if (empty($errors)) {
                try {
                    if ($this->users->resetPassword($token, $password)) {
                        $success = true;
                    } else {
                        $errors[] = 'Lien invalide';
                    }
                } catch (PDOException $e) {
                    $errors[] = 'Une erreur s\'est produite';
                }
            }
        }

        // n'est vrais que si le formulaire n'a pas encore été validé et que le token est valide
        $isTokenValid = !$success && $this->users->tokenExists($token);

        buildHeader('Nouveau mot de passe');
        // affiche la vue
        render('reset_password', ['errors' => $errors, 'success' => $success, 'isTokenValid' => $isTokenValid, 'token' => $token]);
        buildFooter();
    }
}