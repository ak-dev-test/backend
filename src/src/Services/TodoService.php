<?php

namespace AkDevTodo\Backend\Services;

use AkDevTodo\Backend\App;
use AkDevTodo\Backend\Exceptions\AccessDeniedException;
use AkDevTodo\Backend\Exceptions\NotFoundException;
use AkDevTodo\Backend\Helpers\Arr;
use AkDevTodo\Backend\Models\Todo;
use AkDevTodo\Backend\Reps\TodoRep;

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

    /**
     * @return array
     * @throws AccessDeniedException
     */
    public function getAll(): array
    {
        $user = App::getInstance()->user();
        $todoRep = new TodoRep();

        return $todoRep->findBy(['user_id' => $user->getId()]);
    }


    /**
     * @param int $id
     * @param array $params
     * @return Todo
     * @throws AccessDeniedException
     * @throws NotFoundException
     */
    public function getOne(int $id): Todo
    {
        $todoRep = new TodoRep();
        $todolist = $todoRep->findBy(['id' => $id]);

        if (count($todolist) !== 1) {
            throw new NotFoundException();
        }

        $todo = $todolist[0];

        if ($todo->user_id !== App::getInstance()->user()->getId()) {
            throw new AccessDeniedException();
        }

        return $todo;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Todo
     * @throws AccessDeniedException
     * @throws NotFoundException
     */
    public function update(int $id, array $data): Todo
    {
        $todo = $this->getOne($id);

        $todoRep = new TodoRep();
        $user = App::getInstance()->user();
        $data = array_merge($data, ['user_id' => $user->getId()]);
        $todoRep->update($id, $data);

        return $this->getOne($id);
    }

    /**
     * @param int $id
     * @return void
     * @throws AccessDeniedException
     * @throws NotFoundException
     */
    public function delete(int $id): void
    {
        $todo = $this->getOne($id);

        $todoRep = new TodoRep();
        $todoRep->delete($id);
    }

}