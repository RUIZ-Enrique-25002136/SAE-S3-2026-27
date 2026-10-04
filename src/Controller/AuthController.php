<?php
namespace App\Controller;

use App\Models\UserRepository;

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
     * @return void
     */
    public function loginForm(): void
    {
        $this->showLogin([], false, '');
    }

    /**
     * Traite le formulaire de connexion (POST /login) : jeton CSRF, email, mot de passe
     * et compte vérifié, puis ouvre la session avec un nouvel identifiant.
     *
     * @return void
     */
    public function login(): void {
        $email = trim($_POST['email']);
        $password = $_POST['password'] ;
        $errors = [];
        if (!checkCsrf()) {
            $errors[] = 'Session expirée, veuillez réessayer.';
        }
        $success = false;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[] = "adresse mail non valide";
        }
        if(empty($errors)){
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
        $this->showLogin($errors, $success, $email);
    }

    /**
     * Affiche la page de connexion.
     *
     * @param string[] $errors  Messages d'erreur à afficher
     * @param bool     $success true si la connexion a réussi
     * @param string   $email   Email à pré-remplir dans le formulaire
     * @return void
     */
    private function showLogin(array $errors, bool $success, string $email): void {
        buildHeader();
        render('login', ['errors' => $errors, 'success' => $success, 'email' => $email]);
        buildFooter();
    }

    /**
     * Affiche le formulaire d'inscription (GET /register).
     *
     * @return void
     */
    public function registerForm(): void {
        $this->showRegister([], false, '');
    }

    /**
     * Traite le formulaire d'inscription (POST /register) : crée le compte
     * et envoie le lien de confirmation par mail.
     *
     * @return void
     */
    public function register(): void {
        $email = trim($_POST['email'] );
        $password = $_POST['password'] ;
        $confirmation = $_POST['confirmation'];
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
            if($this->users->emailExists($email)) {
                $errors[] = "Cet email est déjà utilisé.";
            }
            else{
                $token = bin2hex(random_bytes(32));
                $created = $this->users->create($email,$password,$token);
                if($created){
                    $config = parse_ini_file(__DIR__ . '/../../.env');
                    $siteUrl = $config['SITE_URL'] ?? 'https://beghin.alwaysdata.net';
                    $link = $siteUrl . '/verify?token=' . $token;
                    mail($email, 'Confirme ton email', 'Clique ici : ' . $link);
                    $success = true;}
                else{
                    $errors[] = "Une erreur est survenue";
                }
            }
        }
        $this->showRegister($errors, $success, $email);
    }

    /**
     * Affiche la page d'inscription.
     *
     * @param string[] $errors  Messages d'erreur à afficher
     * @param bool     $success true si le compte a été créé
     * @param string   $email   Email à pré-remplir dans le formulaire
     * @return void
     */
    private function showRegister(array $errors, bool $success, string $email): void {
        buildHeader();
        render('register', ['errors' => $errors, 'success' => $success,'email'=>$email]);
        buildFooter();
    }

    /**
     * Ferme la session puis redirige vers l'accueil (GET ou POST /logout).
     *
     * @return void
     */
    public function logout(): void {
        $_SESSION = [];
        session_destroy();
        header('Location: /');
        exit;
    }

    /**
     * Confirme l'adresse email à partir du jeton du lien (GET /verify?token=...).
     *
     * @return void
     */
    public function verify(): void {
        $token = $_GET['token'] ?? '';
        $success = $token !== '' && $this->users->verifyEmail($token);

        buildHeader();
        if ($success) {
            echo '<p>Email vérifié</p><p><a href="/login">Vous connecter ?</a></p>';
        } else {
            echo '<p>Lien invalide</p>';
        }
        buildFooter();
    }
}