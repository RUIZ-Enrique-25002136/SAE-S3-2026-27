<?php
$mdp = $_POST['password'];
$login = $_POST['login'];
$action = $_POST['action'];
if($action == "submit"){
    if (sizeof($mdp) === 0 || sizeof($login) === 0) {
        return "Veuillez remplir tous les champs";
    }
    $link = mysqli_connect('localhost', 'admin', 'admin')
    or die('Pb de connexion au serveur: ' . mysqli_connect_error());
    mysqli_select_db($link, 'my_dbname') or die ('Pb de sélection BD : ' . mysqli_error($link));
    $query = "SELECT user FROM Users WHERE login = $login and pwd = MD5('$mdp') ";
    $result = mysqli_query($link, $query);
    if (!$result)
    {
        echo 'Impossible d\'exécuter la requête ', $query, ' : ', mysqli_error($link);
    }
    else {
        if (mysqli_num_rows($result) != 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo $row['username'];
            }
        }
        else{
        echo "nom d'utilisateur ou mot de passe incorrect";
        }
    }
    }