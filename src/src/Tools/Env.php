<?php

namespace AkDevTodo\Backend\Tools;

use Dotenv\Dotenv;

class Env
{
    /**
     * @return void
     */
    public static function load(): void
    {
        try {
            Dotenv::createImmutable(__DIR__ . '/../../')->load();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die;
        }

    }

    /**
     * @param string $key
     * @return string|int|bool|null
     */
    public static function get(string $key): string|int|bool|null
    {
        return $_ENV[$key];
    }
}