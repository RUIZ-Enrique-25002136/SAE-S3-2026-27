<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/db.php';

buildHeader('Connexion');

$message = null;
$messageType = 'danger';

$action = $_POST['action'] ?? null;
if ($action === "submit" || ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $mdp = $_POST['password'] ?? '';
    $login = $_POST['login'] ?? $_POST['email'] ?? '';

    if (empty($mdp) || empty($login)) {
        $message = "Veuillez remplir tous les champs.";
    } else {
        try {
            $link = connexion();
            // Requête préparée PDO sécurisée
            $stmt = $link->prepare("SELECT * FROM users WHERE login = :login LIMIT 1");
            $stmt->execute(['login' => $login]);
            $user = $stmt->fetch();

            if ($user && (password_verify($mdp, $user['password']) || $user['password'] === md5($mdp))) {
                $_SESSION['utilisateur'] = $user;
                $messageType = 'success';
                $message = "Connexion réussie ! Redirection en cours...";
                header('Refresh: 1; URL=index.php');
            } else {
                $message = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } catch (Throwable $e) {
            // Fallback en cas de configuration différente
            $message = "Erreur de connexion à la base de données.";
        }
    }
}
?>

<h1>Connexion</h1>

<?php if ($message !== null): ?>
    <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="post" action="login.php">
    <p>
        <label for="email">Email / Identifiant</label>
        <input type="text" id="email" name="login" required placeholder="nom@exemple.fr" value="<?= htmlspecialchars($_POST['login'] ?? $_POST['email'] ?? '') ?>">
    </p>
    <p>
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required placeholder="••••••••">
    </p>
    <button type="submit" name="action" value="submit">Se connecter</button>
</form>

<p class="auth-helper">Pas encore de compte ? <a href="register.php">Créer un compte</a></p>

<?php
buildFooter();
?>
