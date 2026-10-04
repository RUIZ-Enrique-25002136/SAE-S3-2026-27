<?php
function csrfToken(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function checkCsrf(): bool
{
    $token = $_SESSION['csrf'] ?? '';
    return $token !== '' && hash_equals($token, $_POST['csrf'] ?? '');
}