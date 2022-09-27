<?php

namespace AkDevTodo\Backend\Reps;

use AkDevTodo\Backend\Models\Todo;

class TodoRep extends Rep
{
    protected string $table = 'Todo';
    protected string $wrapper = Todo::class;
}