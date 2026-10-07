<?php

namespace App\Controller;

use App\Models\User;
use App\Models\UserRepository;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Csrf;
use App\Core\Env;

/**
 * Inscription, connexion, déconnexion et confirmation de l'adresse email.
 */
class AuthController
{
    /**
     * @param UserRepository $users Accès aux comptes utilisateurs
     * @param View           $view  Moteur de templates
     */
    public function __construct(
        private UserRepository $users,
        private View $view,
    ) {}

    /**
     * Affiche le formulaire de connexion (GET /login).
     */
    public function loginForm(Request $request): Response
    {
        return $this->showLogin([], false, '');
    }

    /**
     * Traite le formulaire de connexion (POST /login) : jeton CSRF, email, mot de passe
     * et compte vérifié, puis ouvre la session avec un nouvel identifiant.
     */
    public function login(Request $request): Response
    {
        $email = trim((string) $request->get('email', ''));
        $password = (string) $request->get('password', '');
        $errors = [];
        $success = false;

        if (!Csrf::check()) {
            $errors[] = 'Session expirée, veuillez réessayer.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Adresse mail non valide.';
        }

        if (empty($errors)) {
            $user = $this->users->findByEmail($email);
            if ($user !== null && $user->verifyPassword($password)) {
                if (!$user->verified) {
                    $errors[] = 'Veuillez confirmer votre email avant de vous connecter.';
                } else {
                    session_regenerate_id(true);
                    $success = true;
                    $_SESSION['user'] = [
                        'id'    => $user->id,
                        'email' => $user->email,
                    ];
                }
            } else {
                $errors[] = 'Email ou mot de passe incorrect.';
            }
        }

        return $this->showLogin($errors, $success, $email);
    }

    /**
     * Prépare la réponse de la page de connexion.
     *
     * @param string[] $errors  Messages d'erreur à afficher
     * @param bool     $success true si la connexion a réussi
     * @param string   $email   Email à pré-remplir dans le formulaire
     */
    private function showLogin(array $errors, bool $success, string $email): Response
    {
        return $this->view->render('login', [
            'errors'  => $errors,
            'success' => $success,
            'email'   => $email,
            'title'   => 'Connexion',
            'description' => 'Connectez-vous à votre compte SAE S3 pour accéder à votre espace.',

        ]);
    }

    /**
     * Affiche le formulaire d'inscription (GET /register).
     */
    public function registerForm(Request $request): Response
    {
        return $this->showRegister([], false, '', '');
    }

    /**
     * Traite le formulaire d'inscription (POST /register) : crée le compte
     * et envoie le lien de confirmation par mail.
     */
    public function register(Request $request): Response
    {
        $email = trim((string) $request->get('email', ''));
        $login = trim((string) $request->get('login', ''));
        $password = (string) $request->get('password', '');
        $confirmation = (string) $request->get('confirmation', '');
        $errors = [];
        $success = false;

        if (!Csrf::check()) {
            $errors[] = 'Session expirée, veuillez réessayer.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }
        if ($password !== $confirmation) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }

        if (empty($errors)) {
            if ($this->users->emailExists($email)) {
                $errors[] = 'Cet email est déjà utilisé.';
            } elseif ($this->users->loginExists($login)) {
                $errors[] = 'Ce login est déjà utilisé.';
            } else {
                $token = bin2hex(random_bytes(32));
                $created = $this->users->create($email, $login, $password, $token);
                if ($created) {
                    $siteUrl = Env::get('SITE_URL', 'https://beghin.alwaysdata.net');
                    $link = $siteUrl . '/verify?token=' . $token;
                    mail($email, 'Confirme ton email', 'Clique ici : ' . $link);
                    $success = true;
                } else {
                    $errors[] = 'Une erreur est survenue.';
                }
            }
        }

        return $this->showRegister($errors, $success, $email, $login);
    }

    /**
     * Prépare la réponse de la page d'inscription.
     *
     * @param string[] $errors  Messages d'erreur à afficher
     * @param bool     $success true si le compte a été créé
     * @param string   $email   Email à pré-remplir dans le formulaire
     * @param string   $login   Login à pré-remplir dans le formulaire
     */
    private function showRegister(array $errors, bool $success, string $email, string $login): Response
    {
        return $this->view->render('register', [
            'errors'  => $errors,
            'success' => $success,
            'email'   => $email,
            'login'   => $login,
            'title'   => 'Inscription',
        ]);
    }

    /**
     * Ferme la session puis redirige vers l'accueil (GET ou POST /logout).
     */
    public function logout(Request $request): Response
    {
        $_SESSION = [];
        session_destroy();
        return Response::redirect('/');
    }

    /**
     * Confirme l'adresse email à partir du jeton du lien (GET /verify?token=...).
     */
    public function verify(Request $request): Response
    {
        $token = (string) $request->get('token', '');
        $success = $token !== '' && $this->users->verifyEmail($token);

        return $this->view->render('verify', [
            'success' => $success,
            'title'   => 'Vérification du compte',
        ]);
    }
}