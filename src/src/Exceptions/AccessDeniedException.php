<?php

namespace AkDevTodo\Backend\Exceptions;

class AccessDeniedException extends CustomException
{
    protected $code = 403;
    protected $message = 'Access denied';
}