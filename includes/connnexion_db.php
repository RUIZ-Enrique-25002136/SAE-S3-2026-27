<?php

function connexion(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $envFile = __DIR__ . '/.env';
        if (!file_exists($envFile)) {
            $envFile = __DIR__ . '/../.env';
        }

        if (!file_exists($envFile)) {
            die('Erreur configuration : fichier .env introuvable.');
        }

        if (!extension_loaded('pdo_mysql')) {
            die("Erreur configuration : L'extension PHP 'pdo_mysql' n'est pas activée. Veuillez l'activer dans votre php.ini (ex: /etc/php/php.ini)." . PHP_EOL);
        }

        $config = parse_ini_file($envFile);

        $host = $config['DB_HOST'] ?? $config['HOST'] ?? 'localhost';
        $dbname = $config['DB_NAME'] ?? $config['DB_DATABASE'] ?? 'sae_s3';
        $user = $config['DB_USER'] ?? $config['SQL_USR'] ?? 'root';
        $pass = $config['DB_PASS'] ?? $config['SQL_PWD'] ?? '';
        $port = $config['DB_PORT'] ?? '3306';

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $host,
            $port,
            $dbname
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die('Connexion échouée : ' . $e->getMessage());
        }
    }

    return $pdo;
}
