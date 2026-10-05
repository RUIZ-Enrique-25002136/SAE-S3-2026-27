<?php

namespace App\Controller;

use App\Models\UserRepository;
use App\Core\Request;
use App\Core\Response;

/**
 * Inscription, connexion, déconnexion et confirmation de l'adresse email.
 */
class AuthController {
    /**
     * @param UserRepository $users Accès aux comptes utilisateurs
     */
    public function __construct(private UserRepository $users) {}

    /**
     * Affiche le formulaire de connexion (GET /login).
     *
     * @param Request $request
     * @return Response
     */
    public function loginForm(Request $request): Response
    {
        return new Response($this->showLogin([], false, ''));
    }

    /**
     * Traite le formulaire de connexion (POST /login) : jeton CSRF, email, mot de passe
     * et compte vérifié, puis ouvre la session avec un nouvel identifiant.
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request): Response {
        $email = trim((string) $request->get('email', ''));
        $password = (string) $request->get('password', '');
        $errors = [];

        if (!checkCsrf()) {
            $errors[] = 'Session expirée, veuillez réessayer.';
        }
        $success = false;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "adresse mail non valide";
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
                        'id' => $user->id,
                        'email' => $user->email,
                    ];
                }
            } else {
                $errors[] = 'email ou mot de passe incorrect';
            }
        }

        return new Response($this->showLogin($errors, $success, $email));
    }

    /**
     * Prépare le HTML de la page de connexion.
     *
     * @param string[] $errors  Messages d'erreur à afficher
     * @param bool     $success true si la connexion a réussi
     * @param string   $email   Email à pré-remplir dans le formulaire
     * @return string
     */
    private function showLogin(array $errors, bool $success, string $email): string {
        return render('login', [
            'errors'  => $errors,
            'success' => $success,
            'email'   => $email,
        ], 'Connexion');
    }

    /**
     * Affiche le formulaire d'inscription (GET /register).
     *
     * @param Request $request
     * @return Response
     */
    public function registerForm(Request $request): Response {
        return new Response($this->showRegister([], false, ''));
    }

    /**
     * Traite le formulaire d'inscription (POST /register) : crée le compte
     * et envoie le lien de confirmation par mail.
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response {
        $email = trim((string) $request->get('email', ''));
        $password = (string) $request->get('password', '');
        $confirmation = (string) $request->get('confirmation', '');
        $errors = [];

        if (!checkCsrf()) {
            $errors[] = 'Session expirée, veuillez réessayer.';
        }

        $success = false;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide.";
        }
        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }
        if ($password !== $confirmation) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        if (empty($errors)) {
            if ($this->users->emailExists($email)) {
                $errors[] = "Cet email est déjà utilisé.";
            } else {
                $token = bin2hex(random_bytes(32));
                $created = $this->users->create($email, $password, $token);
                if ($created) {
                    $siteUrl = env('SITE_URL', 'https://beghin.alwaysdata.net');
                    $link = $siteUrl . '/verify?token=' . $token;
                    mail($email, 'Confirme ton email', 'Clique ici : ' . $link);
                    $success = true;
                } else {
                    $errors[] = "Une erreur est survenue";
                }
            }
        }

        return new Response($this->showRegister($errors, $success, $email));
    }

    /**
     * Prépare le HTML de la page d'inscription.
     *
     * @param string[] $errors  Messages d'erreur à afficher
     * @param bool     $success true si le compte a été créé
     * @param string   $email   Email à pré-remplir dans le formulaire
     * @return string
     */
    private function showRegister(array $errors, bool $success, string $email): string {
        return render('register', [
            'errors'  => $errors,
            'success' => $success,
            'email'   => $email,
        ], 'Inscription');
    }

    /**
     * Ferme la session puis redirige vers l'accueil (GET ou POST /logout).
     *
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request): Response {
        $_SESSION = [];
        session_destroy();
        return Response::redirect('/');
    }

    /**
     * Confirme l'adresse email à partir du jeton du lien (GET /verify?token=...).
     *
     * @param Request $request
     * @return Response
     */
    public function verify(Request $request): Response {
        $token = (string) $request->get('token', '');
        $success = $token !== '' && $this->users->verifyEmail($token);

        $html = render('verify', ['success' => $success], 'Vérification du compte');
        return new Response($html);
    }
}