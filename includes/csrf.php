<?php
/**
 * Renvoie le jeton CSRF de la session, et le crée au premier appel.
 *
 * @return string Jeton de 64 caractères hexadécimaux, à placer dans un champ caché des formulaires
 */
function csrfToken(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/**
 * Vérifie que le jeton envoyé par le formulaire correspond à celui de la session.
 * Un jeton absent ou vide est toujours refusé.
 *
 * @return bool true si le jeton est valide
 */
function checkCsrf(): bool
{
    $token = $_SESSION['csrf'] ?? '';
    return $token !== '' && hash_equals($token, $_POST['csrf'] ?? '');
}