<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/db.php';

buildHeader('Inscription');

$message = null;
$messageType = 'danger';

$action = $_POST['action'] ?? null;
if ($action === "register" || ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $mdp = $_POST['password'] ?? '';
    $login = $_POST['login'] ?? $_POST['email'] ?? '';

    if (empty($login) || empty($mdp)) {
        $message = "Veuillez remplir tous les champs.";
    } elseif (strlen($mdp) < 8) {
        $message = "Le mot de passe doit contenir au moins 8 caractères.";
    } else {
        try {
            $link = connexion();
            // Vérifier si l'utilisateur existe déjà
            $stmt = $link->prepare("SELECT id FROM users WHERE login = :login");
            $stmt->execute(['login' => $login]);
            if ($stmt->fetch()) {
                $message = "Cet identifiant est déjà utilisé.";
            } else {
                // Insertion sécurisée avec mot de passe haché
                $hash = password_hash($mdp, PASSWORD_DEFAULT);
                $stmt = $link->prepare("INSERT INTO users (login, password) VALUES (:login, :password)");
                $stmt->execute([
                    'login' => $login,
                    'password' => $hash,
                ]);

                $messageType = 'success';
                $message = "Votre compte a bien été créé ! Vous pouvez maintenant vous connecter.";
            }
        } catch (Throwable $e) {
            $message = "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
?>

<h1>S'inscrire</h1>

<?php if ($message !== null): ?>
    <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="post" action="register.php">
    <p>
        <label for="email">Email / Identifiant</label>
        <input type="text" id="email" name="login" required placeholder="nom@exemple.fr" value="<?= htmlspecialchars($_POST['login'] ?? $_POST['email'] ?? '') ?>">
    </p>
    <p>
        <label for="password">Mot de passe (8 caractères min.)</label>
        <input type="password" id="password" name="password" required placeholder="••••••••" minlength="8">
    </p>
    <button type="submit" name="action" value="register">S'inscrire</button>
</form>

<p class="auth-helper">Déjà un compte ? <a href="login.php">Se connecter</a></p>

<?php
buildFooter();
?>
