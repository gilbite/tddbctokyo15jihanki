<?php

spl_autoload_register(function($name){
    $path = false;

    $name = explode('\\', $name);
    
    if (0 === strcmp($name[0], 'Gilbite')) {
        array_shift($name);
    } else {
        return false;
    }

    if (0 === strcmp($name[1], 'Tests')) {
        $path = __DIR__ . '/' . implode('/', $name) . '.php';
    } else {
        $path = __DIR__ . '/../src/' . implode('/', $name) . '.php';
    }

    if (file_exists($path)) {
        require_once($path);
        return true;
    } else {
        return false;
    }

});
