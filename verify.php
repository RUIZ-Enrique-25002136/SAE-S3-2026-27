<?php
$token = $_GET['token'] ?? '';
require_once __DIR__ . '/includes/connnexion_db.php';

$pdo = connexion();
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


