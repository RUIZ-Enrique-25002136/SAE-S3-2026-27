<?php

namespace App\View;

final class Paragraph extends Component
{
    public function __construct(
        public readonly string $text,
    ){
    }
    public function render(): string
    {
        $text = $this->e($this->text);

        return <<<HTML
            <p>{$text}</p>
            HTML;
    }
}