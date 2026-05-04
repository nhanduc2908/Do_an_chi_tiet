<?php

namespace App\Exceptions;

use Exception;

class InvalidKeyException extends Exception
{
    protected $code = 401;
    
    public function __construct(string $message = "Invalid security key", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}