<?php
use App\models\UserRepository;
$config = parse_ini_file(dirname(__DIR__) . '/.env');
$siteUrl = $config['SITE_URL'] ?? 'https://beghin.alwaysdata.net';

buildHeader();
$erreurs = [];
$success = false;
$email= '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] );
    $password = $_POST['password'] ;
    $confirmation = $_POST['confirmation'];
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
        $token = bin2hex(random_bytes(32));
        $pdo = connexion();
        $userRepository = new UserRepository($pdo);
        if($userRepository-> emailExists($email)) {
            $erreurs[] = "Cet email est déjà utilisé.";
        }
        else{
            $res = $userRepository -> create($email,$password,$token);
            if($res){
                $link = $siteUrl . '/verify.php?token=' . $token;
                mail($email, 'Confirme ton email', 'Clique ici : ' . $link);
                $success = true;}
            else{
                $erreurs[] = "Une erreur est survenue";
            }
            }
    }

}

render('register', ['erreurs' => $erreurs, 'success' => $success,'email'=>$email]);
buildFooter();

