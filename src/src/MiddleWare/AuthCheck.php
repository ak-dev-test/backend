<?php

namespace AkDevTodo\Backend\MiddleWare;

use AkDevTodo\Backend\App;
use AkDevTodo\Backend\Exceptions\AccessDeniedException;
use AkDevTodo\Backend\Helpers\Arr;
use AkDevTodo\Backend\Reps\UserRep;
use AkDevTodo\Backend\Services\AuthService;

class AuthCheck extends AbstractMiddleWare
{
    public function handle()
    {
        $headers = getallheaders();
        $authHeaderValue = Arr::get($headers, 'Authorization');

        if (empty($authHeaderValue)) {
            throw new AccessDeniedException();
        }

        $token = str_replace('Bearer ', '', $authHeaderValue);

        $authService = new AuthService();
        $userData = $authService->verifyToken($token);

        $userRep = new UserRep();
        $users = $userRep->findBy($userData);

        if (count($users) !== 1) {
            throw new AccessDeniedException();
        }

        App::getInstance()->set('user', $users[0]);
    }

}