<?php

namespace AkDevTodo\Backend\Exceptions;

class IncorrectRouteException extends CustomException
{
    protected $code = 404;
    protected $message = 'Incorrect route';
}