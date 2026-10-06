<?php
/**
 * @var string $title
 * @var array{id: int, email: string}|null $user
 * @var string $currentPath
 * @var string|null $path
 */

use App\View\Layout;
use App\View\Link;
use App\View\Paragraph;
use App\View\Strong;

return new Layout($title, $user, [
        new Paragraph(['La page ', new Strong($path ?? ''), " n'existe pas"]),
        new Link("Retour a l'acceuil", '/', 'btn btn-primary'),
], $currentPath);
