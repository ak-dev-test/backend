<?php

namespace AkDevTodo\Backend\Helpers;


class Arr
{
    public static function get(array $data, string $key, $default = null)
    {
        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $default;
    }
}