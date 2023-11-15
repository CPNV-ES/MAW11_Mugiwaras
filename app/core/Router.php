<?php

namespace Mugiwaras\Framework\Core;

use Mugiwaras\Framework\Controllers\Controller;
use Exception;

/**
 * Router class to handle request routing.
 */

class Router
{
    /**
     * get
     *
     * @param  string $uriPattern
     * @param  string $callback
     * @return void
     */
    public static function get($uriPattern, $callback)
    {
        $uri = $_SERVER['REQUEST_URI'];

        Router::validateRoute($uriPattern, $uri, 'GET');

        $params = Router::mapParameters($uriPattern, $uri);

        [$controller, $function] = Router::extractAction($callback);

        Router::dispatch($controller, $function, $params);
    }

    /**
     * post
     *
     * @param  mixed $uriPattern
     * @param  mixed $callback
     * @return void
     */
    public static function post($uriPattern, $callback)
    {
    }

    /**
     * put
     *
     * @param  mixed $uriPattern
     * @param  mixed $callback
     * @return void
     */
    public static function put($uriPattern, $callback)
    {
    }

    /**
     * patch
     *
     * @param  mixed $uriPattern
     * @param  mixed $callback
     * @return void
     */
    public static function patch($uriPattern, $callback)
    {
    }

    /**
     * delete
     *
     * @param  mixed $uriPattern
     * @param  mixed $callback
     * @return void
     */
    public static function delete($uriPattern, $callback)
    {
    }

    /**
     * view
     *
     * @param  mixed $uriPattern
     * @param  mixed $view
     * @return void
     */
    public static function view($uriPattern, $view)
    {
    }

    /**
     * Maps parameters keys from uri pattern to uri values then returns an array of mapped parameters.
     *
     * @param  string $pattern - uri pattern to match
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

    private static function dispatch($controller, $function, $params)
    {
        $controller = new $controller;
        $controller->$function($params);
    }

    private static function validateRoute($uriPattern, $uri, $method)
    {
        $uriPattern = str_replace('/', '\/', $uriPattern);
        $uriPattern = preg_replace('/{\w+}/', '\w+', $uriPattern);
        $uriPattern = '/^\A' . $uriPattern . '\Z$/';

        if (!preg_match($uriPattern, $uri, $matches) || $_SERVER['REQUEST_METHOD'] !== $method){
            throw new Exception("Route not found", 404);
        }
    }
}
