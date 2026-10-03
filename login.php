<?php

use models\UserRepository;

session_start();
require_once "includes/header.php";
require_once "includes/footer.php";
require_once "includes/connnexion_db.php";

buildHeader();
$erreurs = [];
$succes = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (($_POST["action"] ?? '') === "connexion") {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erreurs[] = "adresse mail non valide";
    }
    if(empty($erreurs)){
        try{
            $pdo = connexion();
            $userRepository = new UserRepository($pdo);
            $user = $userRepository -> findByEmail($email);

            if ($user != null && $user->verifierMotDePasse($password)){
                $succes = true;
                $_SESSION['email'] = $email;
                $_SESSION['utilisateur'] = [
                        'id' => $user->id,
                    'email' => $user->email,
                ];
            } else {
                $erreurs[] = 'email ou mot de passe incorrect';
            }
        } catch (PDOException $e) {
            $erreurs[] = 'Une erreur de la base de donnée est survenue';
        }
    }

}}
if ($succes){
    echo <<< HTML
    <div class="success-message">Connexion réussie. Redirection...</div>
    <meta http-equiv="refresh" content="1;url=index.php">
    <p><a href="index.php">Cliquez ici si vous n'êtes pas redirigé.</a></p>
    HTML;
}
else{
    if (!empty($erreurs)){
        foreach ($erreurs as $erreur) {
            echo "<ul><li>" . htmlspecialchars($erreur) . "</li></ul>";
        }
    }
}?>
<h1>Connexion</h1>
    <form method="post" action="login.php">
        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
        </p>
        <p>
            <label for="password">Mot de passe</label><br>
            <input type="password" id="password" name="password" required>
        </p>
        <button name="action" type="submit" value = "connexion">Se connecter</button>
    </form>
<?php buildFooter();?>
