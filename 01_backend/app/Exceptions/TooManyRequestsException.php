<?php

namespace App\Exceptions;

use Exception;

class TooManyRequestsException extends Exception
{
    protected $code = 429;
    
    public function __construct(string $message = "Too many requests", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}