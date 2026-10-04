<?php
namespace App;
use App\models\UserRepository;

buildHeader();
$email = '';
$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'] ;

    if ($_POST["action"] === "connexion") {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "adresse mail non valide";
    }
    if(empty($errors)){
        $pdo = getConnection();
            $userRepository = new UserRepository($pdo);
            $user = $userRepository->findByEmail($email);
            if ($user != null && $user->verifyPassword($password)){
                $success = true;
                $_SESSION['email'] = $email;
                $_SESSION['user'] = [
                        'id' => $user->id,
                    'email' => $user->email,
                ];
            } else {
                $errors[] = 'email ou mot de passe incorrect';
                }
            }

        }


}
render('login', ['errors' => $errors, 'success' => $success,'email'=>$email]);
buildFooter();
