<?php
echo <<< HTML


HTML;
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
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