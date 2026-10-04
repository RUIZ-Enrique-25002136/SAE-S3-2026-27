<?php
use App\models\UserRepository;
$config = parse_ini_file(dirname(__DIR__) . '/.env');
$siteUrl = $config['SITE_URL'] ?? 'https://beghin.alwaysdata.net';

buildHeader();
$errors = [];
$success = false;
$email= '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] );
    $password = $_POST['password'] ;
    $confirmation = $_POST['confirmation'];
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
        $token = bin2hex(random_bytes(32));
        $pdo = getConnection();
        $userRepository = new UserRepository($pdo);
        if($userRepository-> emailExists($email)) {
            $errors[] = "Cet email est déjà utilisé.";
        }
        else{
            $created = $userRepository -> create($email,$password,$token);
            if($created){
                $link = $siteUrl . '/verify?token=' . $token;
                mail($email, 'Confirme ton email', 'Clique ici : ' . $link);
                $success = true;}
            else{
                $errors[] = "Une erreur est survenue";
            }
            }
    }

}

render('register', ['errors' => $errors, 'success' => $success,'email'=>$email]);
buildFooter();

