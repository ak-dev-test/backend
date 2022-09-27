<?php

use AkDevTodo\Backend\Controllers\AuthController;
use AkDevTodo\Backend\Controllers\TodoController;
use AkDevTodo\Backend\Exceptions\IncorrectRouteException;
use AkDevTodo\Backend\MiddleWare\AuthCheck;
use AkDevTodo\Backend\Tools\Router;

Router::post('/auth/sign-in', [AuthController::class, 'signIn']);
Router::post('/auth/sign-up', [AuthController::class, 'signUp']);

Router::get('/todo', [TodoController::class, 'getAll', [AuthCheck::class]]);
Router::get('/todo/$id', [TodoController::class, 'getOne', [AuthCheck::class]]);
Router::post('/todo', [TodoController::class, 'create', [AuthCheck::class]]);
Router::patch('/todo/$id', [TodoController::class, 'update', [AuthCheck::class]]);
Router::delete('/todo/$id', [TodoController::class, 'delete', [AuthCheck::class]]);

throw new IncorrectRouteException();