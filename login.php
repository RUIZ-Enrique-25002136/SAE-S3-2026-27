<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/vues/login.php';
buildHeader();
login();
buildFotter();

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