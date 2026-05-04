<?php

namespace App\Exceptions;

use Exception;

class EncryptionException extends Exception
{
    protected $code = 500;
    
    public function __construct(string $message = "Encryption error", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}