<?php

require __DIR__ . '/vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__)->load();

Mugiwaras\Framework\Core\Router::get('/images/{id}/create/{img}', 'Controller@index');
Mugiwaras\Framework\Core\Router::post('/images/{id}/create/{img}', 'Controller@create');