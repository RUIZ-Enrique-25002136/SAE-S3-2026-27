<?php
$email = '';
require_once "includes/header.php";
require_once "includes/footer.php";
buildHeader();
$erreurs = [];
$succes = false;
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';}
?>
<form method="post" action="register.php">
            <p>
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
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
<?php

if ($_POST["action"] === "inscription") {
    $mdp = $_POST['password'];
    $login = $_POST['login'];
    if (strlen($mdp) < 8) {
        echo <<< HTML
        mot de passe trop court
        HTML;
    }
    else{
        $link = connexion();
        $query = "INSERT INTO Users VALUES ($login, $mdp);";
        $result = mysqli_query($link, $query);
        if (!$result) {
            echo <<<"HTML"
Le nom est invalide ou déjà utilisé'
HTML;
        }
        else {
            echo <<<HTML
"Votre identifiant a bien été créé.";
HTML;}

    }}
else{
    echo $confirmation;

}
buildFooter();