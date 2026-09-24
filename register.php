<?php
require_once "includes/header.php";
require_once "includes/footer.php";
buildHeader();
$erreurs = [];
$succes = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmation = $_POST['confirmation'] ?? '';}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';}

if ($_POST["action"] === "inscription") {

    if (strlen($password) < 8) {
       $erreurs[] =  "mot de passe trop court";
    }

    if( strcmp($confirmation ,$password) !== 0 ){
        $erreurs[] = "Les mots de passe ne correspondent pas";}

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $erreurs[] = "adresse mail non valide";
        }

    if(empty($erreurs)){
        $link = connexion();
        $query = "INSERT INTO Users VALUES ($email, $password);";
        $result = mysqli_query($link, $query);

        if (!$result) {
            $erreurs[] = 'Le nom est invalide ou déjà utilisé';
        }
        else{
            $succes = true;
        }


    }

}
if ($succes){
    echo <<< HTML
    Inscription réussie.
    <p><a href="login.php">Vous connecter ?</a></p>
    HTML;
}
else{
    if (!empty($erreurs)){
     foreach ($erreurs as $erreur) {
        echo "<li>" . htmlspecialchars($erreur) . "</li>";
        }
    }

}?>
<form method="post" action="register.php">
    <p>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
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