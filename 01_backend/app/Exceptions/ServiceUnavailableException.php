<?php

namespace App\Exceptions;

use Exception;

class ServiceUnavailableException extends Exception
{
    protected $code = 503;
    
    public function __construct(string $message = "Service temporarily unavailable", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}