<?php
$token = $_GET['token'] ?? '';

$pdo = getConnection();
$stmt = $pdo->prepare("UPDATE users SET verified = TRUE, verify_token = NULL WHERE verify_token = ?");
$stmt->execute([$token]);

if ($stmt->rowCount() > 0){
    echo <<< HTML
        'Email vérifié'
        <p><a href="/login.php">Vous connecter ?</a></p>
    HTML;
} else {
    echo 'Lien ivalide';
}


