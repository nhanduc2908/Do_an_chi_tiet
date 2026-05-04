<?php

namespace App\Exceptions;

use Exception;

class AIException extends Exception
{
    protected $code = 500;
    
    public function __construct(string $message = "AI service error", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}