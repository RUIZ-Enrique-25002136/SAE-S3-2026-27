<?php
require_once "includes/header.php";
buildHeader();
echo <<< HTML
<h1>Connexion</h1>
    <form method="post" action="login.php">
        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" >
        </p>
        <p>
            <label for="password">Mot de passe</label><br>
            <input type="password" id="password" name="password" required>
        </p>
        <button type="submit">Se connecter</button>
    </form>

HTML;
$action = $_POST['action'];
if ($action === "submit") {
    $mdp = $_POST['password'];
    $login = $_POST['login'];
    if (sizeof($mdp) === 0 || sizeof($login) === 0) {
        echo "Veuillez remplir tous les champs";
    }
    $link = connexion();
    $query = "SELECT user FROM Users WHERE login = $login and pwd = MD5('$mdp') ;";
    $result = mysqli_query($link, $query);
    if (!$result) {
        echo "nom d'utilisateur ou mot de passe incorrect";
    } else {
        if (mysqli_num_rows($result) === 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo $row['username']; # Rediriger sur page accueil.
            }

        }
        else{
            echo "Plusieurs utilisateur au même nom erreur base de données";
        }
    }
}
require_once "includes/footer.php";
buildFooter();
