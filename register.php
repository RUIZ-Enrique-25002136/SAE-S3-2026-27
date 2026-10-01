<?php
require_once "includes/header.php";
require_once "includes/footer.php";
require_once "includes/db.php";
buildHeader();
$erreurs = [];
$succes = false;

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

     if(empty($erreurs)){
        try{
            $dsn = "mysql:host=localhost;dbname=users";
            $pdo = new \PDO($dsn, 'root','root');
            $pdo-> exec("SET CHARACTER SET utf8");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e){
            die('Erreur : '.$e->getMessage());
        }
        $sql = "INSERT INTO users(email,password) VALUES (:email,:password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":email", $email,PDO::PARAM_STR);
        $stmt->bindValue(":password", $password,PDO::PARAM_STR);
        try{
            $stmt->execute();
            $succes = true;
        }
        catch(PDOException $e){
            if ($e->getCode() === '23000') { 
                $erreurs[] = "Cet email est déjà utilisé.";
                }
            else {
                $erreurs[] = "Une erreur est survenue.";
    }


    }

}}
if ($succes){
    echo <<< HTML
    Inscription réussie.
    <p><a href="login.php">Vous connecter ?</a></p>
    HTML;
}
else{
    if (!empty($erreurs)){
     foreach ($erreurs as $erreur) {
        echo "<ul><li>" . htmlspecialchars($erreur) . "</li></ul>";
        }
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