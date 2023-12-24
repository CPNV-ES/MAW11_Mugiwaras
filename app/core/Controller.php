<?php

namespace Mugiwaras\Framework\Core;

use Mugiwaras\Framework\Core\Renderer;

abstract class Controller
{
    protected Renderer $renderer;

    public function __construct()
    {
        $this->renderer = new Renderer(dirname(__DIR__) . '/views');
    }
}
