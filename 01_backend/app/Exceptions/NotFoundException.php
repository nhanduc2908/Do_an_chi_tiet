<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $code = 404;
    
    public function __construct(string $message = "Resource not found", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}