<?php

namespace App\Exceptions;

use Exception;

class DeviceConnectionException extends Exception
{
    protected $code = 400;
    
    public function __construct(string $message = "Device connection error", ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}