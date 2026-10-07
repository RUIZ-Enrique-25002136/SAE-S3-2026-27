<?php

namespace App\View;

final class Button extends Component
{
    public function __construct(
        private readonly string $label,
        private readonly string $name,
        private readonly string $value,
        private readonly string $type = 'submit',
        private readonly string $class = 'btn btn-primary',
    ){
    }
    public function render(): string
    {
        $label = $this->e($this->label);
        $name = $this->e($this->name);
        $value = $this->e($this->value);
        $type = $this->e($this->type);
        $class = $this->e($this->class);

        return <<<HTML
            <button type="{$type}" name="{$name}" value="{$value}" class="{$class}">{$label}</button>
            HTML;
    }
}