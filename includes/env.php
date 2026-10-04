<?php

/**
 * Lit une valeur du fichier .env situé à la racine du projet.
 * Le fichier n'est lu qu'une seule fois par requête ; s'il est absent ou illisible, la page s'arrête.
 *
 * @param string      $key     Nom de la clé (par exemple DB_HOST ou SITE_URL)
 * @param string|null $default Valeur renvoyée si la clé n'existe pas
 * @return string|null
 */
function env(string $key, ?string $default = null): ?string
{
    static $config = null;
    if ($config === null) {
        $file = dirname(__DIR__) . '/.env';
        if (!file_exists($file)) {
            die('Erreur configuration : fichier .env introuvable.');
        }
        $values = parse_ini_file($file);
        if ($values === false) {
            die('Erreur configuration : fichier .env illisible.');
        }
        $config = $values;
    }
    return isset($config[$key]) ? (string) $config[$key] : $default;
}
