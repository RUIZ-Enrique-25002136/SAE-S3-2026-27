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

    if ($_POST["action"] === "connexion") {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erreurs[] = "adresse mail non valide";
    }

    if(empty($erreurs)){
        $link = connexion();
        $query = "SELECT name from  Users where name = $email and password = $password;";
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) === 0) {
            $erreurs[] = 'Le nom ou le mdp est invalide';
        }
        else{
            $succes = true;
        }


    }

}}
if ($succes){
    echo <<< HTML
    Connexion réussie.
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