<?php
    require_once 'includes/header.php';
    require_once 'includes/footer.php';
    require_once 'includes/connnexion_db.php';

    buildHeader();

    $erreurs = [];
    $success = false;
    $token = $_GET["token"] ?? $_POST["token"];

    try {
        $pdo = connexion();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $password = $_POST["password"];
        $confirmation  = $_POST["confirmation"];

        if (strlen($password) < 8) {
            $erreurs[] = 'Le mot de passe doit contenir au moins 8 caractére';
        }

        if ($password !== $confirmation) {
            $erreurs[] = 'Les mots de passe ne correspondent pas';
        }

        if (empty($erreurs)) {
            try {
                $stmt = $pdo->prepare("UPDATE users SET password = ?, verify_token = NULL WHERE verify_token = ?");
                $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $token]);

                if ($stmt->rowCount() > 0) {
                    $success = true;
                } else {
                    $erreurs[] = "Lien invalide";
                }
            } catch (PDOException $e) {
                $erreurs[] = "Une erreur est survenue";
            }
        }
    }

    if ($success) {
        echo <<< HTML
            Mot de passe réinitialisé
            <a href="login.php">Vous connecter ?</a> 
        HTML;
    } else {
        foreach ($erreurs as $erreur) {
            echo "<ul><li>" . htmlspecialchars($erreur) . "</li></ul>";
        }

        $stmt = $pdo->prepare("SELECT id FROM users WHERE verify_token = ?");
        $stmt->execute([$token]);

        if ($stmt->rowCount() > 0) {
            ?>
            <form method="post" action="reset_password.php">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <p>
                    <label for="password">Nouveau mot de passe</label><br>
                    <input type="password" id="password" name="password" required>
                </p>
                <p>
                    <label for="confirmation">Confirmation</label><br>
                    <input type="password" id="confirmation" name="confirmation" required>
                </p>
                <button type="submit">Réinitialiser</button>
            </form>
            <?php
        } else {
            echo "<p>Lien invalide.</p>";
        }

    }
