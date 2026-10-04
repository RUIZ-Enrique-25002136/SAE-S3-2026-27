<?php
function render(string $view, array $data = []): void
{
    $data += ['user' => $_SESSION['user'] ?? null];
    extract($data, EXTR_SKIP);
    require dirname(__DIR__) . '/src/views/' . $view . '.php';
}
