<?php

$mdp = $_POST['password'];
$login = $_POST['login'];
$action = $_POST['action'];
if ($action === "submit") {
    if (sizeof($mdp) === 0 || sizeof($login) === 0) {
        echo "Veuillez remplir tous les champs";
    }
    $link = mysqli_connect('localhost', 'admin', 'admin')
    or die('Pb de connexion au serveur: ' . mysqli_connect_error());
    mysqli_select_db($link, 'Username') or die ('Pb de sélection BD : ' . mysqli_error($link));
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
} elseif ($action == "register") {
    $link = mysqli_connect('localhost', 'admin', 'admin')
    or die('Pb de connexion au serveur: ' . mysqli_connect_error());
    mysqli_select_db($link, 'Username') or die ('Pb de sélection BD : ' . mysqli_error($link));
    $query = "INSERT INTO Users VALUES ($login, $mdp);";
    $result = mysqli_query($link, $query);
    if (!$result) {
        echo 'Le nom est invalide ou déjà utilisé';
    }
    if (sizeof($mdp) < 8) {
        echo "mot de passe trop court";
    } else {
        echo "Votre identifiant a bien été créé.";
    }
}