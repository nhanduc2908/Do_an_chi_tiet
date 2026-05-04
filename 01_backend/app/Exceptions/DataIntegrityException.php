<?php

namespace App\Exceptions;

use Exception;

class DataIntegrityException extends Exception
{
    protected $code = 422;
    
    public function __construct(string $message = "Data integrity violation", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}