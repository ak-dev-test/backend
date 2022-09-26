<?php

namespace AkDevTodo\Backend\Controllers;

use AkDevTodo\Backend\Exceptions\AccessDeniedException;
use AkDevTodo\Backend\Exceptions\EmailBusyException;
use AkDevTodo\Backend\Helpers\Arr;
use AkDevTodo\Backend\Services\AuthService;
use AkDevTodo\Backend\Tools\Response;

class AuthController extends Controller
{
    /**
     * @param array $data
     * @return Response
     * @throws AccessDeniedException
     */
    public function signIn(array $data): Response
    {
//        must be used validation in real project
        $email = Arr::get($data, 'email');
        $password = Arr::get($data, 'password');

        $authService = new AuthService();
        $result = $authService->login($email, $password);

        $this->response->setData($result);

        return $this->response;
    }

    /**
     * @param array $data
     * @return Response
     * @throws EmailBusyException
     */
    public function signUp(array $data): Response
    {
//        must be used validation in real project
        $email = Arr::get($data, 'email');
        $password = Arr::get($data, 'password');

        $authService = new AuthService();
        $result = $authService->register($email, $password);

        $this->response->setData($result);

        return $this->response;
    }
}