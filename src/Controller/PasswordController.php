<?php

namespace App\Controller;

use PDOException;
use App\Models\UserRepository;
use App\Core\Request;
use App\Core\Response;

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
     * @param Request $request
     * @return Response
     */
    public function forgot(Request $request): Response
    {
        $siteUrl = env('SITE_URL', 'https://beghin.alwaysdata.net');
        $errors = [];
        $success = false;

        // Si le formulaire a été soumis
        if ($request->isPost()) {
            if (!checkCsrf()) {
                $errors[] = 'Session expirée, veuillez réessayer.';
            }
            $email = trim((string) $request->get('email', ''));

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email n'est pas valide";
            } elseif (empty($errors)) {
                try {
                    $token = bin2hex(random_bytes(32));
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

        $html = render('forgot_password', ['errors' => $errors, 'success' => $success], 'Mot de passe oublié');
        return new Response($html);
    }

    /**
     * Affiche et traite le formulaire de nouveau mot de passe (GET et POST /reset-password).
     *
     * @param Request $request
     * @return Response
     */
    public function reset(Request $request): Response
    {
        $errors = [];
        $success = false;

        $token = (string) $request->get('token', '');

        if ($request->isPost()) {
            if (!checkCsrf()) {
                $errors[] = 'Session expirée, veuillez réessayer.';
            }

            $password = (string) $request->get('password', '');
            $confirmation = (string) $request->get('confirmation', '');

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
        $isTokenValid = !$success && $this->users->tokenExists($token);
        $html = render('reset_password', [
            'errors'       => $errors,
            'success'      => $success,
            'isTokenValid' => $isTokenValid,
            'token'        => $token,
        ], 'Nouveau mot de passe');

        return new Response($html);
    }
}