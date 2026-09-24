<?php
echo <<< HTML

<h1>S'Inscrire</h1>
    <form method="post" action="register.php">
        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" >
        </p>
        <p>
            <label for="password">Mot de passe</label><br>
            <input type="password" id="password" name="password" required>
        </p>
        <button type="submit">S'inscrire</button>
    </form>

HTML;
buildHeader();
$action = $_POST['action'];
if ($action == "register") {
    $mdp = $_POST['password'];
    $login = $_POST['login'];
    if (sizeof($mdp) < 8) {
        echo "mot de passe trop court";
    }
    else{
        $link = connexion();
        $query = "INSERT INTO Users VALUES ($login, $mdp);";
        $result = mysqli_query($link, $query);
        if (!$result) {
            echo 'Le nom est invalide ou déjà utilisé';
        }
        else {
            echo "Votre identifiant a bien été créé.";
        }
    }}
buildFooter();