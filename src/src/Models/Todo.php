<?php

namespace AkDevTodo\Backend\Models;

class Todo
{
    public int $id;
    public int $user_id;
    public string $task;
    public string $status;
}