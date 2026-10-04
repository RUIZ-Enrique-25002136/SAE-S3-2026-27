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
    $data += ['user' => $_SESSION['user'] ?? null];
    extract($data, EXTR_SKIP);
    require dirname(__DIR__) . '/src/views/' . $view . '.php';
}
