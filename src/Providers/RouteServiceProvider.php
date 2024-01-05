<?php

namespace App\Providers;

use App\Router\Route;
use App\Router\Router;
use App\Containers\ApplicationContainer;
use Exception;

class RouteServiceProvider {
    /**
     * @throws Exception
     */
    public static function boot(ApplicationContainer $container): void
    {
        require_once __DIR__ . '/../../routes/web.php';
        $router = new Router(Route::routes(), $container);
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $router->dispatch($uri, $method);
    }
}
