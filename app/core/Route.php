<?php

namespace Mugiwaras\Framework\Core;

class Route
{
    readonly string $method;
    public function __construct($method, readonly string $uriPattern, readonly string $action)
    {
        $this->method = strtoupper($method);
    }
}
