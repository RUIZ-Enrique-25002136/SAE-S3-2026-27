<?php
namespace App\Controller;

use App\models\UserRepository;

class AuthController {
    public function __construct(private UserRepository $users) {}

    public function loginForm(): void
    {
        $this->showLogin([], false, '');
    }

    public function login(): void {
        $email = trim($_POST['email']);
        $password = $_POST['password'] ;
        $erreurs = [];
        $success = false;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $erreurs[] = "adresse mail non valide";
        }
        if(empty($erreurs)){
            $user = $this->user->findByEmail($email);
            if ($user != null && $user->verifierMotDePasse($password)){
                $success = true;
                $_SESSION['email'] = $email;
                $_SESSION['utilisateur'] = [
                    'id' => $user->id,
                    'email' => $user->email,
                ];
            } else {
                $erreurs[] = 'email ou mot de passe incorrect';
            }
        }
        $this->showLogin($erreurs, $success, $email);
    }

    private function showLogin(array $errors, bool $success, string $email): void {
        buildHeader();
        render('login', ['erreurs' => $errors, 'success' => $success, 'email' => $email]);
        buildFooter();
    }

    public function registerForm(): void {
        $this->showregister([], false, '');
    }

    public function register(): void {
        $email = trim($_POST['email'] );
        $password = $_POST['password'] ;
        $confirmation = $_POST['confirmation'];
        $erreurs = [];

        $success = false;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "Email invalide.";
        }
        if (strlen($password) < 8) {
            $erreurs[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }
        if ($password !== $confirmation) {
            $erreurs[] = "Les mots de passe ne correspondent pas.";
        }

        if (empty($erreurs)) {
            if($this->users->emailExists($email)) {
                $erreurs[] = "Cet email est déjà utilisé.";
            }
            else{
                $token = bin2hex(random_bytes(32));
                $res = $this->users->create($email,$password,$token);
                if($res){
                    $config = parse_ini_file(__DIR__ . '/../../.env');
                    $siteUrl = $config['SITE_URL'] ?? 'https://beghin.alwaysdata.net';
                    $link = $siteUrl . '/verify?token=' . $token;
                    mail($email, 'Confirme ton email', 'Clique ici : ' . $link);
                    $success = true;}
                else{
                    $erreurs[] = "Une erreur est survenue";
                }
            }
        }
        $this->showregister($erreurs, $success, $email);
    }

    private function showregister(array $erreurs, bool $success, string $email): void {
        buildHeader();
        render('register', ['erreurs' => $erreurs, 'success' => $success,'email'=>$email]);
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