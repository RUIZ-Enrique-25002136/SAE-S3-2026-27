<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
session_start();

$utilisateur = isset($_SESSION['utilisateur']) ? $_SESSION['utilisateur'] : null;


if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}


$titre = 'Accueil';
buildHeader();
?>

<h1>Bienvenue</h1>

<?php if ($utilisateur !== null): ?>
    <p>Bonjour <strong><?= htmlspecialchars($utilisateur['email']) ?></strong>, vous êtes connecté.</p>
<?php else: ?>
    <p>Vous n'êtes pas connecté. <a href="login.php">Connectez-vous</a> ou <a href="register.php">créez un compte</a>.</p>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php';
buildFooter();?>