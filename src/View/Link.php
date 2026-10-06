<?php

namespace App\View;

final class Link extends Component
{
    public function __construct(
        private readonly string $label,
        private readonly string $href,
        private readonly string $class,
    ){
    }

    public function render(): string
    {
        $label = $this->e($this->label);
        $href = $this->e($this->href);
        $class = $this->e($this->class);

        return <<<HTML
            <a href="{$href}" class="{$class}">{$label}</a>
            HTML;

    }
}