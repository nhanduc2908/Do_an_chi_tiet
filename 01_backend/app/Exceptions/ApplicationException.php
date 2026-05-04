<?php

namespace App\Exceptions;

use Exception;

class ApplicationException extends Exception
{
    protected $code = 400;
    
    public function __construct(string $message = "Application error", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}