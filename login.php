<?php
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

    if ($_POST["action"]??'' === "connexion") {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erreurs[] = "adresse mail non valide";
    }

    if(empty($erreurs)){
        try{
            $pdo = connexion();
            $pdo-> exec("SET CHARACTER SET utf8");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e){
            die('Erreur : '.$e->getMessage());
        }
        $sql = "SELECT *  FROM `users` WHERE  email = :email and password = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":email", $email,PDO::PARAM_STR);
        $stmt->bindValue(":password", $password,PDO::PARAM_STR);
        try{
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $result = $stmt->fetch();
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage() . PHP_EOL;
            echo 'Requête : '. $sql . PHP_EOL;
            exit();
        }
        if($result){
            $succes = true;
        }

        else {
            $erreurs[] = 'Le nom ou le mdp est invalide';
        }


    }

}}
if ($succes){
    $_SESSION['email'] = $result->email;
    echo <<< HTML
    Connexion réussie.
    HTML;
    head("login.php");
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
