<?php

namespace App\Exceptions;

use Exception;

class ScreenDetectionException extends Exception
{
    protected $code = 400;
    
    public function __construct(string $message = "Screen detection error", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}