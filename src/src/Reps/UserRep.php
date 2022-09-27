<?php

namespace AkDevTodo\Backend\Reps;

use AkDevTodo\Backend\Models\User;

class UserRep extends Rep
{
    protected string $table = 'User';
    protected string $wrapper = User::class;
}