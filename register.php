<?php
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

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(32));
        $sql = "INSERT INTO users(email,password, verify_token) VALUES (:email,:password, :token)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $passwordHash, PDO::PARAM_STR);
        $stmt->bindValue(":token", $token, PDO::PARAM_STR);
        try{
            $stmt->execute();
            $success = true;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $erreurs[] = "Cet email est déjà utilisé.";
                $success = false;
            } else {
                $erreurs[] = "Une erreur est survenue.";
                $success = false;
            }
        }
        if ($success) {
            $link = $siteUrl . '/verify.php?token=' . $token;
            mail($email, 'Confirme ton email', 'Clique ici : ' . $link);
        }

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
