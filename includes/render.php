<?php
function render(string $vue, array $donnees = []): void
{
    $donnees += ['utilisateur' => $_SESSION['utilisateur'] ?? null];
    extract($donnees, EXTR_SKIP);
    require dirname(__DIR__) . '/src/views/' . $vue . '.php';
}
