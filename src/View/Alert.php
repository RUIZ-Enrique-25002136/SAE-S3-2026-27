<?php

namespace App\View;

final class Alert extends Component
{
    public function __construct(
        private readonly array $messages,
        private readonly string $type = 'danger',
    ){}

    public function render(): string
    {
        if ($this->messages === []) {
            return '';
        }
        $item = '';
        foreach ($this->messages as $message) {
            $item .= '<li>' . $this->e($message) . '</li>';
        }
        return '<ul class="alert alert-' . $this->e($this->type) . '">' . $item . '</ul>';
    }
}