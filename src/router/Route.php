<?php

namespace src\router;

class Route
{
    public function __construct()
    {
    }

    private static $uris = [];

    public static function add($method, $uri, $function = null)
    {
        Route::$uris[] = new Uri(self::parseUri($uri), $method, $function);

        return;
    }

    public static function get($uri, $function = null)
    {
        return Route::add('GET', $uri, $function);
    }

    public static function post($uri, $function = null)
    {
        return Route::add('POST', $uri, $function);
    }

    public static function put($uri, $function = null)
    {
        return Route::add('PUT', $uri, $function);
    }

    public static function delete($uri, $function = null)
    {
        return Route::add('DELETE', $uri, $function);
    }

    public static function any($uri, $function = null)
    {
        return Route::add('ANY', $uri, $function);
    }

    public static function parseUri($uri)
    {
        $uri = trim($uri, '/');
        $uri = (strlen($uri) > 0) ? $uri : '/';
        
        return $uri;
    }

    public static function submit()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = isset($_GET['uri']) ? $_GET['uri'] : '';
        $uri = self::parseUri($uri);

        foreach (Route::$uris as $key => $recordUri) {
            if ($recordUri->match($uri)) {
                return $recordUri->call();
            }
        }

        header('Content-Type: text/html');
        echo "la uri {$uri} no se encuentra registrada en el metodo".$method;
    }
}
