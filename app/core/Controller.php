<?php

namespace Mugiwaras\Framework\Core;

use Mugiwaras\Framework\Core\Renderer;

abstract class Controller
{
    protected Renderer $renderer;

    public function __construct($viewPath)
    {
        if (!isset($viewPath)) {
            throw new \Exception("View path is not set");
        }
        $this->renderer = new Renderer($viewPath);
    }
}
