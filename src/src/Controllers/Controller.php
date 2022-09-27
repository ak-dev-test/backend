<?php

namespace AkDevTodo\Backend\Controllers;

use AkDevTodo\Backend\Tools\Response;

class Controller
{
    protected Response $response;

    public function __construct()
    {
        $this->response = new Response();
    }
}