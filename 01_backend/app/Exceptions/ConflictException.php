<?php

namespace App\Exceptions;

use Exception;

class ConflictException extends Exception
{
    protected $code = 409;
    
    public function __construct(string $message = "Resource conflict", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}