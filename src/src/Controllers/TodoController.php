<?php

namespace AkDevTodo\Backend\Controllers;

use AkDevTodo\Backend\Tools\Response;

class TodoController extends Controller
{

    public function getAll(): Response
    {
        return $this->response;
    }

    public function getOne($id): Response
    {
        // TODO: Implement getOne() method.
        return $this->response;
    }

    public function create($params): Response
    {

        // TODO: Implement create() method.
        return $this->response;
    }

    public function update($id, $params): Response
    {
        // TODO: Implement update() method.
        return $this->response;
    }

    public function delete($id): Response
    {
        // TODO: Implement delete() method.
        return $this->response;
    }
}