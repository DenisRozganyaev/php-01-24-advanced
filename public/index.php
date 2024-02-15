<?php

use Core\Router;

define('BASE_DIR', dirname(__DIR__));

require_once BASE_DIR . '/vendor/autoload.php';

try {
    die(Router::dispatch($_SERVER['REQUEST_URI']));
} catch (Exception $exception) {
    dd($exception);
}
