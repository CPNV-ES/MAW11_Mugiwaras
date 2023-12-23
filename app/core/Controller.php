<?php

namespace Mugiwaras\Framework\Core;

use Mugiwaras\Framework\Core\Renderer;

use Mugiwaras\Framework\Core\QueryBuilder;

abstract class Controller
{
    protected Renderer $renderer;
    protected QueryBuilder $qb;

    public function __construct()
    {
        $this->renderer = new Renderer(dirname(__DIR__) . '/views');
        $this->qb = QueryBuilder::getInstance();
    }
}
