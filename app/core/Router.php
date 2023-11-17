<?php

namespace Mugiwaras\Framework\Core;

use Exception;

/**
 * Router class to handle request routing.
 */

class Router
{

    /**
     * Constructor for Router class.
     *
     * @param  array $routes array of Route objects
     * @return void
     */
    public function __construct(private array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Handles the incoming request routing.
     *
     * @return void
     */
    public function run()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        $route = Router::existingRoute($uri, $method);

        $params = Router::mapParameters($route->uriPattern, $uri);

        [$controller, $function] = Router::extractAction($route->action);

        Router::dispatch($controller, $function, $params);
    }

    /**
     * Maps parameters keys from uri pattern to uri values then returns an array of mapped parameters.
     *
     * @param  string $pattern uri pattern to match
     * @return array array of mapped parameters
     */
    private static function mapParameters($pattern, $uri)
    {
        $uriSegments = explode('/', $uri);
        $uriPatternSegments = explode('/', $pattern);

        $dirtyParametersKeys = array_diff($uriPatternSegments, $uriSegments);

        $cleanedParametersKeys = str_replace(['{', '}'], '', $dirtyParametersKeys);

        $parametersValues = array_diff($uriSegments, $uriPatternSegments);

        return array_combine($cleanedParametersKeys, $parametersValues);
    }

    /**
     * Extract the controller class and the function from the callback given. 
     *
     * @param  string $action
     * @return array array containing  controller class and function
     */
    private static function extractAction($action)
    {
        $action = explode('@', $action);
        $controller = "Mugiwaras\\Framework\\Controllers\\" . $action[0];
        $function = $action[1];
        return [$controller, $function];
    }

    /**
     * call the controller class's function with the given parameters.
     *
     * @param  string $controller
     * @param  string $function
     * @param  array $params
     * @return void
     */
    private static function dispatch($controller, $function, $params)
    {
        $controller = new $controller;
        $controller->$function($params);
    }

    /**
     * Match the uri pattern with the uri and the request method with the method given.
     *
     * @param  string $uriPattern
     * @param  string $uri
     * @param  string $requestMethod
     * @param  string $method 
     * @return bool
     * Returns true if a route matches all the conditions, false otherwise.
     */
    private static function validateRoute($uriPattern, $uri, $requestMethod, $method)
    {
        $uriPattern = str_replace('/', '\/', $uriPattern);
        $uriPattern = preg_replace('/{\w+}/', '\w+', $uriPattern);
        $uriPattern = '/^\A' . $uriPattern . '\Z$/';

        if (preg_match($uriPattern, $uri, $matches) && $method == $requestMethod) {
            return true;
        }

        return false;
    }

    /**
     * Find the route that matches the given uri and method.
     *
     * @param  string $uri
     * @param  string $method
     * @return Route
     * @throws Exception if no route matches the given uri and method
     */
    private function existingRoute($uri, $method)
    {
        foreach ($this->routes as $route) {
            if (Router::validateRoute($route->uriPattern, $uri, $method, $route->method)) {
                return $route;
            }
        }
        throw new Exception("Route not found", 404);
    }
}
