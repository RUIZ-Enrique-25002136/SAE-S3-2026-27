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

        $config = parse_ini_file($envFile);

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=utf8mb4',
            $config['DB_HOST'],
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
    }

    return $pdo;
}
