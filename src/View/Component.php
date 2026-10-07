<?php

namespace App\View;

abstract class Component
{
    abstract public function render(): string;

    public function __toString(): string
    {
        return $this->render();
    }

    protected function e(string $texte): string
    {
        return htmlspecialchars($texte, ENT_QUOTES);
    }

    /** @param array<Component|string> $enfants */
    protected function renderAll(array $enfants): string
    {
        $html = '';
        foreach ($enfants as $enfant) {
            $html .= $enfant instanceof Component ? $enfant->render() : $this->e($enfant);
        }

        return $html;
    }
}