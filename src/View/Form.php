<?php

namespace App\View;

final class Form extends Component
{
    public function __construct(
        private readonly string $method,
        private readonly string $action
    ){
    }
    public function render(): string
    {
        $method = e($this->method);
        $action = e($this->action);

        return <<<HTML
            <form method="{$method}" action="{$action}"></form>
            HTML;
    }
}