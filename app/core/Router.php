<?php

namespace App\Core;

use App\Controller\Controller;

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
        $params = Router::mapParameters($uriPattern);

        $callback = explode('@', $callback);
        $controller = "App\\Controllers\\" . $callback[0];
        $controller = new $controller;
        $function = $callback[1];
        $controller->$function($params);
        die();
        $action = new $controller->$callback[1];

        $action($params);
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
    private static function mapParameters($pattern){
        $realUri = explode('/', $_SERVER['REQUEST_URI']);
        $uriPattern = explode('/', $pattern);
        $dirtyParametersKeys = array_diff($uriPattern, $realUri);
        $cleanedParametersKeys = str_replace(['{', '}'], '', $dirtyParametersKeys);
        $parametersValues = array_diff($realUri, $uriPattern);
        return array_combine($cleanedParametersKeys, $parametersValues);
    }
}
