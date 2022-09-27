<?php

namespace AkDevTodo\Backend\Controllers;

use AkDevTodo\Backend\App;
use AkDevTodo\Backend\Exceptions\AccessDeniedException;
use AkDevTodo\Backend\Exceptions\NotFoundException;
use AkDevTodo\Backend\Services\TodoService;
use AkDevTodo\Backend\Tools\Response;

class TodoController extends Controller
{

    /**
     * @return Response
     * @throws AccessDeniedException
     */
    public function getAll(): Response
    {
        $user = App::getInstance()->user();
        $todoService = new TodoService();
        $todoList = $todoService->getAll(['user_id' => $user->getId()]);
        $this->response->setData($todoList);

        return $this->response;
    }

    /**
     * @param int $id
     * @return Response
     * @throws AccessDeniedException
     * @throws NotFoundException
     */
    public function getOne(int $id): Response
    {
        $user = App::getInstance()->user();
        $todoService = new TodoService();
        $todo = $todoService->getOne($id, ['user_id' => $user->getId()]);
        $this->response->setData([$todo]);

        return $this->response;
    }

    /**
     * @param array $params
     * @return Response
     * @throws AccessDeniedException
     */
    public function create(array $params): Response
    {
        $user = App::getInstance()->user();
        $params['user_id'] = $user->getId();

        $todoService = new TodoService();
        $todoService->create($params);

        return $this->response;
    }

    public function update($id, $params): Response
    {


        return $this->response;
    }

    public function delete($id): Response
    {
        // TODO: Implement delete() method.
        return $this->response;
    }
}