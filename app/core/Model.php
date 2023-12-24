<?php

namespace Mugiwaras\Framework\Core;

use Mugiwaras\Framework\Core\QueryBuilder;

abstract class Controller
{
    protected QueryBuilder $qb;

    public function __construct()
    {
        $this->qb = QueryBuilder::getInstance();
    }
}
