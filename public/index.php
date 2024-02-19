<?php

use Core\Router;
use function Core\json_response;

define('BASE_DIR', dirname(__DIR__));

require_once BASE_DIR . '/vendor/autoload.php';

try {
    $dotenv = \Dotenv\Dotenv::createUnsafeImmutable(BASE_DIR);
    $dotenv->load();

    die(Router::dispatch($_SERVER['REQUEST_URI']));
} catch (Exception $exception) {
    die(
    json_response(
        $exception->getCode(),
        [
            'errors' => [
                'message' => $exception->getMessage()
            ]
        ]
    )
    );
}
