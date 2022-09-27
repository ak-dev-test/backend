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
        $todoService = new TodoService();
        $todoList = $todoService->getAll();
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
        $todoService = new TodoService();
        $todo = $todoService->getOne($id);
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
        $todoService = new TodoService();
        $todo = $todoService->update($id, $params);
        $this->response->setData([$todo]);

        return $this->response;
    }

    /**
     * @param $id
     * @return Response
     * @throws AccessDeniedException
     * @throws NotFoundException
     */
    public function delete($id): Response
    {
        $todoService = new TodoService();
        $todoService->delete($id);

        return $this->response;
    }
}