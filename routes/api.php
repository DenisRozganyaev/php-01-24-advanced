<?php

use Core\Router;

//Router::get('users/{id:\d+}/edit')->controller(\App\Controllers\UsersController::class)->action('edit');
Router::post('api/auth/registration')
    ->controller(\App\Controllers\AuthController::class)
    ->action('signUp');
Router::post('api/auth')
    ->controller(\App\Controllers\AuthController::class)
    ->action('signIn');
