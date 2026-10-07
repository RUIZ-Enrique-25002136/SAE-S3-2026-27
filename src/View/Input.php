<?php

namespace App\View;

final class Input extends Component
{
    public function __construct(
        private readonly string $name,
        private readonly string $label,
        private readonly string $type = 'text',
        private readonly string $value = '',
        private readonly array $attributes = [],
    ) {
    }

    public function render(): string
    {
        $name = $this->e($this->name);
        $label = $this->e($this->label);
        $type = $this->e($this->type);
        $value = $this->e($this->value);
        $extra = $this->renderAttributs();

        return <<<HTML
            <p>
                <label for="{$name}">{$label}</label><br>
                <input type="{$type}" id="{$name}" name="{$name}" value="{$value}" required{$extra}>
            </p>

            HTML;
    }

    private function renderAttributs(): string {
        $out = '';
        foreach ($this->attributes as $name => $value) {
            if ($name === 'required' || $value === false) {
                continue;
            }
            $out .= ' ' . $this->e($name);
            if ($value !== null) {
                $out .= '="' . $this->e((String) $value) . '"';
            }
        }
        return $out;
    }
}