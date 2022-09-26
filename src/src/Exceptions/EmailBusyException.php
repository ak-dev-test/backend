<?php

namespace AkDevTodo\Backend\Exceptions;

class EmailBusyException extends CustomException
{
    protected $code = 401;
    protected $message = 'Email already in use';
}