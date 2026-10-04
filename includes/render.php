<?php
/**
 * Affiche une vue de src/views/ en lui transmettant des variables.
 * L'utilisateur connecté est toujours disponible dans la vue sous le nom $user.
 *
 * @param string               $view Nom du fichier de vue, sans .php
 * @param array<string, mixed> $data Variables à transmettre à la vue
 * @return void
 */
function render(string $view, array $data = []): void
{
    $data += ['user' => currentUser()];
    extract($data, EXTR_SKIP);
    require dirname(__DIR__) . '/src/views/' . $view . '.php';
}

/**
 * Renvoie l'utilisateur connecté, tel qu'enregistré en session à la connexion.
 *
 * @return array{id: int, email: string}|null null si personne n'est connecté
 */
function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

/**
 * Renvoie le chemin de l'URL demandée, sans slash final (par exemple /login).
 *
 * @return string
 */
function currentPath(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    return rtrim(is_string($path) ? $path : '/', '/') ?: '/';
}
