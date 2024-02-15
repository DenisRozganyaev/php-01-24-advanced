<?php

use Core\Router;

Router::get('users/{id:\d+}/edit')->controller(\App\Controllers\UsersController::class)->action('edit');
//Router::get('articles/hello-world')->controller(\App\Controllers\UsersController::class)->action('edit');
