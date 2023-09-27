<?php

namespace App\Core;

/**
 * Route class to handle request routing.
 */

class Route
{
    /**
     * The URI pattern the route responds to.
     *
     * @var string
     */
    public $uri;

    /**
     * The HTTP methods the route responds to.
     *
     * @var array
     */
    public $methods;

    public function __construct($methods, $uri, $action)
    {
        $this->uri = $uri;
        $this->methods = (array) $methods;
    }

    /**
     * get
     *
     * @param  string $uri
     * @param  string $callback
     * @return void
     */
    public static function get($uri, $callback)
    {
        $callback = explode('@', $callback);
        $controller = new $callback[0];
        $function = new $controller->$callback[1];

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
