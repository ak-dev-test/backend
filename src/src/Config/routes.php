<?php

use AkDevTodo\Backend\Controllers\AuthController;
use AkDevTodo\Backend\Controllers\TodoController;
use AkDevTodo\Backend\Tools\Router;

Router::post('/auth/sign-in', [AuthController::class, 'signIn']);
Router::post('/auth/sign-up', [AuthController::class, 'signUp']);

Router::get('/todo', [TodoController::class, 'getAll']);
Router::get('/todo/$id', [TodoController::class, 'getOne']);
Router::post('/todo', [TodoController::class, 'create']);
Router::patch('/todo/$id', [TodoController::class, 'update']);
Router::delete('/todo/$id', [TodoController::class, 'delete']);