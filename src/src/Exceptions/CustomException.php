<?php

namespace AkDevTodo\Backend\Exceptions;

class CustomException extends \Exception
{
    protected $code = 400;
    protected $message = '';
}