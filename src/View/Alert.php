<?php

namespace App\View;

final class Alert extends Component
{
    public function __construct(
        array $messages,
        string $type = 'danger',
    ){}

    public function render(): string
    {
        if ($this->messages === []) {
            return '';
        }
        $item = '';
        foreach ($this->messages as $message) {
            $item .= '<li>' . htmlspecialchars($message) . '</li>';
        }
        return '<ul class="alert alert-' . htmlspecialchars($this->type) . '">' . $item . '</ul>';
    }
}