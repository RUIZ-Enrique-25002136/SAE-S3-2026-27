<?php
require_once 'autoload.php';
use App\models\UserRepository;
require_once "includes/header.php";
require_once "includes/footer.php";
require_once "includes/connnexion_db.php";
$config = parse_ini_file(__DIR__ . '/.env');
$siteUrl = $config['SITE_URL'] ?? 'https://beghin.alwaysdata.net';

buildHeader();
$erreurs = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';
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
        $pdo = connexion();
        $token = bin2hex(random_bytes(32));
        $userRepository = new UserRepository($pdo);
        if($userRepository-> emailExists($email)) {
            $erreurs[] = "Cet email est déjà utilisé.";
        }
        else{
            $res = $userRepository -> create($email,$password,$token);
            if(!$res){
                $erreurs[] = "Une erreur est survenue";
            }
            $link = $siteUrl . '/verify.php?token=' . $token;
            mail($email, 'Confirme ton email', 'Clique ici : ' . $link);
            $success = true;}
    }

}
if ($success){
    echo <<< HTML
    Inscription réussie.
    Veuillez vérifier votre email.
    HTML;
}
else{
    if (!empty($erreurs)){
        echo "<ul>";
     foreach ($erreurs as $erreur) {
        echo htmlspecialchars($erreur) . "</li></ul>";
        }
     echo "</ul>";
    }

}?>
<h1>S'inscrire</h1>
<form method="post" action="register.php">
    <p>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
    </p>
    <p>
        <label for="password">Mot de passe</label><br>
        <input type="password" id="password" name="password" required>
    </p>
    <p>
        <label for="confirmation">Confirmation du mot de passe</label><br>
        <input type="password" id="confirmation" name="confirmation" required>
    </p>
    <button name="action" type="submit" value = "inscription">S'inscrire</button>
</form>
<?php buildFooter();?>
