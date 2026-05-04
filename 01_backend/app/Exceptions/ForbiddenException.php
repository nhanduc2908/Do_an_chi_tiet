<?php

namespace App\Exceptions;

use Exception;

class ForbiddenException extends Exception
{
    protected $code = 403;
    
    public function __construct(string $message = "You don't have permission", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}