<?php

namespace App\Controller;

use PDOException;
use Random\RandomException;
use App\Models\UserRepository;
use App\Core\Request;
use App\Core\Response;
use App\Core\Csrf;
use App\Core\View;
use App\Core\Env;

/**
 * Mot de passe oublié : demande d'un lien par mail, puis choix d'un nouveau mot de passe.
 */
class PasswordController
{
    /**
     * @param UserRepository $users Accès aux comptes utilisateurs
     * @param View           $view  Moteur de templates
     */
    public function __construct(
        private UserRepository $users,
        private View $view,
    ) {}

    public function forgot(Request $request): Response
    {
        $siteUrl = Env::get('SITE_URL', 'https://beghin.alwaysdata.net');
        $errors = [];
        $success = false;

        if ($request->isPost()) {
            $email = trim((string) $request->get('email', ''));

            if (!Csrf::check()) {
                $errors[] = 'Session expirée, veuillez réessayer.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email n'est pas valide";
            } else {
                try {
                    $token = bin2hex(random_bytes(32));
                    if ($this->users->setToken($email, $token)) {
                        $link = $siteUrl . '/reset-password?token=' . $token;
                        mail($email, 'Pour réinitialiser ton mot de passe', 'Clique ici : ' . $link);
                    }
                    $success = true;
                } catch (PDOException|RandomException $e) {
                    $errors[] = 'Une erreur s\'est produite';
                }
            }
        }

        return $this->view->render('forgot_password', [
            'errors'  => $errors,
            'success' => $success,
            'title'   => 'Mot de passe oublié',
            'noindex' => true,
        ]);
    }

    public function reset(Request $request): Response
    {
        $errors = [];
        $success = false;

        $token = (string) $request->get('token', '');

        if ($request->isPost()) {
            if (!Csrf::check()) {
                $errors[] = 'Session expirée, veuillez réessayer.';
            }

            $password = (string) $request->get('password', '');
            $confirmation = (string) $request->get('confirmation', '');

            if (strlen($password) < 8) {
                $errors[] = 'Le mot de passe doit contenir au moins 8 caractères';
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

        $isTokenValid = !$success && $this->users->tokenExists($token);

        return $this->view->render('reset_password', [
            'errors'       => $errors,
            'success'      => $success,
            'isTokenValid' => $isTokenValid,
            'token'        => $token,
            'title'        => 'Nouveau mot de passe',
            'noindex'      => true,
        ]);
    }
}