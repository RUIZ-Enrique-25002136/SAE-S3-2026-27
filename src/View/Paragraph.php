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
        $this.$this->renderAll($this->children);
        return '<p>' . $html . '</p>';
    }
}