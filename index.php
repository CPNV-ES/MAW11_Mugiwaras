<?php

use App;

require './vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__)->load();

Router::get('/{hello}', 'Controller@index');