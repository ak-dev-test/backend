<?php

namespace AkDevTodo\Backend\Services;

use AkDevTodo\Backend\Exceptions\AccessDeniedException;
use AkDevTodo\Backend\Exceptions\EmailBusyException;
use AkDevTodo\Backend\Exceptions\NotFoundException;
use AkDevTodo\Backend\Helpers\Arr;
use AkDevTodo\Backend\Models\Todo;
use AkDevTodo\Backend\Models\User;
use AkDevTodo\Backend\Reps\TodoRep;
use AkDevTodo\Backend\Reps\UserRep;
use AkDevTodo\Backend\Tools\Env;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TodoService
{
    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $todoRep = new TodoRep();

        $todoRep->create($data);

        return true;
    }


    public function getAll(array $params): array
    {
        $todoRep = new TodoRep();
        return $todoRep->findBy($params);
    }


    /**
     * @param int $id
     * @param array $params
     * @return Todo
     * @throws AccessDeniedException
     * @throws NotFoundException
     */
    public function getOne(int $id, array $params): Todo
    {
        $todoRep = new TodoRep();
        $todolist = $todoRep->findBy(['id' => $id]);

        if (count($todolist) !== 1) {
            throw new NotFoundException();
        }

        $todo = $todolist[0];

        if ($todo->user_id !== Arr::get($params, 'user_id')) {
            throw new AccessDeniedException();
        }

        return $todo;
    }

}