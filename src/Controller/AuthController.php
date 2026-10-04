<?php
namespace App\Controller;

use App\Models\UserRepository;

class AuthController {
    public function __construct(private UserRepository $users) {}

    public function loginForm(): void
    {
        $this->showLogin([], false, '');
    }

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
                    ];
                }
            } else {
                $errors[] = 'email ou mot de passe incorrect';
            }
        }
        $this->showLogin($errors, $success, $email);
    }

    private function showLogin(array $errors, bool $success, string $email): void {
        buildHeader();
        render('login', ['errors' => $errors, 'success' => $success, 'email' => $email]);
        buildFooter();
    }

    public function registerForm(): void {
        $this->showRegister([], false, '');
    }

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

    private function showRegister(array $errors, bool $success, string $email): void {
        buildHeader();
        render('register', ['errors' => $errors, 'success' => $success,'email'=>$email]);
        buildFooter();
    }

    public function logout(): void {
        $_SESSION = [];
        session_destroy();
        header('Location: /');
        exit;
    }

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