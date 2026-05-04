<?php

namespace App\Exceptions;

use Exception;

class SyncException extends Exception
{
    protected $code = 500;
    
    public function __construct(string $message = "Synchronization error", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}