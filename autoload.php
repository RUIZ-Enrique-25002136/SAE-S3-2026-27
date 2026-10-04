<?php

/**
 * Chargement automatique des classes App\... depuis src/ (par exemple App\Models\User → src/Models/User.php).
 */
spl_autoload_register(function (string $class) : void
{
    $prefix = "App\\";
    if(!str_starts_with($class, $prefix)) {
        return;
    }
    $relative_class = substr($class, strlen($prefix));
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';
    if(file_exists($file)){
        require $file;
    }
});