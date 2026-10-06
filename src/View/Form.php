<?php

namespace App\View;

use App\Core\Csrf;

final class Form extends Component
{
    public function __construct(
        private readonly string $method,
        private readonly string $action
    ){
    }
    public function render(array $enfants = []): string
    {
        $method  = $this->e($this->method);
        $action  = $this->e($this->action);
        $contenu = $this->renderAll($enfants);
        $token   = $this->e(Csrf::token());

        return <<<HTML
            <form method="{$method}" action="{$action}">
                <input type="hidden" name="csrf_token" value="{$token}">
                {$contenu}
            </form>
            HTML;
    }
}