<?php

namespace App\Exceptions;

use Exception;

class InfrastructureException extends Exception
{
    protected $code = 500;
    
    public function __construct(string $message = "Infrastructure error", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}