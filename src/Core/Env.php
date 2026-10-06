<?php
namespace App\Core;
final class Env

{
    /** @var array<string, mixed>|null Valeurs du .env, chargées une seule fois */
    private static ?array $config = null;

    /**
     * Charge le fichier .env (parse_ini_file).
     */
    public static function charger(string $file): void
    {
        if (!file_exists($file)) {
            die('Erreur configuration : fichier .env introuvable.');
        }

        $values = parse_ini_file($file);
        if ($values === false) {
            die('Erreur configuration : fichier .env illisible.');
        }

        self::$config = $values;
    }

    /**
     * Retourne la valeur d'une clé du .env, ou $defaut si elle est absente.
     */
    public static function get(string $cle, ?string $defaut = null): ?string
    {
        return isset(self::$config[$cle]) ? (string) self::$config[$cle] : $defaut;
    }
}