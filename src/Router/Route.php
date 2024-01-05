<?php

namespace App\Router;

class Route
{
    /**
     * @var array
     */
    private static array $routes = [];

    /**
     * @param $uri
     * @param $action
     * @return void
     */
    public static function get($uri, $action): void
    {
        self::$routes['GET'][$uri] = $action;
    }

    /**
     * @return array
     */
    public static function routes(): array
    {
        return self::$routes;
    }
}