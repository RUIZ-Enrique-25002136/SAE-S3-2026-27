<?php
namespace App;
session_start();
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/src/views/partials/header.php';
require_once __DIR__ . '/src/views/partials/footer.php';
require_once __DIR__ . '/includes/render.php';
require_once __DIR__ . '/includes/connnexion_db.php';
use App\models\UserRepository;

buildHeader();
$email = '';
$erreurs = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'] ;

    if ($_POST["action"] === "connexion") {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erreurs[] = "adresse mail non valide";
    }
    if(empty($erreurs)){
            $pdo = connexion();
            $userRepository = new UserRepository($pdo);
            $user = $userRepository->findByEmail($email);
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

        }


}
render('login', ['erreurs' => $erreurs, 'success' => $success,'email'=>$email]);
buildFooter();
