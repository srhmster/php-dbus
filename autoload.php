<?php

spl_autoload_register(function ($className) {
    $className = str_replace('PhpDbus\\', '', $className);
    
    $path = '';
    foreach (explode('\\', $className) as $item) {
        $path .= "/$item";
    }
    $path = __DIR__ . '/src' . $path . '.php';
    
    if (file_exists($path)) {
        require_once $path;
    }
});