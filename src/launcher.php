<?php

require_once './src/autoloader/Autoloader.php';

use src\router\Route;

Autoloader::register();

$routes = scandir('./routes/');

foreach ($routes as $route) {
    $rutaArchivo = realpath('./routes/'.$route);
    if (is_file($rutaArchivo)) {
        require $rutaArchivo;
    }
}

Route::submit();

