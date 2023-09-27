<?php

require './vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__)->load();


require './app/router.php';