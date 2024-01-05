<?php

namespace App\Router;

use App\Containers\ApplicationContainer;
use App\Responses\Response;
use Exception;

class Router {
    /**
     * @var mixed
     */
    private mixed $routes;

    /**
     * @var ApplicationContainer
     */
    private ApplicationContainer $container;

    /**
     * @param $routes
     * @param ApplicationContainer $container
     */
    public function __construct($routes, ApplicationContainer $container) {
        $this->routes = $routes;
        $this->container = $container;
    }

    /**
     * @param $uri
     * @param $requestMethod
     * @return void
     * @throws Exception
     */
    public function dispatch($uri, $requestMethod): void
    {
        if ($uri) {
            foreach ($this->routes[$requestMethod] ?? [] as $routeUri => $action) {

                $routePattern = preg_replace('/\{[^}]+}/', '([^/]+)', $routeUri);

                if (preg_match('#^' . $routePattern . '$#', $uri, $matches)) {
                    array_shift($matches);

                    if (is_array($action) && count($action) === 2 && class_exists($action[0])) {
                        $controller = $this->resolveController($action[0]);
                        $method = $action[1];

                        if (method_exists($controller, $method)) {
                            $response = call_user_func_array([$controller, $method], array_values($matches));
                            if ($response instanceof Response) {
                                $response->send();
                                return;
                            }
                        }
                    }
                }
            }
        } else {
            Response::make()->json(['error' => 'Not Found'])->status(404)->send();
        }
    }

    /**
     * @param $controllerName
     * @return mixed|object|null
     * @throws Exception
     */
    protected function resolveController($controllerName): mixed
    {
        return $this->container->make($controllerName);
    }
}
