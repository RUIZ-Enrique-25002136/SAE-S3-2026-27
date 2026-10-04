<?php

/**
 * Ouvre la connexion PDO à MariaDB avec les paramètres du fichier .env.
 * En cas d'échec, l'erreur est écrite dans le journal du serveur et le visiteur voit une erreur 500.
 *
 * @return PDO Connexion configurée (exceptions, tableaux associatifs, vraies requêtes préparées)
 */
function getConnection(): PDO
{
    static $pdo = null;
        $envFile = __DIR__ . '/.env';
        if (!file_exists($envFile)) {
            $envFile = __DIR__ . '/../.env';
        }

        if (!file_exists($envFile)) {
            die('Erreur configuration : fichier .env introuvable.');
        }

        if (!extension_loaded('pdo_mysql')) {
            die("Erreur configuration : l'extension PHP pdo_mysql n'est pas activée.");
        }

        $config = parse_ini_file($envFile);
        if ($config === false) {
            die('Erreur configuration : fichier .env illisible.');
        }

        foreach (['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'] as $key) {
            if (!isset($config[$key])) {
                die("Erreur configuration : clé $key absente du fichier .env.");
            }
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $config['DB_HOST'],
            $config['DB_PORT'] ?? '3306',
            $config['DB_NAME']
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], $options);
        } catch (PDOException $e) {
            error_log('Connexion BDD échouée : ' . $e->getMessage());
            http_response_code(500);
            die('Service momentanément indisponible.');
        }


    return $pdo;
}
