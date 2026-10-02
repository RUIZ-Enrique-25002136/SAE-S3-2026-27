<?php
$token = $_GET['token'];

$pdo = connection();
$stmt = $pdo->prepare("UPDATE users SET verfied = TRUE, verify_token = NULL WHERE verify_token = ?");
$stmt->execute([$token, $email]);

if ($stmt->rowCount() > 0){
    echo <<< HTML
        'Email vérifié'
        <p><a href="login.php">Vous connecter ?</a></p>
    HTML;
} else {
    echo 'Lien ivalide';
}


