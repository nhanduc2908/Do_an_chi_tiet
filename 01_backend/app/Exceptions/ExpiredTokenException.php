<?php

namespace App\Exceptions;

use Exception;

class ExpiredTokenException extends Exception
{
    protected $code = 401;
    
    public function __construct(string $message = "Token has expired", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}