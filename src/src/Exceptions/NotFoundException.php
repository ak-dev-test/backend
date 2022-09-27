<?php

namespace AkDevTodo\Backend\Exceptions;

class NotFoundException extends CustomException
{
    protected $code = 404;
    protected $message = 'Resource not found';
}