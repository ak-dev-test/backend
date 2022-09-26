<?php

namespace AkDevTodo\Backend\Services;

use AkDevTodo\Backend\Exceptions\AccessDeniedException;
use AkDevTodo\Backend\Exceptions\EmailBusyException;
use AkDevTodo\Backend\Models\User;
use AkDevTodo\Backend\Reps\UserRep;
use AkDevTodo\Backend\Tools\Env;
use Firebase\JWT\JWT;

class AuthService
{
    /**
     * @param string $email
     * @param string $password
     * @return array
     * @throws AccessDeniedException
     */
    public function login(string $email, string $password): array
    {
        $userRep = new UserRep();
        $userData = ['email' => $email];
        $users = $userRep->findBy($userData);

        if (count($users) === 0) {
            throw new AccessDeniedException();
        }

        /** @var $user User */
        $user = $users[0];

        if (!password_verify($password, $user->getPassword())) {
            throw new AccessDeniedException();
        }

        $token = JWT::encode($userData, Env::get('JWT_KEY'), Env::get('JWT_ALG'));

        return ['token' => $token];
    }

    /**
     * @param string $email
     * @param string $password
     * @return array
     * @throws EmailBusyException
     */
    public function register(string $email, string $password): array
    {
        $userRep = new UserRep();
        $users = $userRep->findBy([
            'email' => $email
        ]);

        if (count($users) > 0) {
            throw new EmailBusyException();
        }

        $userRep->create([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ]);

        return [];
    }
}