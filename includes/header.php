<?php
function buildHeader() : void
{
$utilisateur = isset($_SESSION['utilisateur']) ? $_SESSION['utilisateur'] : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
</head>
<body>
<nav>
    <a href="../index.php">Accueil</a>
    <?php if ($utilisateur === null): ?>
        | <a href="../login.php">Connexion</a>
        <a href="../register.php">Inscription</a>
    <?php else: ?>
        | <a href="../index.php?action=logout">Déconnexion</a>
    <?php endif; ?>
</nav>


<?php }?>
