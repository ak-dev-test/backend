<?php

use AkDevTodo\Backend\Controllers\TodoController;
use AkDevTodo\Backend\Tools\Router;

Router::get('/todo', [TodoController::class, 'getAll']);
Router::get('/todo/$id', [TodoController::class, 'getOne']);
Router::post('/todo', [TodoController::class, 'create']);
Router::patch('/todo/$id', [TodoController::class, 'update']);
Router::delete('/todo/$id', [TodoController::class, 'delete']);