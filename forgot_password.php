<?php
require_once 'includes/footer.php';
require_once 'includes/header.php';
require_once "includes/connnexion_db.php";

buildHeader();

$erreurs = [];
$succes = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Email invalide.";
    }

    if (empty($erreurs)) {
        try {
            $pdo = connexion();
            $pdo->exec("SET CHARACTER SET utf8");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {
                $token = bin2hex(random_bytes(32));
            } catch (\Random\RandomException $e) {
                die('Erreur : ' . $e->getMessage());
            }

            $sql = "UPDATE users SET verify_token = ? WHERE email = ?";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([$token, $email]);
            $link = 'https://beghin.alwaysdata.net/reset_password.php?token=' . $token;
            mail($email, 'Pour réinitialiser ton mot de passe', 'Clique ici : ' . $link);
            $succes = true;
        } catch (PDOException $e) {
            $erreurs[] = "Une erreur est survenue.";
        }
    }
}

    foreach ($erreurs as $erreur) {
        echo "<ul><li>" . htmlspecialchars($erreur) . "</li></ul>";
    }

    if ($succes) {
        echo "<p>Si cet email existe, un lien vient d'être envoyé.</p>";
    } else {
        ?>
        <form method="post" action="forgot_password.php">
            <p>
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" required>
            </p>
            <button type="submit">Envoyer le lien</button>
        </form>
        <?php
}?>
