<?php

namespace App;

/**
 * Router class to handle request routing.
 */

class Router
{
    /**
     * get
     *
     * @param  string $uri
     * @param  string $callback
     * @return void
     */
    public static function get($uri, $callback)
    {
        $params = [];
        if(preg_match('/{[a-z_]+}/i', $uri, $matches)){
            foreach($matches as $match){
                array_push($params, $match);
            }
        }

        $callback = explode('@', $callback);
        $controller = "App\\Controllers\\" . $callback[0];
        $controller = new $controller;
        BaseController::index();
        die();
        $action = new $controller->$callback[1];

        $action($params);
    }

    /**
     * post
     *
     * @param  mixed $uri
     * @param  mixed $callback
     * @return void
     */
    public static function post($uri, $callback)
    {
    }

    /**
     * put
     *
     * @param  mixed $uri
     * @param  mixed $callback
     * @return void
     */
    public static function put($uri, $callback)
    {
    }

    /**
     * patch
     *
     * @param  mixed $uri
     * @param  mixed $callback
     * @return void
     */
    public static function patch($uri, $callback)
    {
    }

    /**
     * delete
     *
     * @param  mixed $uri
     * @param  mixed $callback
     * @return void
     */
    public static function delete($uri, $callback)
    {
    }

    /**
     * view
     *
     * @param  mixed $uri
     * @param  mixed $view
     * @return void
     */
    public static function view($uri, $view)
    {
    }
}
