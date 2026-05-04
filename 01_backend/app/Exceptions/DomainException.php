<?php

namespace App\Exceptions;

use Exception;

class DomainException extends Exception
{
    protected $code = 422;
    
    public function __construct(string $message = "Domain rule violated", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}