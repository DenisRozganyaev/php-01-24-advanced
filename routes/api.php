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
Router::get('api/folders/{folder_id:\d+}/notes')
    ->controller(\App\Controllers\FoldersController::class)
    ->action('notes');
Router::post('api/folders/store')
    ->controller(\App\Controllers\FoldersController::class)
    ->action('store');
Router::put('api/folders/{id:\d+}/update')
    ->controller(\App\Controllers\FoldersController::class)
    ->action('update');
Router::delete('api/folders/{id:\d+}/destroy')
    ->controller(\App\Controllers\FoldersController::class)
    ->action('destroy');

Router::get('api/notes')
    ->controller(\App\Controllers\NotesController::class)
    ->action('index');
Router::get('api/notes/{id:\d+}')
    ->controller(\App\Controllers\NotesController::class)
    ->action('show');
Router::post('api/notes/store')
    ->controller(\App\Controllers\NotesController::class)
    ->action('store');
Router::put('api/notes/{id:\d+}/update')
    ->controller(\App\Controllers\NotesController::class)
    ->action('update');
Router::delete('api/notes/{id:\d+}/destroy')
    ->controller(\App\Controllers\NotesController::class)
    ->action('destroy');


Router::post('api/notes/{note_id:\d+}/share/add')
    ->controller(\App\Controllers\SharedNotesController::class)
    ->action('add');
Router::delete('api/notes/{id:\d+}/shared/remove')
    ->controller(\App\Controllers\SharedNotesController::class)
    ->action('remove');
