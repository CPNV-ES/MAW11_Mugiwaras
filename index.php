<?php

require __DIR__ . '/vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__)->load();

App\Core\Router::get('/images/{id}/create/{img}', 'Controller@index');