<?php

spl_autoload_register(function (string $class) : void
{
    $prefixe = "App\\";
    if(!str_starts_with($class, $prefixe)) {
        return;
    }
    $relative_class = substr($class, strlen($prefixe));
});