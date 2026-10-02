<?php
/**
 * Script d'initialisation et de test de la base de données locale pour la SAE S3.
 * Usage CLI : php sql/init_db.php
 */

declare(strict_types=1);

echo "=========================================" . PHP_EOL;
echo "  Initialisation de la base de données   " . PHP_EOL;
echo "=========================================" . PHP_EOL . PHP_EOL;

// 1. Vérification de l'extension PHP pdo_mysql
if (!extension_loaded('pdo_mysql')) {
    echo "❌ Erreur : L'extension PHP 'pdo_mysql' n'est pas activée." . PHP_EOL;
    echo "💡 Pour l'activer sur Arch Linux, exécutez :" . PHP_EOL;
    echo "   sudo sed -i 's/^;extension=pdo_mysql/extension=pdo_mysql/' /etc/php/php.ini" . PHP_EOL . PHP_EOL;
    exit(1);
}
echo "✅ Extension PHP pdo_mysql activée." . PHP_EOL;

// 2. Chargement du fichier .env
$envFile = __DIR__ . '/../.env';
if (!file_exists($envFile)) {
    echo "❌ Erreur : Fichier .env introuvable à la racine du projet." . PHP_EOL;
    exit(1);
}

$config = parse_ini_file($envFile);
$host = $config['DB_HOST'] ?? $config['HOST'] ?? 'localhost';
$dbname = $config['DB_NAME'] ?? $config['DB_DATABASE'] ?? 'sae_s3';
$user = $config['DB_USER'] ?? $config['SQL_USR'] ?? 'root';
$pass = $config['DB_PASS'] ?? $config['SQL_PWD'] ?? '';
$port = (int)($config['DB_PORT'] ?? 3306);

echo "📋 Configuration chargée depuis .env :" . PHP_EOL;
echo "   - Hôte     : $host:$port" . PHP_EOL;
echo "   - Base     : $dbname" . PHP_EOL;
echo "   - Utilisateur : $user" . PHP_EOL . PHP_EOL;

// 3. Connexion au serveur MySQL/MariaDB (sans spécifier de base)
try {
    $dsnServer = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $host, $port);
    $pdo = new PDO($dsnServer, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "✅ Connexion au serveur MySQL/MariaDB réussie." . PHP_EOL;
} catch (PDOException $e) {
    echo "❌ Erreur de connexion au serveur MySQL/MariaDB : " . $e->getMessage() . PHP_EOL . PHP_EOL;
    echo "💡 Si l'utilisateur '$user' ou les droits ne sont pas encore créés dans MariaDB, exécutez :" . PHP_EOL;
    echo "   sudo mariadb -e \"CREATE DATABASE IF NOT EXISTS $dbname; CREATE USER IF NOT EXISTS '$user'@'localhost' IDENTIFIED BY '$pass'; GRANT ALL PRIVILEGES ON $dbname.* TO '$user'@'localhost'; FLUSH PRIVILEGES;\"" . PHP_EOL . PHP_EOL;
    exit(1);
}

// 4. Création de la base de données si elle n'existe pas
try {
    $pdo->exec(sprintf('CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci', $dbname));
    echo "✅ Base de données '$dbname' vérifiée / créée." . PHP_EOL;
} catch (PDOException $e) {
    echo "❌ Impossible de créer la base de données '$dbname' : " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// 5. Bascule sur la base et exécution du schéma
try {
    $pdo->exec(sprintf('USE `%s`', $dbname));
    $schemaFile = __DIR__ . '/schema.sql';
    if (!file_exists($schemaFile)) {
        echo "❌ Erreur : Fichier sql/schema.sql introuvable." . PHP_EOL;
        exit(1);
    }

    $sql = file_get_contents($schemaFile);
    $pdo->exec($sql);
    echo "✅ Schéma 'sql/schema.sql' importé avec succès." . PHP_EOL;
} catch (PDOException $e) {
    echo "❌ Erreur lors de l'exécution du schéma SQL : " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// 6. Vérification des tables et colonnes
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->fetch()) {
        echo "✅ Table 'users' présente :" . PHP_EOL;
        $cols = $pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $col) {
            echo "   - {$col['Field']} ({$col['Type']}) " . ($col['Key'] ? "[{$col['Key']}]" : "") . PHP_EOL;
        }
    }
} catch (PDOException $e) {
    echo "⚠️ Attention lors de la vérification de la table : " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "🎉 Tout est prêt et fonctionnel pour votre base de données locale !" . PHP_EOL;
