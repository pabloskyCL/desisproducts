<?php

class Autoloader
{
    public static function register()
    {
        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');

            return;
        }

        spl_autoload_register(function ($classname) {
            include_once str_replace('\\', '/', $classname).'.php';
        });
    }
}
