<?php

namespace App\View;

final class Strong extends Component
{
    public function __construct(
        public readonly string $text,
    ){}
    public function render(): string
    {
        return '<strong>' . htmlspecialchars($this->text) . '</strong>';
    }
}