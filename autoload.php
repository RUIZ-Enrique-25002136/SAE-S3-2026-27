<?php

spl_autoload_register(function (string $class) : void
{
    $prefixe = "App\\";
    if(!str_starts_with($class, $prefixe)) {
        return;
    }
    $relative_class = substr($class, strlen($prefixe));
    $file = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';
    if(file_exists($file)){
        require $file;
    }
});