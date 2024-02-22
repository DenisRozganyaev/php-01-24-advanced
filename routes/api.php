<?php

use Core\Router;

//Router::get('users/{id:\d+}/edit')->controller(\App\Controllers\UsersController::class)->action('edit');
Router::post('api/auth/registration')
    ->controller(\App\Controllers\AuthController::class)
    ->action('signUp');
Router::post('api/auth')
    ->controller(\App\Controllers\AuthController::class)
    ->action('signIn');

Router::get('api/folders')
    ->controller(\App\Controllers\FoldersController::class)
    ->action('index');
Router::get('api/folders/{id:\d+}')
    ->controller(\App\Controllers\FoldersController::class)
    ->action('show');
Router::post('api/folders/store')
    ->controller(\App\Controllers\FoldersController::class)
    ->action('store');
