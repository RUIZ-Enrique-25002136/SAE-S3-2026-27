<?php

namespace App\View;

final class Paragraph extends Component
{
    public function __construct(
        public array $children = [],
    ){
    }
    public function render(): string
    {
        $html = '';
        foreach ($this->children as $child) {
            $html .= $child instanceof Component ? $child->render() : htmlspecialchars($child);
        }
        return '<p>' . $html . '</p>';
    }
}